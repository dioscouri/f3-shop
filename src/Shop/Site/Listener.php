<?php 
namespace Shop\Site;

class Listener extends \Dsc\Singleton 
{
	/*public function afterUserLogin( $event ) 
	{
	    $user = $event->getArgument('identity');
	    
	    if (empty($user->id) || empty($user->email))
	    {
	        return;
	    }
	    
	    $customer = (new \Shop\Models\Customers)->load(array('_id' => new \MongoId( (string) $user->_id ) ));
	    if (!empty($customer->id))
	    {
	        try
	        {
	            $customer->{'shop.total_spent'} = $customer->totalSpent(true);
	            $customer->{'shop.orders_count'} = $customer->ordersCount(true);
	            $customer->save();
	             
	            $user->reload();
	        }
	        catch (\Exception $e) 
	        {
	            $customer->log($e->getMessage(), 'ERROR', 'ShopSiteListener::afterUserLogin.customerCounts');
	        }
	        
	        // Only do this once a day
	        $last_campaign_check_datetime = $user->get('shop.last_campaign_check_datetime');
	        if (empty($last_campaign_check_datetime) || strtotime( $last_campaign_check_datetime ) < strtotime('today') )
	        {
	            try
	            {
	                $customer->checkCampaigns();
	                $customer->set('shop.last_campaign_check_datetime', date('Y-m-d', strtotime('now')) );
	                $customer->save();
	        
	                $user->reload();
	        
	                // After running this, unset the session variable so the bootstrap file doesn't include the JS file
	                \Dsc\System::instance()->get('session')->set('shop.check_campaigns', false);
	            }
	            catch (\Exception $e) 
	            {
	                $customer->log($e->getMessage(), 'ERROR', 'ShopSiteListener::afterUserLogin.checkCampaigns');
	            }
	        }	        
	    }	    
	}*/
	
}