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
                'Shop/Assets/js/jquery.sortable.css',
                'Shop/Assets/css/jquery.star-rating.css',
            );
            
            foreach ($files as $file)
            {
                \Minify\Factory::css($file);
            }
            
            $files = array(
                'Shop/Assets/js/jquery.sortable.min.js',
                'Shop/Assets/js/class.js',
                'Shop/Assets/js/validation.js',    
                'Shop/Assets/js/jquery.star-rating.js',
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
        
        \Shop\Models\PaymentMethods::register('\Shop\PaymentMethods\OmnipayPaypalExpress', array(
            'title'=>'Paypal Express (via Omnipay)',
            'identifier'=>'omnipay.paypal_express',            
        ));
                
        \Shop\Models\PaymentMethods::register('\Shop\PaymentMethods\OmnipayCybersource', array(
            'title'=>'Cybersource (via Omnipay)',
            'identifier'=>'omnipay.cybersource',
        ));

        \Shop\Models\PaymentMethods::register('\Shop\PaymentMethods\CCAvenue', array(
            'title'=>'CCAvenue',
            'identifier'=>'ccavenue',
        ));
        
        static::diagnostics();
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
                'Shop/Assets/js/jquery.popupoverlay.js',
                'Shop/Assets/js/jquery.scrollTo.js',
                'Shop/Assets/js/jquery.payment.js',
                'Shop/Assets/js/jquery.star-rating.js',
            );
            
            if ($check_campaigns = \Dsc\System::instance()->get('session')->get('shop.check_campaigns'))
            {
                $files[] = 'Shop/Assets/js/check_campaigns.js';
            }
            
            foreach ($files as $file)
            {
                \Minify\Factory::js($file);
            }

            $files = array(
                'Shop/Assets/css/jquery.star-rating.css',
            );
            
            foreach ($files as $file)
            {
                \Minify\Factory::css($file);
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
        
        // symlink to the public folder if necessary
        if (!is_dir($this->app->get('PATH_ROOT') . 'public/ShopAssets'))
        {
            $public_assets = $this->app->get('PATH_ROOT') . 'public/ShopAssets';
            $app_assets = realpath(__dir__ . '/src/Shop/Assets');
            $res = symlink($app_assets, $public_assets);
        }
        
        static::diagnostics();
    }
    
    public static function diagnostics()
    {
        if (class_exists('\Modules\Factory')) 
        {
            \Modules\Models\Conditions::register('\Shop\ModuleConditions\Orders', array(
                'title'=>'Orders',
                'icon'=>'fa fa-shopping-cart',
                'type'=>'shop',
                'slug'=>'shop-orders',
            ));
        }
                
        $settings = \Shop\Models\Settings::fetch();
        
        // TODO When did we last pull down the currency list?
        if (empty($settings->currencies_last_refreshed) || $settings->currencies_last_refreshed < time() - 24*60) 
        {
            /*
            \Dsc\Queue::task('\Shop\Models\Currencies::refresh', array(), array(
                'title' => 'Refresh currencies from OpenExchangeRates.org'
            ));
            */
        }
        
        // TODO When did we last pull down the exchange rates?
        if (empty($settings->exchangerates_last_refreshed) || $settings->exchangerates_last_refreshed < time() - 24*60)
        {
        
        }        
    }
}

$app = new ShopBootstrap();