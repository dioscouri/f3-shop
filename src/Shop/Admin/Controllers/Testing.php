<?php
namespace Shop\Admin\Controllers;

class Testing extends \Admin\Controllers\BaseAuth
{
    public function importCountries()
    {
        $message = null;
        // \Dsc\System::addMessage('message');
        
        $db = new \DB\SQL( 'mysql:host=localhost;port=3306;dbname=asingh', 'asingh', 'as231pm' );
        
        foreach ($db->exec('SELECT 
                country_name AS name,
                country_isocode_2 AS isocode_2,  
                country_isocode_3 AS isocode_3,
                country_enabled AS enabled,
                ordering  
                FROM dchvy_tienda_countries') as $country)
        {
            $message .= \Dsc\Debug::dump($country);
            (new \Shop\Models\Countries($country))->save();
        }
        
        // $message = 'here is a data dump';
        \Base::instance()->set( 'message', $message );
        
        $view = \Dsc\System::instance()->get( 'theme' );
        echo $view->render( 'Shop/Admin/Views::message.php' );
    }
    
    public function importRegions()
    {
        $message = null;
        // \Dsc\System::addMessage('message');
    
        $db = new \DB\SQL( 'mysql:host=localhost;port=3306;dbname=asingh', 'asingh', 'as231pm' );
    
        foreach ($db->exec('SELECT
                code,
                zone_name as name,
                country_isocode_2
                FROM dchvy_tienda_zones AS z
                LEFT JOIN dchvy_tienda_countries AS c ON z.country_id = c.country_id
                ') as $zone)
        {
            (new \Shop\Models\Regions($zone))->save();
        }
    
        // $message = 'here is a data dump';
        \Base::instance()->set( 'message', $message );
    
        $view = \Dsc\System::instance()->get( 'theme' );
        echo $view->render( 'Shop/Admin/Views::message.php' );
    }
}