<?php 
namespace Tienda;

class Listener extends \Prefab 
{
    public function onSystemRebuildMenu( $event )
    {
        if ($mapper = $event->getArgument('mapper')) 
        {
            $mapper->reset();
            $mapper->title = 'Catalog';
            $mapper->route = '';
            $mapper->icon = 'fa fa-ticket';
            $mapper->children = array(
                    json_decode(json_encode(array( 'title'=>'Products', 'route'=>'/admin/tienda/products', 'icon'=>'fa fa-list' )))
            );
            $mapper->save();
            
            \Dsc\System::instance()->addMessage('Tienda added its admin menu items.');
        }
        
    }
}