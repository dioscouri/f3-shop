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
		$view = \Dsc\System::instance()->get('theme');
		$this->app->set( 'item', $item );
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
    	
    	$prefix = 'bro-';
    	$len = 10;
    	$num = 50;
    	
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
    	$id = $this->app->get("PARAMS.id" );
    	$code = $this->app->get('PARAMS.code');
    	
    	if( empty( $id ) || empty( $code ) ){
    		\Dsc\System::addMessage( 'Missing coupon ID', 'error' );
    		$this->app->redirect( '/admin/shop/coupons' );
    		return;
    	}
    	 
    	try{
    		\Shop\Models\Coupons::collection()->update( 
    								array( '_id' => new \MongoId( $id ),
    										'codes.list.code' => $code
    								),
    								array( '$pull' => 'codes.list.$'));
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