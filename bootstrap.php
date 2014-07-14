<?php

class ShopBootstrap extends \Dsc\Bootstrap
{

    protected $dir = __DIR__;

    protected $namespace = 'Shop';

    protected function preAdmin()
    {
        parent::preAdmin();
        
        \Dsc\Apps::registerPath($this->dir . "/src/Shop/MassUpdate", 'massupdate');
        
        if (class_exists('\Minify\Factory'))
        {
            \Minify\Factory::registerPath($this->dir . "/src/");
            
            $files = array(
                'Shop/Assets/js/jquery.sortable.css'
            );
            
            foreach ($files as $file)
            {
                \Minify\Factory::css($file);
            }
            
            $files = array(
                'Shop/Assets/js/jquery.sortable.min.js'
            );
            
            foreach ($files as $file)
            {
                \Minify\Factory::js($file);
            }
        }
        
        \Shop\Models\Reports::register('\Shop\Reports\CustomersExpiredCarts', array(
            'title'=>'Expired Carts',
            'icon'=>'fa fa-shopping-cart',
            'type'=>'customers',
            'slug'=>'customers-expired-carts',
        ));
        
        \Shop\Models\Reports::register('\Shop\Reports\OrdersByCouponCode', array(
            'title'=>'Orders - by Coupon Code',
            'icon'=>'fa fa-inbox',
            'type'=>'orders',
            'slug'=>'orders-coupon-code',
        ));        
        
        $path = $this->app->hive()['PATH'];
        if (strpos($path, '/admin/shop/reports') !== false)
        {
            // Bootstrap the reports
            \Shop\Models\Reports::bootstrap();
        }

        if (class_exists('\Search\Factory'))
        {
            \Search\Factory::registerSource(new \Search\Models\Source(array(
                'id' => 'shop.products',
                'title' => 'Products',
                'class' => '\Shop\Models\Products',
                'priority' => 20,
            )));
            
            \Search\Factory::registerSource(new \Search\Models\Source(array(
                'id' => 'shop.orders',
                'title' => 'Orders',
                'class' => '\Shop\Models\Orders',
                'priority' => 20,
            )));
            
        }
    }

    protected function preSite()
    {
        if (class_exists('\Search\Factory'))
        {
            \Search\Factory::registerSource(new \Search\Models\Source(array(
                'id' => 'shop.products',
                'title' => 'Products',
                'class' => '\Shop\Models\Products'
            )));
            
        }        
        
        if (class_exists('\Minify\Factory'))
        {
            \Minify\Factory::registerPath($this->dir . "/src/");
            
            $files = array(
                'Shop/Assets/js/class.js',
                'Shop/Assets/js/validation.js',
                'Shop/Assets/js/site.js',
                'Shop/Assets/js/jquery.popupoverlay.js'
            );
            
            if ($check_campaigns = \Dsc\System::instance()->get('session')->get('shop.check_campaigns'))
            {
                $files[] = 'Shop/Assets/js/check_campaigns.js';
            }
            
            foreach ($files as $file)
            {
                \Minify\Factory::js($file);
            }            
        }
        
        $app = \Base::instance();
        $request_kmi = \Dsc\System::instance()->get('input')->get('kmi', null, 'string');
        $cookie_kmi = $app->get('COOKIE.kmi');
        if (!empty($request_kmi)) 
        {
            if ($cookie_kmi != $request_kmi) 
            {
                $app->set('COOKIE.kmi', $request_kmi);
            }
            
            $cart = \Shop\Models\Carts::fetch();
            if (empty($cart->user_email)) 
            {
                $cart->user_email = $request_kmi;
                $cart->store();
            }
        }
    }
}

$app = new ShopBootstrap();