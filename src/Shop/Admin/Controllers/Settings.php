<?php 
namespace Shop\Admin\Controllers;

class Settings extends \Admin\Controllers\BaseAuth 
{
    public function index()
    {
        \Base::instance()->set('pagetitle', 'Settings');
        \Base::instance()->set('subtitle', '');
        
        $f3 = \Base::instance();
        $flash = \Dsc\Flash::instance();
        $f3->set('flash', $flash );
        
        $model = $this->getModel();
        $f3->set('model', $model );
        
        $data = $model->cast();
        $flash->store($data);        
        
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
        
        // save
        $save_as = false;
        try {
            $values = $data;
            unset($values['submitType']);

            if (empty($model->id)) {
                $model->create($values);
                \Dsc\System::instance()->addMessage('Settings saved');
            } else {
                $model->update($values);
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
            $data = $model->cast();
            
            return $this->outputJson( $this->getJsonResponse( array(
                            'message' => \Dsc\System::instance()->renderMessages(),
                            'result' => $data
            ) ) );
        }
        
        $f3->reroute( $route );

        return;
    }
    
    protected function getModel()
    {
        $f3 = \Base::instance();
        $model = (new \Shop\Models\Settings)
        ->setState('filter.type', true);
    
        try {
            $model->getItem();
        } catch ( \Exception $e ) {
            \Dsc\System::instance()->addMessage( "Invalid Item: " . $e->getMessage(), 'error');
            $f3->reroute( '/admin/shop/settings' );
            return;
        }
    
        return $model;
    }
}