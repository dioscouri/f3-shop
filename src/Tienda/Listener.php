<?php 
namespace Tienda;

class Listener extends \Prefab 
{
    public function onSystemRebuildMenu( $event )
    {
        if ($mapper = $event->getArgument('mapper')) 
        {
            $mapper->reset();
            $mapper->priority = 20;
            $mapper->title = 'Catalog';
            $mapper->route = '';
            $mapper->icon = 'fa fa-ticket';
            $mapper->children = array(
                    json_decode(json_encode(array( 'title'=>'Products', 'route'=>'/admin/tienda/products', 'icon'=>'fa fa-list' )))
            		,json_decode(json_encode(array( 'title'=>'Add New', 'route'=>'/admin/tienda/product/create', 'icon'=>'fa fa-plus' )))
            		,json_decode(json_encode(array( 'title'=>'Categories', 'route'=>'/admin/tienda/categories', 'icon'=>'fa fa-folder' )))
            		,json_decode(json_encode(array( 'title'=>'Add New', 'route'=>'/admin/tienda/category', 'hidden'=>true )))
                    ,json_decode(json_encode(array( 'title'=>'Manufacturers', 'route'=>'/admin/tienda/manufacturers', 'icon'=>'fa fa-barcode' )))
            );
            $mapper->base = '/admin/tienda';
            $mapper->save();
            
            \Dsc\System::instance()->addMessage('Tienda added its admin menu items.');
        }
    }
    
    public function onAdminNavigationGetQuickAddItems( $event )
    {
        $items = $event->getArgument('items');
        $tree = $event->getArgument('tree');
        
        $item = new \stdClass;
        $item->title = 'Product Category';
        $item->form = \Tienda\Admin\Controllers\MenuItemQuickAdd::instance()->category($event);
        $items[] = $item;
        
        /*
        $item = new \stdClass;
        $item->title = 'Product Detail';
        $item->form = \Tienda\Admin\Controllers\MenuItemQuickAdd::instance()->product($event);
        $items[] = $item;
        */
        
        $event->setArgument('items', $items);
    }
}