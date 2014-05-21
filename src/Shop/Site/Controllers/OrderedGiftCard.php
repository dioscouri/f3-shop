<?php 
namespace Shop\Site\Controllers;

class OrderedGiftCard extends \Dsc\Controller 
{
    /**
     * Display a single ordered gift card
     */
    public function read()
    {
        $f3 = \Base::instance();
        $id = $this->inputfilter->clean( $f3->get('PARAMS.id'), 'alnum' );
        $token = $this->inputfilter->clean( $f3->get('PARAMS.token'), 'alnum' );
    	
    	try {
    		$item = (new \Shop\Models\OrderedGiftCards)->setState('filter.id', $id)->setState('filter.token', $token)->getItem();
    		if (empty($item->id)) {
    			throw new \Exception;
    		}

    	} catch ( \Exception $e ) 
    	{
    		\Dsc\System::instance()->addMessage( "Invalid Gift Card", 'error');
    		$f3->reroute( '/shop' );
    		return;
    	}
    	
    	\Base::instance()->set('giftcard', $item );
    	
    	$this->app->set('meta.title', 'Gift Card');
    	
    	$view = \Dsc\System::instance()->get('theme');
    	echo $view->render('Shop/Site/Views::orderedgiftcard/detail.php');
    }
}