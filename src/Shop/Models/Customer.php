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
    public function getGroup()
    {
        $group = new \Users\Models\Groups; 
        
        return $group;
    }
}