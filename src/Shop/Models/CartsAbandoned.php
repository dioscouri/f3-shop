<?php
namespace Shop\Models;

class CartsAbandoned extends \Shop\Models\Carts
{

    protected function fetchConditions()
    {
        parent::fetchConditions();
        
        $filter_abandoned = $this->getState('filter.abandoned', 0);
        
        if ($filter_abandoned)
        {
            
            if (!$or = $this->getCondition('$or'))
            {
                $or = array();
            }
            
            // only users with known emails
            $or[] = array(
                'user_email' => array(
                    '$ne' => null
                )
            );
            $or[] = array(
                'user_id' => array(
                    '$ne' => null
                )
            );
            
            $settings = \Shop\Models\Settings::fetch();
            $abandoned_time = $settings->get('abandoned_cart_time') * 60;
            
            // set starting date and time for abandoned carts?
            $filter_abandoned_datetime = $this->getState('filter.abandoned.datetime');
            if (!empty($filter_abandoned_datetime))
            {
                $abandoned_time += $filter_abandoned_datetime;
            }
            else
            { // or use current timestamp
                $abandoned_time += time();
            }
            
            $this->setCondition('metadata.last_modified.time', array(
                '$lt' => $abandoned_time
            ));
            
            // only newly abandoned carts
            $filter_only_new = $this->getState('filter.abandoned_only_new', 0);
            if ($filter_only_new)
            {
                $this->setCondition('abandoned_notifications', array(
                    '$not' => array(
                        '$size' => 0
                    )
                ));
            }
            
            $this->setCondition('$or', $or);
        }
    }

    /**
     * Finds all carts that are abandoned and adds jobs for email notifications to queue manager
     */
    public function findNewlyAbandonedCarts()
    {
        $newly_abandoned = (new static())->setState('filter.abandoned', '1')
            ->setState('filter.abandoned_only_new', '1')
            ->getList();
        
        $settings = \Shop\Models\Settings::fetch();
        
        if (count((array) $newly_abandoned))
        {
            $notifications = (array) $settings->get('abandoned_cart_emails');
            $abandoned_time = $settings->get('abandoned_cart_time') * 60;
            foreach ($newly_abandoned as $cart)
            {
                $cart->abandoned_notifications = array();
                foreach ($notifications as $idx => $val)
                {
                    
                    $time = $abandoned_time + $cart->{'metadata.last_modified.time'} + $val['delay'] * 60;
                    $task = \Dsc\Queue::task('\Shop\Models\CartsAbandoned::sendAbandonedEmailNotification', array(
                        (string) $cart->id,
                        $idx
                    ), array(
                        'title' => 'Abandoned Cart Email Notification',
                        'when' => $time
                    ));
                    $cart->abandoned_notifications[] = new \MongoId((string) $task->_id);
                }
                
                // save reference to those task to the cart without modifying last_modified timestamp
                $cart->store();
            }
        }
    }

    public static function sendAbandonedEmailNotification($cart_id, $notification_idx)
    {
        $settings = \Shop\Models\Settings::fetch();
        $subject = $settings->get('abandoned_cart_subject');
        $cart = (new static())->setState('filter.id', $cart_id)->getItem();
        
        // cart was deleted so dont do anything
        if (empty($cart->id))
        {
            return;
        }
        $user = (new \Users\Models\Users())->setState('filter.id', $cart->user_id)->getItem();
        
        // get correct user email
        $recipients = array();
        if (empty($cart->{'user_email'}))
        {
            $recipients = array(
                $user->email
            );
        }
        else
        {
            $recipients = array(
                $cart->{'user_email'}
            );
        }
        
        $token = \Dsc\System::instance()->get('auth')->getAutoLoginToken($user, true);
        
        \Base::instance()->set('cart', $cart);
        \Base::instance()->set('user', $user);
        \Base::instance()->set('idx', $notification_idx);
        \Base::instance()->set('token', $token);
        
        $notification = $settings->get('abandoned_cart_emails.' . $notification_idx);
        if (empty($notification))
        {
            $notification = array(
                'text' => array(
                    'html' => '',
                    'plain' => ''
                )
            );
        }
        \Base::instance()->set('notification', $notification);
        
        $html = \Dsc\System::instance()->get('theme')->renderView('Shop/Views::emails_html/abandoned_cart.php');
        $text = \Dsc\System::instance()->get('theme')->renderView('Shop/Views::emails_text/abandoned_cart.php');
        
        foreach ($recipients as $recipient)
        {
            \Dsc\System::instance()->get('mailer')->send($recipient, $subject, array(
                $html,
                $text
            ));
        }
        
        \Dsc\Activities::trackActor($user->email, 'Sent abandoned cart email notification', array(
            'cart_value' => $cart->total(),
            'cart_items_count' => $cart->quantity()
        ));
    }

    public function deleteAbandonedEmailNotifications()
    {
        if (empty($this->abandoned_notifications))
        {
            return;
        }
        
        $ids = array_values($this->abandoned_notifications);
        \Dsc\Mongo\Collections\QueueTasks::collection()->remove(array(
            '_id' => array(
                '$in' => $ids
            )
        ));
        $this->abandoned_notifications = array();
    }
}