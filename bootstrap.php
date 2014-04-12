<?php
class ShopBootstrap extends \Dsc\Bootstrap
{
    protected $dir = __DIR__;
    protected $namespace = 'Shop';

    protected function runAdmin()
    {
        parent::runAdmin();
        try
        {
            $service = \Dsc\System::instance()->get( 'massupdate' );
            if (! empty( $service ))
            {
            	$group = new \Shop\MassUpdateGroup();
            	$group->setName( $this->namespace );
                $service->registerGroup( $group );
            }
        }
        catch ( \Exception $e )
        {
        }
    }

    protected function preSite()
    {
        // add the css & js files to the minifier
        \Minify\Factory::registerPath( $this->dir . "/src/" );
        
        $files = array(
            'Shop/Assets/js/class.js',
            'Shop/Assets/js/validation.js',
            'Shop/Assets/js/site.js' 
        );
        
        foreach ( $files as $file )
        {
            \Minify\Factory::js( $file );
        }
    }
}

$app = new ShopBootstrap();