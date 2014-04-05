<?php 
namespace Shop\Site\Controllers;

class Order extends \Dsc\Controller 
{    
    public function read()
    {
    	$f3 = \Base::instance();
    	$id = $this->inputfilter->clean( $f3->get('PARAMS.id'), 'alnum' );
    	
    	try {
    		$item = (new \Shop\Models\Orders)->setState('filter.id', $id)->getItem();
    	} catch ( \Exception $e ) {
    	    // TODO Change to a normal 404 error
    		\Dsc\System::instance()->addMessage( "Invalid Order: " . $e->getMessage(), 'error');
    		$f3->reroute( '/' );
    		return;
    	}
    	
    	\Base::instance()->set('item', $item );
    	
    	if ($f3->get('print')) {
    	    $view = \Dsc\System::instance()->get('theme');
    	    echo $view->renderView('Shop/Site/Views::order/print.php');
    	    return;    		
    	}
    	
    	$view = \Dsc\System::instance()->get('theme');
    	echo $view->render('Shop/Site/Views::order/detail.php');
    }
}