<?php 
namespace Shop;

class Listener extends \Prefab 
{
    public function onSystemRebuildMenu( $event )
    {
        if ($mapper = $event->getArgument('mapper')) 
        {
            $mapper->reset();
            $mapper->priority = 20;
            $mapper->title = 'Shop';
            $mapper->route = '';
            $mapper->icon = 'fa fa-ticket';
            $mapper->children = array(
                    json_decode(json_encode(array( 'title'=>'Products', 'route'=>'/admin/shop/products', 'icon'=>'fa fa-list' )))
            		,json_decode(json_encode(array( 'title'=>'Add New', 'route'=>'/admin/shop/product/create', 'icon'=>'fa fa-plus' )))
            		,json_decode(json_encode(array( 'title'=>'Categories', 'route'=>'/admin/shop/categories', 'icon'=>'fa fa-folder' )))
            		,json_decode(json_encode(array( 'title'=>'Add New', 'route'=>'/admin/shop/category', 'hidden'=>true )))
                    ,json_decode(json_encode(array( 'title'=>'Manufacturers', 'route'=>'/admin/shop/manufacturers', 'icon'=>'fa fa-barcode' )))
                    ,json_decode(json_encode(array( 'title'=>'Media Assets', 'route'=>'/admin/shop/assets', 'icon'=>'fa fa-list' )))
            );
            $mapper->base = '/admin/shop';
            $mapper->save();
            
            \Dsc\System::instance()->addMessage('Shop added its admin menu items.');
        }
    }
    
    public function onAdminNavigationGetQuickAddItems( $event )
    {
        $items = $event->getArgument('items');
        $tree = $event->getArgument('tree');
        
        $item = new \stdClass;
        $item->title = 'Product Category';
        $item->form = \Shop\Admin\Controllers\MenuItemQuickAdd::instance()->category($event);
        $items[] = $item;
        
        /*
        $item = new \stdClass;
        $item->title = 'Product Detail';
        $item->form = \Shop\Admin\Controllers\MenuItemQuickAdd::instance()->product($event);
        $items[] = $item;
        */
        
        $event->setArgument('items', $items);
    }
}