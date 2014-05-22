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
    	
    	if ($f3->get('print')) {
    	    $this->app->set('meta.title', 'Print | Gift Card');
    	    $view = \Dsc\System::instance()->get('theme');
    	    echo $view->renderView('Shop/Site/Views::orderedgiftcard/print.php');
    	    return;
    	}    	
    	
    	$this->app->set('meta.title', 'Gift Card');
    	
    	$view = \Dsc\System::instance()->get('theme');
    	echo $view->render('Shop/Site/Views::orderedgiftcard/detail.php');
    }
    
    public function email()
    {
        $f3 = \Base::instance();

        $id = $this->inputfilter->clean( $f3->get('PARAMS.id'), 'alnum' );
        $token = $this->inputfilter->clean( $f3->get('PARAMS.token'), 'alnum' );
                
        $data = array(
            'sender_name' => $this->input->get( 'sender_name', null, 'string' ),
            'sender_email' => $this->input->get( 'sender_email', null, 'string' ),
            'recipient_name' => $this->input->get( 'recipient_name', null, 'string' ),
            'recipient_email' => $this->input->get( 'recipient_email', null, 'string' ),
            'message' => $this->input->get( 'message', null, 'string' ),
        );
                
        // TODO Validate the input from the form, require at least emails and names
        if (empty($data['sender_name'])
            || empty($data['sender_email'])
            || empty($data['recipient_name'])
            || empty($data['recipient_email'])
            )
        {
            \Dsc\System::instance()->addMessage( "Please complete all required fields.  All name and email fields are required.", 'error');
            $f3->reroute( '/shop/giftcard/' . $id . '/' . $token );
            return;        	
        }
         
        try {
            $item = (new \Shop\Models\OrderedGiftCards)->setState('filter.id', $id)->setState('filter.token', $token)->getItem();
            if (empty($item->id)) {
                throw new \Exception;
            }
        
        } 
        catch ( \Exception $e )
        {
            \Dsc\System::instance()->addMessage( "Invalid Gift Card", 'error');
            $f3->reroute( '/shop' );
            return;
        }
        
        // use the model to send the email so the model can add the history
        try {
            $item->sendEmailShareGiftCard( $data );
        }
        catch ( \Exception $e )
        {
            \Dsc\System::instance()->addMessage( "Error sending email.", 'error');
            $f3->reroute( '/shop/giftcard/' . $id . '/' . $token );
            return;
        }
        
        \Dsc\System::instance()->addMessage( "Gift card has been sent to " . $data['recipient_email'] );
        
        $f3->reroute( '/shop' );
    }
}