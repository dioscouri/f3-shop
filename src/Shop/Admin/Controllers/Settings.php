<?php 
namespace Shop\Admin\Controllers;

class Settings extends \Admin\Controllers\BaseAuth 
{
	use \Dsc\Traits\Controllers\Settings;
	
	protected $layout_link = 'Shop/Admin/Views::settings/default.php';
	protected $settings_route = '/admin/shop/settings';
    
    protected function getModel()
    {
        $model = new \Shop\Models\Settings;
        return $model;
    }
}