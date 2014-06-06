<?php

class ShopBootstrap extends \Dsc\Bootstrap
{

    protected $dir = __DIR__;

    protected $namespace = 'Shop';

    protected function preAdmin()
    {
        parent::preAdmin();
        
        \Dsc\Apps::registerPath($this->dir . "/src/Shop/MassUpdate", 'massupdate');
        
        // add the css & js files to the minifier
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
        
        \Shop\Models\Reports::register('\Shop\Reports\CustomersExpiredCarts', array(
            'name'=>'Expired Carts',
            'icon'=>'fa fa-shopping-cart',
            'type'=>'customers',
            'slug'=>'customers-expired-carts',
        ));
    }

    protected function preSite()
    {
        \Search\Factory::registerSource(new \Search\Models\Source(array(
            'id' => 'shop.products',
            'title' => 'Products',
            'class' => '\Shop\Models\Products'
        )));
        
        // add the css & js files to the minifier
        \Minify\Factory::registerPath($this->dir . "/src/");
        
        $files = array(
            'Shop/Assets/js/class.js',
            'Shop/Assets/js/validation.js',
            'Shop/Assets/js/site.js'
        );
        
        foreach ($files as $file)
        {
            \Minify\Factory::js($file);
        }
    }
}

$app = new ShopBootstrap();