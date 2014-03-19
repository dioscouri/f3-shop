<?php 
namespace Shop\Models;

class Settings extends \Dsc\Mongo\Collections\Settings 
{
    protected $__type = 'shop.settings';
    
    public function prefab( $source=array(), $options=array() )
    {
        $prefab = new \Shop\Models\Prefabs\Settings($source, $options);
    
        return $prefab;
    }
    
    /**
     * An alias for the save command, used only for creating a new object
     *
     * @param array $values
     * @param array $options
     */
    public function create( $document = array(), $options=array() )
    {	
        $values = array_merge( $this->prefab()->cast(), $document );
    
        return $this->save( $document, $options );
    }
}