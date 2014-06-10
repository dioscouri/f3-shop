<?php 
namespace Shop\Site\Controllers;

class Customer extends \Dsc\Controller 
{    
    public function checkCampaigns()
    {
        // Disabled for now
        return; 
        
        $user = $this->getIdentity();
        
        if (empty($user->id) || empty($user->email))
        {
            return;
        }

        // Only do this once a day
        $last_campaign_check_datetime = $user->get('shop.last_campaign_check_datetime');
        if (empty($last_campaign_check_datetime) || strtotime( $last_campaign_check_datetime ) < strtotime('today') )
        {
            $customer = (new \Shop\Models\Customers)->load(array('_id' => new \MongoId( (string) $user->_id ) ));
            if (!empty($customer->id))
            {
                $customer->checkCampaigns();
            
                $customer->set('shop.last_campaign_check_datetime', date('Y-m-d', strtotime('now')) );
                $customer->save();
            
                // After running this, unset the session variable so the bootstrap file doesn't include the JS file
                \Dsc\System::instance()->get('session')->set('shop.check_campaigns', false);
            }            
        }
    }
}