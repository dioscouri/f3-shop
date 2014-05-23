<?php
namespace Shop\Admin;

class Listener extends \Prefab
{

    public function onSystemRebuildMenu($event)
    {
        if ($model = $event->getArgument('model'))
        {
            $root = $event->getArgument('root');
            $shop = clone $model;
            
            $shop->insert(array(
                'type' => 'admin.nav',
                'priority' => 20,
                'title' => 'Shop',
                'icon' => 'fa fa-ticket',
                'is_root' => false,
                'tree' => $root,
                'base' => '/admin/shop/'
            ));
            
            $children = array(
                array(
                    'title' => 'Catalog',
                    'route' => 'javascript:void(0);',
                    'icon' => 'fa fa-cubes'
                ),
                array(
                    'title' => 'Orders',
                    'route' => 'javascript:void(0);',
                    'icon' => 'fa fa-inbox'
                ),
                array(
                    'title' => 'Coupons',
                    'route' => './admin/shop/coupons',
                    'icon' => 'fa fa-barcode'
                ),
                array(
                    'title' => 'Media Assets',
                    'route' => './admin/assets?filter[type]=shop.assets',
                    'icon' => 'fa fa-list'
                ),
                array(
                    'title' => 'Localization',
                    'route' => 'javascript:void(0);',
                    'icon' => 'fa fa-flag-o'
                ),                
                array(
                    'title' => 'Configuration',
                    'route' => 'javascript:void(0);',
                    'icon' => 'fa fa-cogs'
                ),                
            );
            $shop->addChildren($children, $root);
            
            // Find the Catalog Menu Item
            $catalog_item = (new \Admin\Models\Nav\Primary())->load(array(
                'type' => 'admin.nav',
                'parent' => $shop->id,
                'title' => 'Catalog'
            ));
            
            // add its children
            if (!empty($catalog_item->id))
            {
                $catalog_children = array(
                    array(
                        'title' => 'Products',
                        'route' => './admin/shop/products',
                        'icon' => 'fa fa-list'
                    ),
                    array(
                        'title' => 'Collections',
                        'route' => './admin/shop/collections',
                        'icon' => 'fa fa-hdd-o'
                    ),
                    array(
                        'title' => 'Categories',
                        'route' => './admin/shop/categories',
                        'icon' => 'fa fa-folder'
                    ),
                    array(
                        'title' => 'Tags',
                        'route' => './admin/shop/tags',
                        'icon' => 'fa fa-tag'
                    ),
                    array(
                        'title' => 'Manufacturers',
                        'route' => './admin/shop/manufacturers',
                        'icon' => 'fa fa-barcode'
                    ),
                    array(
                        'title' => 'Gift Cards',
                        'route' => './admin/shop/giftcards',
                        'icon' => 'fa fa-gift'
                    ),                    
                );
            
                $catalog_item->addChildren($catalog_children);
            }
            
            // Find the Orders Item
            $orders_item = (new \Admin\Models\Nav\Primary())->load(array(
                'type' => 'admin.nav',
                'parent' => $shop->id,
                'title' => 'Orders'
            ));
            
            // add its children
            if (!empty($orders_item->id))
            {
                $orders_children = array(
                    array(
                        'title' => 'Manage',
                        'route' => './admin/shop/orders',
                        'icon' => 'fa fa-money'
                    ),
                    array(
                        'title' => 'Gift Cards',
                        'route' => './admin/shop/orders/giftcards',
                        'icon' => 'fa fa-gift'
                    ),                    
                );
                
                $orders_item->addChildren($orders_children);
            }
            
            
            // Find the Localization Menu Item 
            $locale_item = (new \Admin\Models\Nav\Primary())->load(array(
                'type' => 'admin.nav',
                'parent' => $shop->id,
                'title' => 'Localization'
            ));
            
            // add its children
            if (!empty($locale_item->id))
            {
                $locale_children = array(
                    array(
                        'title' => 'Countries',
                        'route' => './admin/shop/countries',
                        'icon' => 'fa fa-list'
                    ),
                    array(
                        'title' => 'Regions',
                        'route' => './admin/shop/regions',
                        'icon' => 'fa fa-list'
                    ),
                );
            
                $locale_item->addChildren($locale_children);
            }
                        
            // Find the Shop's Configuration menu item
            $settings_item = (new \Admin\Models\Nav\Primary())->load(array(
                'type' => 'admin.nav',
                'parent' => $shop->id,
                'title' => 'Configuration'
            ));
            
            // add its children
            if (!empty($settings_item->id))
            {
                $settings_children = array(
                    array(
                        'title' => 'Settings',
                        'route' => './admin/shop/settings',
                        'icon' => 'fa fa-cogs'
                    ),
                    /*                    
                    array(
                        'title' => 'Shipping',
                        'route' => '/admin/shop/shipping-methods',
                        'icon' => 'fa fa-truck'
                    )
                    */
                );
                
                $settings_item->addChildren($settings_children);
            }
            
            \Dsc\System::instance()->addMessage('Shop added its admin menu items.');
        }
    }

    public function onAdminNavigationGetQuickAddItems($event)
    {
        $items = $event->getArgument('items');
        $tree = $event->getArgument('tree');
        
        $item = new \stdClass();
        $item->title = 'Product Collection';
        $item->form = \Shop\Admin\Controllers\MenuItemQuickAdd::instance()->collection($event);
        $items[] = $item;
        
        $item = new \stdClass();
        $item->title = 'Product Category';
        $item->form = \Shop\Admin\Controllers\MenuItemQuickAdd::instance()->category($event);
        $items[] = $item;
        
        $item = new \stdClass();
        $item->title = 'Shopping Cart';
        $item->form = \Shop\Admin\Controllers\MenuItemQuickAdd::instance()->cart($event);
        $items[] = $item;
        
        $event->setArgument('items', $items);
    }

    /**
     * Adds a Shop tab to the Users\Groups editing form
     */
    public function onDisplayAdminGroupEdit($event)
    {
        $item = $event->getArgument('item');
        $tabs = $event->getArgument('tabs');
        $content = $event->getArgument('content');
        $isNew = $event->getArgument('isNew');
        $identifier = $event->getArgument('identifier');
        
        if (empty($tabs))
        {
            $tabs = array();
        }
        
        if (empty($content))
        {
            $content = array();
        }
        
        $view = \Dsc\System::instance()->get('theme');
        $view->item = $item;
        $prefab = new \Shop\Models\Prefabs\UserGroupDetail;
        if (!isset($view->item['shop']))
        {
            $view->item = $prefab->cast();
        }
        else
        {
            $view->item = array_merge($prefab->cast(), $view->item->cast());
        }
        $shop_content = $view->renderLayout('Shop/Admin/Views::groups/tab_usergroups.php');
        
        $tabs['shop'] = 'Shop Details';
        $content['shop'] = $shop_content;
        
        $event->setArgument('tabs', $tabs);
        $event->setArgument('content', $content);
    }
    
    /**
     * Adds a Shop tab to the Pages\Page editing form
     */
    public function onDisplayPagesEdit($event)
    {
        $item = $event->getArgument('item');
        $tabs = $event->getArgument('tabs');
        $content = $event->getArgument('content');

        $view = \Dsc\System::instance()->get('theme');
        $shop_content = $view->renderLayout('Shop/Admin/Views::listeners/fields_related_products.php');
        
        $tabs['shop'] = 'Shop';
        $content['shop'] = $shop_content;
        
        $event->setArgument('tabs', $tabs);
        $event->setArgument('content', $content);
    }
    
    public function beforeSavePagesModelsPages( $event ) 
    {
        $model = $event->getArgument('model');
        
        if (!empty($model->__products)) 
        {
        	if (! is_array( $model->__products ))
        	{
        	    $model->__products = trim( $model->__products );
        	    if (! empty( $model->__products ))
        	    {
        	        $model->__products = \Base::instance()->split( (string) $model->__products );
        	    }
        	}
        	
        	if (! empty( $model->__products ) && is_array( $model->__products ))
        	{
        	    // convert the array of product ids into an array of MongoIds
        	    $model->{'shop.products'} = array_map( function ( $input )
        	    {
        	        return new \MongoId( $input );
        	    }, $model->__products );
        	}
        }
        
        $event->setArgument('model', $model);
    }
}