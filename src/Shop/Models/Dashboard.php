<?php
namespace Shop\Models;

class Dashboard extends \Dsc\Models
{
    public function fetchTotalSales($start=null, $end=null)
    {
        $model = (new \Shop\Models\Orders)
            ->setState('filter.financial_status', \Shop\Constants\OrderFinancialStatus::paid);
        
        if (!empty($start)) {
        	$model->setState('filter.created_after', $start);
        }
        
        if (!empty($end)) {
            $model->setState('filter.created_before', $end);
        }
        
        $conditions = $model->conditions();
    
        $agg = \Shop\Models\Orders::collection()->aggregate(array(
            array(
                '$match' => $conditions
            ),
            array(
                '$group' => array(
                	'_id' => null,
                    'total' => array( '$sum' => '$grand_total' ),
                    'count' => array( '$sum' => 1 )
                )
            )
        ));
    
        //\Dsc\System::addMessage( \Dsc\Debug::dump($conditions) );
        //\Dsc\System::addMessage( \Dsc\Debug::dump($start) );
        //\Dsc\System::addMessage( \Dsc\Debug::dump($end) );
        //\Dsc\System::addMessage( \Dsc\Debug::dump($agg) );
        
        $return = array(
            'total'=>0,
            'count'=>0,
        );
        if (!empty($agg['ok']) && !empty($agg['result']))
        {
            $return = $agg['result'][0];
        }

        return $return;
    } 
    
    public function fetchTopSellers($start=null, $end=null)
    {
        $model = (new \Shop\Models\Orders)
        ->setState('filter.financial_status', \Shop\Constants\OrderFinancialStatus::paid);
    
        if (!empty($start)) {
            $model->setState('filter.created_after', $start);
        }
    
        if (!empty($end)) {
            $model->setState('filter.created_before', $end);
        }
    
        $conditions = $model->conditions();
    
        $agg = \Shop\Models\Orders::collection()->aggregate(array(
            array(
                '$match' => $conditions
            ),
            array(
                '$unwind' => '$items'
            ),
            array(
                '$group' => array(
                    '_id' => '$items.product_id',
                    'total' => array( '$sum' => '$items.quantity' ),
                )
            ),
            array(
                '$sort' => array( 'total' => -1 )
            ),
            array(
                '$limit' => 5
            ),
            
        ));
    
        //\Dsc\System::addMessage( \Dsc\Debug::dump($conditions) );
        //\Dsc\System::addMessage( \Dsc\Debug::dump($start) );
        //\Dsc\System::addMessage( \Dsc\Debug::dump($end) );
        //\Dsc\System::addMessage( \Dsc\Debug::dump($agg) );
    
        $items = array();
        if (!empty($agg['ok']) && !empty($agg['result']))
        {
            foreach ($agg['result'] as $result) 
            {
                $product = (new \Shop\Models\Products)->setState('filter.id', $result['_id'])->getItem();
                if (!empty($product->id)) 
                {
                    $product->__total = $result['total'];
                    $items[] = $product;
                }
            }
        }
    
        return $items;
    }

    public function fetchSalesData($start=null, $end=null)
    {
        $model = (new \Shop\Models\Orders)
        ->setState('filter.financial_status', \Shop\Constants\OrderFinancialStatus::paid);
    
        if (!empty($start)) {
            $model->setState('filter.created_after', $start);
        }
    
        if (!empty($end)) {
            $model->setState('filter.created_before', $end);
        }
    
        $conditions = $model->conditions();
    
        $agg = \Shop\Models\Orders::collection()->aggregate(array(
            array(
                '$match' => $conditions
            ),
            array(
                '$group' => array(
                    '_id' => null,
                    'total' => array( '$sum' => '$grand_total' ),
                    'count' => array( '$sum' => 1 )
                )
            )
        ));
    
        //\Dsc\System::addMessage( \Dsc\Debug::dump($conditions) );
        //\Dsc\System::addMessage( \Dsc\Debug::dump($start) );
        //\Dsc\System::addMessage( \Dsc\Debug::dump($end) );
        //\Dsc\System::addMessage( \Dsc\Debug::dump($agg) );
    
        $total = 0;
        if (!empty($agg['ok']) && !empty($agg['result']))
        {
            $total = (float) $agg['result'][0]['total'];
        }
    
        return (float) $total;
    }
    
}