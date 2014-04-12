<?php 
namespace Shop\Admin;

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
                     json_decode(json_encode(array( 'title'=>'Orders', 'route'=>'/admin/shop/orders', 'icon'=>'fa fa-money' )))
                    ,json_decode(json_encode(array( 'title'=>'Products', 'route'=>'/admin/shop/products', 'icon'=>'fa fa-list' )))
                    ,json_decode(json_encode(array( 'title'=>'Collections', 'route'=>'/admin/shop/collections', 'icon'=>'fa fa-hdd' )))
            		,json_decode(json_encode(array( 'title'=>'Categories', 'route'=>'/admin/shop/categories', 'icon'=>'fa fa-folder' )))
                    ,json_decode(json_encode(array( 'title'=>'Coupons', 'route'=>'/admin/shop/coupons', 'icon'=>'fa fa-barcode' )))
                    ,json_decode(json_encode(array( 'title'=>'Manufacturers', 'route'=>'/admin/shop/manufacturers', 'icon'=>'fa fa-barcode' )))
                    ,json_decode(json_encode(array( 'title'=>'Media Assets', 'route'=>'/admin/shop/assets', 'icon'=>'fa fa-list' )))
                    ,json_decode(json_encode(array( 'title'=>'Countries', 'route'=>'/admin/shop/countries', 'icon'=>'fa fa-list' )))
                    ,json_decode(json_encode(array( 'title'=>'Regions', 'route'=>'/admin/shop/regions', 'icon'=>'fa fa-list' )))
                    ,json_decode(json_encode(array( 'title'=>'Settings', 'route'=>'/admin/shop/settings', 'icon'=>'fa fa-cogs' )))
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
        $item->title = 'Product Collection';
        $item->form = \Shop\Admin\Controllers\MenuItemQuickAdd::instance()->collection($event);
        $items[] = $item;
        
        $item = new \stdClass;
        $item->title = 'Product Category';
        $item->form = \Shop\Admin\Controllers\MenuItemQuickAdd::instance()->category($event);
        $items[] = $item;
        
        $item = new \stdClass;
        $item->title = 'Shopping Cart';
        $item->form = \Shop\Admin\Controllers\MenuItemQuickAdd::instance()->cart($event);
        $items[] = $item;
        
        $event->setArgument('items', $items);
    }
	
    /**
     * Adds a Shop tab to the Users\Groups editing form
     */
	public function onDisplayAdminGroupEdit( $event ) 
	{
        $item = $event->getArgument('item');
        $tabs = $event->getArgument('tabs');
        $content = $event->getArgument('content');
        $isNew = $event->getArgument( 'isNew' );
		$identifier = $event->getArgument( 'identifier' );
		
		if( empty( $tabs ) ) {
			$tabs = array();
		}
		
		if( empty( $content ) ) {
			$content = array();
		}

		$view = \Dsc\System::instance()->get( 'theme' );
		$view->item = $item;
		$prefab = (new \Shop\Models\Prefabs\UserGroupDetail);
		if (!isset( $view->item['shop'] ))
		{
		    $view->item = $prefab->cast();
		}
		else
		{
		    $view->item = array_merge( $prefab->cast(), $view->item->cast() );
		}
		$shop_content = $view->renderLayout( 'Shop/Admin/Views::groups/tab_usergroups.php' );		
		
		$tabs['shop'] = 'Shop Details';
		$content['shop'] = $shop_content;

        $event->setArgument('tabs', $tabs);
        $event->setArgument('content', $content);
	}
}