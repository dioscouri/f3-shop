<?php 
namespace Shop\Admin\Controllers;

class GiftCard extends \Shop\Admin\Controllers\Product 
{
    protected $list_route = '/admin/shop/giftcards';
    protected $create_item_route = '/admin/shop/giftcard/create';
    protected $get_item_route = '/admin/shop/giftcard/read/{id}';    
    protected $edit_item_route = '/admin/shop/giftcard/edit/{id}';
    
    protected function getModel() 
    {
        $model = new \Shop\Models\GiftCards;
        return $model; 
    }
    
    protected function displayCreate() 
    {
        $f3 = \Base::instance();
        
        $model = new \Shop\Models\Categories;
        $categories = $model->getList();
        \Base::instance()->set('categories', $categories );
        \Base::instance()->set('selected', 'null' );

        $item = $this->getItem();
        
        $selected = array();
        $flash = \Dsc\Flash::instance();

        $use_flash = \Dsc\System::instance()->getUserState('use_flash.' . $this->create_item_route);
        if (!$use_flash) {
            // this is a brand-new create, so store the prefab data
            $flash->store( $item->cast() );
        }        
        
        $input = $flash->old('category_ids');

        if (!empty($input)) 
        {
            foreach ($input as $id)
            {
                $id = $this->inputfilter->clean( $id, 'alnum' );
                $selected[] = array('id' => $id);
            }
        }
        
        $flash->store( $flash->get('old') + array('categories'=>$selected));        

        $all_tags = $this->getModel()->getTags();
        \Base::instance()->set('all_tags', $all_tags );
        
        $this->app->set('meta.title', 'Create Gift Card | Shop');
        
        $view = \Dsc\System::instance()->get('theme');
        $view->event = $view->trigger( 'onDisplayShopProductsEdit', array( 'item' => $item, 'tabs' => array(), 'content' => array() ) );
        
        echo $view->render('Shop\Admin\Views::giftcards/create.php');
    }
    
    protected function displayEdit()
    {   
        
        $item = $this->getItem();
    	if(empty($item) || $item->product_type != 'giftcards') {
    		\Dsc\System::addMessage('Item is not a giftcard', 'error');
    		$this->app->reroute('/admin/shop/giftcards');
    	}
        
        
        $f3 = \Base::instance();

        $flash = \Dsc\Flash::instance();
        $variants = array();
        if ($flashed_variants = $flash->old('variants')) {
        	foreach ($flashed_variants as $variant) 
        	{
        	    $key = implode("-", (array) $variant['attributes']);
        	    if (empty($key)) {
        	        $key = $variant['id'];
        	    }
        		$variants[$key] = $variant;
        	}
        }
        $old = array_merge( $flash->get('old'), array( 'variants' => $variants ) );
        $flash->store( $old );
        
        $model = new \Shop\Models\Categories;
        $categories = $model->getList();
        \Base::instance()->set('categories', $categories );
        \Base::instance()->set('selected', 'null' );
        
        $all_tags = $this->getModel()->getTags();
        \Base::instance()->set('all_tags', $all_tags );
        
        $this->app->set('meta.title', 'Edit Gift Card | Shop');
        
        $view = \Dsc\System::instance()->get('theme');
        $view->event = $view->trigger( 'onDisplayShopProductsEdit', array( 'item' => $this->getItem(), 'tabs' => array(), 'content' => array() ) );
        
        echo $view->render('Shop\Admin\Views::giftcards/edit.php');
    }
}
