<?php
namespace Shop\Models;

class Customer extends \Users\Models\Users
{
    /**
     * Gets the customer's highest-ordered Users\Group
     * to be used for determining pricing and primary group status
     * 
     * @return \Users\Models\Groups
     */
    public static function primaryGroup( \Users\Models\Users $user )
    {
        $group = new \Users\Models\Groups;
        
        // Set this to be a default group, as configured in the Shop config        
        $group_id = \Shop\Models\Settings::fetch()->{'users.default_group'};
        if (!empty($group_id)) {
            $group->setState('filter.id', (string) $group_id)->getItem();
        }
        
        if ($groups = $user->groups()) 
        {
        	$group = $groups[0];
        }
        
        return $group;
    }
}