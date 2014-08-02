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
            // abandoned carts are only valid when they have items in them
            $this->setCondition('items', array(
                '$not' => array(
                    '$size' => 0
                )
            ));
            
            if (!$or = $this->getCondition('$or'))
            {
                $or = array();
            }
            
            // only users with known emails
            $or[] = array(
                'user_email' => array(
                    '$nin' => array('', null)
                )
            );
            
            $or[] = array(
                'user_id' => array(
                    '$nin' => array('', null)
                )
            );
            
            $this->setCondition('$or', $or);
            
            $settings = \Shop\Models\Settings::fetch();
            $abandoned_time = $settings->get('abandoned_cart_time') * 60;
            
            // set starting date and time for abandoned carts?
            $filter_abandoned_datetime = $this->getState('filter.abandoned.datetime');
            if (!empty($filter_abandoned_datetime))
            {
                $abandoned_time = $filter_abandoned_datetime - $abandoned_time;
            }
            else
            { // or use current timestamp
                $abandoned_time = time() - $abandoned_time;
            }
            
            $this->setCondition('metadata.last_modified.time', array(
                '$lt' => $abandoned_time
            ));
            
            // only newly abandoned carts
            $filter_only_new = $this->getState('filter.abandoned_only_new', 0);
            if ($filter_only_new)
            {
                // TODO OR NULL
                $this->setCondition('abandoned_notifications', array(
                    '$size' => 0
                ));
            }
        }
    }

    /**
     * Finds all carts that are abandoned and adds jobs for email notifications to queue manager
     */
    public static function queueEmailsForNewlyAbandonedCarts()
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
                    // scheduling should be relative to when this job runs, not the time of the cart's last modification, 
                    // because that could lead to lots of emails at once for really old carts
                    // if the cron job is started months after the site goes live
                    $time = $abandoned_time + time() + $val['delay'] * 60;
                    $task = \Dsc\Queue::task('\Shop\Models\CartsAbandoned::sendAbandonedEmailNotification', array(
                        (string) $cart->id,
                        (string) $idx
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

    /**
     * 
     * @param unknown $cart_id
     * @param unknown $notification_idx
     */
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
        
        // Has the cart been updated recently?  if so, don't send this email
        $abandoned_time = $settings->get('abandoned_cart_time') * 60;
        $abandoned_time = time() - $abandoned_time;
        if ($cart->{'metadata.last_modified.time'} > $abandoned_time) 
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
        
        $num = $notification_idx + 1;
        \Dsc\Activities::trackActor($user->email, 'Received abandoned cart email notification #' . $num, array(
            'cart_value' => (string) $cart->total(),
            'cart_items_count' => (string) $cart->quantity()
        ));
    }

    /**
     * Delete any queued email notifications for this cart
     */
    public function deleteAbandonedEmailNotifications()
    {
        return static::deleteQueuedEmails( $this );
    }
    
    /**
     * Delete any queued email notifications for this cart
     * 
     * Kinda chainable
     */    
    public static function deleteQueuedEmails( \Shop\Models\Carts $cart )
    {
        if (empty($cart->abandoned_notifications))
        {
            return $cart;
        }
        
        if ($ids = array_values($cart->abandoned_notifications))
        {
            \Dsc\Mongo\Collections\QueueTasks::collection()->remove(array(
                '_id' => array(
                    '$in' => $ids
                )
            ));
        }
        
        return $cart;
    }
}