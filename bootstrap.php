<<<<<<< HEAD
<?php
class ShopBootstrap extends \Dsc\BaseBootstrap
{
    protected $dir = __DIR__;
    protected $namespace = 'Shop';
=======
<?php 
class ShopBootstrap extends \Dsc\Bootstrap{
	protected $dir = __DIR__;
	protected $namespace = 'Shop';
>>>>>>> 2a7664e488a270437128579631b82859632a9607

    protected function runAdmin()
    {
        parent::runAdmin();
        try
        {
            $service = \Dsc\System::instance()->get( 'massupdate' );
            if (! empty( $service ))
            {
                $service->registerGroup( new \Shop\MassUpdateGroup() );
            }
        }
        catch ( \Exception $e )
        {
            
        }
    }
    
    protected function preSite()
    {
        // add the css & js files to the minifier
        \Minify\Factory::registerPath( $this->dir . "/src/");
        
        $files = array(
            'Shop/Assets/js/site.js'
        );
        
        foreach ($files as $file)
        {
            \Minify\Factory::js($file);
        }        
    }
}

$app = new ShopBootstrap();