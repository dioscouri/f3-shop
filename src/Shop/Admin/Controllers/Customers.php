<?php 
namespace Shop\Admin\Controllers;

class Customers extends \Admin\Controllers\BaseAuth 
{
    public function forSelection()
    {
        $field = $this->input->get('value', '_id', 'default');
        $term = $this->input->get('q', null, 'default');
        $key =  new \MongoRegex('/'. $term .'/i');
        
        $where = array();
        $where[] = array(
            'username' => $key
        );
        $where[] = array(
            'email' => $key
        );
        $where[] = array(
            'first_name' => $key
        );
        $where[] = array(
            'last_name' => $key
        );
        
        $results = \Shop\Models\Customers::forSelection(array('$or'=>$where), $field);
    
        $response = new \stdClass;
        $response->more = false;
        $response->term = $term;
        $response->results = $results;
    
        return $this->outputJson($response);
    }
}