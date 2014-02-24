<?php 
namespace Shop\Modules\Products;

class Module extends \Modules\Abstracts\Module 
{
    public $list = array(); // unprocessed list of menu items, typically straight from the data source
    public $items = array(); // final, processed list of menu items ready for display
    
    public function __construct($options=array()) 
    {
        if (!empty($options['list'])) {
            $this->list = $options['list'];
            unset($options['list']);
        }
                
        parent::__construct($options);
    }
    
    public function html()
    {
        $f3 = \Base::instance();
        
        $old_ui = $f3->get('UI');
        $temp_ui = !empty($this->options['views']) ? $this->options['views'] : dirname( __FILE__ ) . "/Views/";
        $f3->set('UI', $temp_ui);
        
        $f3->set('module', $this);

        \Dsc\System::instance()->get('theme')->registerViewPath( __dir__ . '/Views/', 'Shop/Modules/Products/Views' );
        $string = \Dsc\System::instance()->get('theme')->renderLayout('Shop/Modules/Products/Views::default.php');
        
        $f3->set('UI', $old_ui);
        
        return $string;
    }
}