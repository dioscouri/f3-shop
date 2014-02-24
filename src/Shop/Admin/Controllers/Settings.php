<?php 
namespace Shop\Admin\Controllers;

class Settings extends \Admin\Controllers\BaseAuth 
{
    public function display()
    {
        \Base::instance()->set('pagetitle', 'Settings');
        \Base::instance()->set('subtitle', '');
        
        $f3 = \Base::instance();
        $flash = \Dsc\Flash::instance();
        $f3->set('flash', $flash );
        
        $model = $this->getModel();
        $item = $this->getItem();
        
        $f3->set('model', $model );
        $f3->set('item', $item );
        
        $item_data = $model->prefab()->cast();
        if (method_exists($item, 'cast')) {
            $item_data = $item->cast();
        } elseif (is_object($item)) {
            $item_data = \Joomla\Utilities\ArrayHelper::fromObject($item);
        }
        $flash->store($item_data);        
        
        $view = \Dsc\System::instance()->get('theme');
        echo $view->render('Shop/Admin/Views::settings/default.php');
    }
    
    public function save()
    {
        $route = "/admin/shop/settings";
        
        $f3 = \Base::instance();
        $flash = \Dsc\Flash::instance();
        $data = $f3->get('REQUEST');
        $model = $this->getModel();
        $this->item = $this->getItem();
        
        // save
        $save_as = false;
        try {
            $values = $data;
            unset($values['submitType']);

            if (empty($this->item->id)) {
                $this->item = $model->create($values);
                \Dsc\System::instance()->addMessage('Settings saved');
            } else {
                $this->item = $model->update($this->item, $values);
                \Dsc\System::instance()->addMessage('Settings updated');
            }            
        }
        catch (\Exception $e) 
        {
            \Dsc\System::instance()->addMessage('Save failed with the following errors:', 'error');
            \Dsc\System::instance()->addMessage($e->getMessage(), 'error');
            foreach ($model->getErrors() as $error)
            {
                \Dsc\System::instance()->addMessage($error, 'error');
            }
        
            if ($f3->get('AJAX')) {
                // output system messages in response object
                return $this->outputJson( $this->getJsonResponse( array(
                                'error' => true,
                                'message' => \Dsc\System::instance()->renderMessages()
                ) ) );
            }
        
            // redirect back to the form with the fields pre-populated
            $flash->store($data);
            $f3->reroute( $route );
        
            return;
        }
        
        if ($f3->get('AJAX')) 
        {
            if (method_exists($this->item, 'cast')) {
                $this->item_data = $this->item->cast();
            } else {
                $this->item_data = \Joomla\Utilities\ArrayHelper::fromObject($this->item);
            }
            
            return $this->outputJson( $this->getJsonResponse( array(
                            'message' => \Dsc\System::instance()->renderMessages(),
                            'result' => $this->item_data
            ) ) );
        }
        
        $f3->reroute( $route );

        return;
    }
    
    protected function getModel()
    {
        $model = new \Shop\Admin\Models\Settings;
        return $model;
    }
    
    protected function getItem()
    {
        $f3 = \Base::instance();
        $model = $this->getModel()
        ->setState('filter.type', true);
    
        try {
            $item = $model->getItem();
        } catch ( \Exception $e ) {
            \Dsc\System::instance()->addMessage( "Invalid Item: " . $e->getMessage(), 'error');
            $f3->reroute( '/admin/shop/settings' );
            return;
        }
    
        return $item;
    }
}