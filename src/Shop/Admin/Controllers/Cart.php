<?php 
namespace Shop\Admin\Controllers;

class Cart extends \Admin\Controllers\BaseAuth 
{
    use \Dsc\Traits\Controllers\CrudItemCollection;

    protected $list_route = '/admin/shop/carts';
    protected $create_item_route = '/admin/shop/cart/create';
    protected $get_item_route = '/admin/shop/cart/read/{id}';
    protected $edit_item_route = '/admin/shop/cart/edit/{id}';
    
    protected function getModel() 
    {
        $model = new \Shop\Models\Carts;
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
    
    protected function displayCreate() {}
    protected function displayRead() {}
    protected function displayEdit() {}
}