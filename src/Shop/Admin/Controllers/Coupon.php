<?php 
namespace Shop\Admin\Controllers;

class Coupon extends \Admin\Controllers\BaseAuth 
{
    use \Dsc\Traits\Controllers\CrudItemCollection;

    protected $list_route = '/admin/shop/coupons';
    protected $create_item_route = '/admin/shop/coupon/create';
    protected $get_item_route = '/admin/shop/coupon/read/{id}';    
    protected $edit_item_route = '/admin/shop/coupon/edit/{id}';
    
    protected function getModel() 
    {
        $model = new \Shop\Models\Coupons;
        return $model; 
    }
    
    protected function getItem() 
    {
        $f3 = \Base::instance();
        $id = $this->inputfilter->clean( $f3->get('PARAMS.id'), 'alnum' );
        $model = $this->getModel()
            ->setState('filter.id', $id);

        try {
            $item = $model->getItem();
        } catch ( \Exception $e ) {
            \Dsc\System::instance()->addMessage( "Invalid Item: " . $e->getMessage(), 'error');
            $f3->reroute( $this->list_route );
            return;
        }

        return $item;
    }
    
    protected function displayCreate() 
    {
        $f3 = \Base::instance();

        $model = new \Shop\Models\Coupons;
        
        $this->app->set('meta.title', 'Create Coupon | Shop');
        
        $view = \Dsc\System::instance()->get('theme');
        $view->event = $view->trigger( 'onDisplayShopCouponsEdit', array( 'item' => $this->getItem(), 'tabs' => array(), 'content' => array() ) );
        echo $view->render('Shop/Admin/Views::coupons/create.php');        
    }
    
    protected function displayEdit()
    {
        $model = new \Shop\Models\Coupons;
        
        $flash = \Dsc\Flash::instance();

        $this->app->set('meta.title', 'Edit Coupon | Shop');
        
        $view = \Dsc\System::instance()->get('theme');
        $view->event = $view->trigger( 'onDisplayShopCouponsEdit', array( 'item' => $this->getItem(), 'tabs' => array(), 'content' => array() ) );        
        echo $view->render('Shop/Admin/Views::coupons/edit.php');
    }
    
    /**
     * This controller doesn't allow reading, only editing, so redirect to the edit method
     */
    protected function doRead(array $data, $key=null) 
    {
        $f3 = \Base::instance();
        $id = $this->getItem()->get( $this->getItemKey() );
        $route = str_replace('{id}', $id, $this->edit_item_route );
        $f3->reroute( $route );
    }
    
    
    public function displayCodes(){
		$item = $this->getItem();
		$model = $this->getModel()->populateState();
		$view = \Dsc\System::instance()->get('theme');
		
		$offset = $model->getState('list.offset', 0, 'int');
    	if( $offset < 0 ){
			$offset = 0;
		}
		$model->setState('list.offset', $offset );
		
		$size = $model->getState('list.limit', 50, 'int');
    	if( empty( $size ) ){
			$size = 50;
		}
		$model->setState('list.limit', $size );
		
		$total = count( (array)$item->{'codes.list'});

		$pagination = new \Dsc\Pagination( $total, $size );
		$origin_codes = array_values( (array)$item->{'codes.list'} );
		$codes = array_slice( $origin_codes, $offset * $size, $size );
		
		$this->app->set( 'item', $item );
		$this->app->set( 'codes', $codes );
		$this->app->set( 'paginated', $pagination );
		$this->app->set( 'state', $model->getState() );
		
        $this->app->set('meta.title', 'Coupon Codes | Shop');
		echo $view->render('Shop/Admin/Views::coupons/codes.php');
    }
    
    public function generateCodes(){
    	$id = $this->app->get("PARAMS.id" );
    	if( empty( $id ) ){
    		\Dsc\System::addMessage( 'Missing coupon ID', 'error' );
    		$this->app->redirect( '/admin/shop/coupons' );
    		return;
    	}
    	
    	$prefix = $this->input->get( 'prefix', '', 'cmd' );
    	$len = $this->input->get( 'length', null, 'int' );
    	$num = $this->input->get( 'count', null, 'int' );
    	
    	if( empty( $len ) ) {
    		if( empty( $num ) ){
    		\Dsc\System::addMessage( 'Missing length of codes and number of codes to generate.', 'error' );
    		} else {
    			\Dsc\System::addMessage( 'Missing length of codes.', 'error' );
    		}
    		$this->app->reroute('/admin/shop/coupon/'.$id.'/codes');
    		return;
    	} elseif( empty( $num ) ) {
    		\Dsc\System::addMessage( 'Missing number of codes to generate.', 'error' );
    		$this->app->reroute('/admin/shop/coupon/'.$id.'/codes');
    		return;
    	}
    	
    	try{
    		$item = $this->getItem();
    		$item->generateCodes( $prefix, $len, $num );
    		\Dsc\System::addMessage( 'Codes were successfuly generated.' );
    	} catch(\Exception $e){
	    	\Dsc\System::addMessage( $e->getMessage(), 'error' );	
    	}
    	$this->app->reroute('/admin/shop/coupon/'.$id.'/codes');
    }
    
    public function deleteCode(){
    	$id = $this->inputfilter->clean( $this->app->get("PARAMS.id" ), 'ALNUM' );
    	$code = $this->inputfilter->clean( $this->app->get('PARAMS.code'), 'CMD' );
    	
    	if( empty( $id ) || empty( $code ) ){
    		\Dsc\System::addMessage( 'Missing coupon ID', 'error' );
    		$this->app->reroute( '/admin/shop/coupons' );
    		return;
    	}
    	 
    	try{
    		\Shop\Models\Coupons::collection()->update( 
    								array( '_id' => new \MongoId( (string)$id ),
    								),
    								array( '$pull' => array( 'codes.list' => array( 'code' => $code ) ) ), array("upset" => true));
    		\Dsc\System::addMessage( 'Codes was successfuly deleted.' );
    	} catch(\Exception $e){
    		\Dsc\System::addMessage( $e->getMessage(), 'error' );
    	}
    	$this->app->reroute('/admin/shop/coupon/'.$id.'/codes');
    }
    
    
    public function downloadCodes(){
    
    }
    
    protected function displayRead() {}
}