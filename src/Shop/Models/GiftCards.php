<?php 
namespace Shop\Models;

class GiftCards extends \Shop\Models\Products
{
    public $product_type = 'giftcards';
    
    public $variants = array(
    	array(
    	    'attribute_title'=> '10.00',
    	    'price'=>'10.00',
    	    'enabled'=>1,
    	    'ordering'=>1,
        ),
        array(
            'attribute_title'=> '25.00',
            'price'=>'25.00',
            'enabled'=>1,
            'ordering'=>2,
        ),
        array(
            'attribute_title'=> '50.00',
            'price'=>'50.00',
            'enabled'=>1,
            'ordering'=>3,
        ),
        array(
            'attribute_title'=> '100.00',
            'price'=>'100.00',
            'enabled'=>1,
            'ordering'=>4,
        ),
    );

    public $attributes = array(
        array(
            'title' => 'Price',
            'ordering'=>1,
            'options' => array(
                array(
                    'value' => '10.00',         // for gift cards, the attribute option values === the variant price
                    'ordering'=>1,
                ),
                array(
                    'value' => '25.00',
                    'ordering'=>2,
                ),
                array(
                    'value' => '50.00',
                    'ordering'=>3,
                ),
                array(
                    'value' => '100.00',
                    'ordering'=>4,
                ),
            )
        ),
    );

    public $attributes_count = 1;
    public $variants_count = 4;
        
    public function __construct($source=array(), $options=array())
    {
        parent::__construct( $source, $options );
        
        foreach ($this->attributes as $key=>$attribute) 
        {
            $this->attributes[$key] = (new \Shop\Models\Prefabs\Attribute( $attribute ))->cast();
            foreach ($this->attributes[$key]['options'] as $ao_key=>$ao_value) 
            {
                $this->attributes[$key]['options'][$ao_key] = (new \Shop\Models\Prefabs\AttributeOption( $ao_value ))->cast();
            }
        }
        
        foreach ($this->variants as $key=>$variant) 
        {
            if (empty($this->variants[$key]['id'])) {
                $this->variants[$key]['id'] = (string) new \MongoId;
            }
            
            foreach ($this->attributes as $a_key=>$attribute) 
            {
                foreach ($this->attributes[$a_key]['options'] as $ao_key=>$ao_value) 
                {
                    if ($this->attributes[$a_key]['options'][$ao_key]['value'] == $variant['price']) 
                    {
                        $this->variants[$key]['key'] = $this->attributes[$a_key]['options'][$ao_key]['id'];
                    	break 2;
                    }
                }
            }        	
        }
        
        $this->set('shipping.enabled', false);
        $this->set('policies.track_inventory', false);
        $this->set('policies.variant_pricing.enabled', true);
        
        $this->variants = \Dsc\ArrayHelper::sortArrays(array_values( $this->variants ), 'ordering');
    }
    
    protected function fetchConditions()
    {
        parent::fetchConditions();
    
        $this->setCondition('giftcard', true );
    }
}