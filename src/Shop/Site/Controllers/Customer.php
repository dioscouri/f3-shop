<?php 
namespace Shop\Site\Controllers;

class Customer extends \Dsc\Controller 
{    
    public function checkCampaigns()
    {
        $user = $this->getIdentity();
        
        if (empty($user->id) || empty($user->email))
        {
            return;
        }
        
        // TODO Only do this once a day
        
        // TODO Push this into a model method:
        
        // TODO get all the published campaigns and see if the customer satisfies any of them.  
        // if so, grant the customer the benefits, but only if the customer doesn't satisfy the rules of any descendants
        // optimize the loop by only checking each campaign once (including when making the descendant check above) 
         
        // TODO Check all of the customer's current campaigns, and if they no longer match them, expire the benefits
        // TODO Track current campaigns in the user object, shop.campaigns 
        
        // TODO After running this, unset the session variable so the bootstrap file doesn't include the JS file
        // \Dsc\System::instance()->get('session')->set('shop.check_campaigns', false);
    }
}