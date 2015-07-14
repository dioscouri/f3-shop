<?php
namespace Shop;

class Listener extends \Prefab
{


    
    public function onSystemRegisterEmails($event)
    {
    	if (class_exists('\Mailer\Factory'))
    	{
    
    		$model = (new \Mailer\Models\Events);
    
    		\Mailer\Models\Events::register('shop.new_order',
    				[
    						'title' => 'New Order Created',
    						'copy' => 'Sent when a new order is placed',
    						'app' => 'Shop',
    				],
    				[
    						'event_subject' => 'Thank You For Your Order',
    						'event_html' => file_get_contents(__DIR__.'/Emails/html/new_order.php'),
    						'event_text' => file_get_contents(__DIR__.'/Emails/text/new_order.php')
    				]
    		);
    		
    		\Mailer\Models\Events::register('shop.new_order_notification',
    				[
    						'title' => 'New Order Created',
    						'copy' => 'Sent when a new order is placed',
    						'app' => 'Shop',
    				],
    				[
    						'event_subject' => 'New Order Notification',
    						'event_html' => file_get_contents(__DIR__.'/Emails/html/new_order_notification.php'),
    						'event_text' => file_get_contents(__DIR__.'/Emails/text/new_order_notification.php')
    				]
    		);
    		
    		\Mailer\Models\Events::register('shop.abandoned_cart',
    				[
    						'title' => 'Shopping Cart Abandoned',
    						'copy' => 'Sent after some time of leaving a cart open',
    						'app' => 'Shop',
    				],
    				[
    						'event_subject' => 'Hey forget something?',
    						'event_html' => file_get_contents(__DIR__.'/Emails/html/abandoned_cart.php'),
    						'event_text' => file_get_contents(__DIR__.'/Emails/text/abandoned_cart.php')
    				]
    		);
    		
    		\Mailer\Models\Events::register('shop.review_products',
    				[
    						'title' => 'Product Review Request',
    						'copy' => 'Sent some time after order is placed and askes for reviewing products',
    						'app' => 'Shop',
    				],
    				[
    						'event_subject' => 'Thank You For Your Order',
    						'event_html' => file_get_contents(__DIR__.'/Emails/html/review_products.php'),
    						'event_text' => file_get_contents(__DIR__.'/Emails/text/review_products.php')
    				]
    		);
    		
    		\Mailer\Models\Events::register('shop.new_giftcard',
    				[
    						'title' => 'New Gift Card',
    						'copy' => 'Sent when gift card fulfilled',
    						'app' => 'Shop',
    				],
    				[
    						'event_subject' => 'New Gift Card',
    						'event_html' => file_get_contents(__DIR__.'/Emails/html/new_gift_card.php'),
    						'event_text' => file_get_contents(__DIR__.'/Emails/text/new_gift_card.php')
    				]
    		);
    
    		\Dsc\System::instance()->addMessage('Shop added its emails.');
    	}
    }
    
}