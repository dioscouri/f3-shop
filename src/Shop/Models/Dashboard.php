<?php
namespace Shop\Models;

class Dashboard extends \Dsc\Models
{
    public function fetchTotalSales($start=null, $end=null)
    {
        $model = (new \Shop\Models\Orders)
            ->setState('filter.status_excludes', \Shop\Constants\OrderStatus::cancelled)
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
        ->setState('filter.status_excludes', \Shop\Constants\OrderStatus::cancelled)
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
        ->setState('filter.status_excludes', \Shop\Constants\OrderStatus::cancelled)
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
    
    public function fetchConversions($start=null, $end=null) 
    {
        $model = (new \Activity\Models\Actions);
        
        if (!empty($start)) {
            $model->setState('filter.created_after', $start);
        }
        
        if (!empty($end)) {
            $model->setState('filter.created_before', $end);
        }

        $base_conditions = $model->conditions();
        $total = $this->fetchTotalVisitors( $start, $end );
        $return = array();
        
        //Added to Cart
        $cart_conditions = $base_conditions + array(
            'action' => new \MongoRegex('/Added to Cart/i'),
            'properties.app' => 'shop'
        );                
        $count = count($model->collection()->distinct( 'actor_id', $cart_conditions ));
        $perc = empty($total) ? 0 : number_format((($count / $total) * 100), 1) . "%";
        $return['Added to Cart'] = array(
            'count' => $count,
            'perc' => $perc 
        );
        
        //Checkout Registration Page
        $cart_conditions = $base_conditions + array(
            'action' => new \MongoRegex('/Checkout Registration Page/i'),
            'properties.app' => 'shop'
        );
        $count = count($model->collection()->distinct( 'actor_id', $cart_conditions ));
        $perc = empty($total) ? 0 : number_format((($count / $total) * 100), 1) . "%";
        $return['Checkout Registration Page'] = array(
            'count' => $count,
            'perc' => $perc
        );        
        
        // Started Checkout
        $start_conditions = $base_conditions + array(
            'action' => new \MongoRegex('/Started Checkout/i'),
            'properties.app' => 'shop'
        );
        $count = count($model->collection()->distinct( 'actor_id', $start_conditions ));
        $perc = empty($total) ? 0 : number_format((($count / $total) * 100), 1) . "%";
        $return['Started Checkout'] = array(
            'count' => $count,
            'perc' => $perc
        );
        
        // Reached Payment Step in Checkout
        $payment_conditions = $base_conditions + array(
            'action' => new \MongoRegex('/Reached Payment Step in Checkout/i'),
            'properties.app' => 'shop'
        );
        $count = count($model->collection()->distinct( 'actor_id', $payment_conditions ));
        $perc = empty($total) ? 0 : number_format((($count / $total) * 100), 1) . "%";
        $return['Reached Payment Step in Checkout'] = array(
            'count' => $count,
            'perc' => $perc
        );      

        // Completed Checkout
        $complete_checkout_conditions = $base_conditions + array(
            'action' => new \MongoRegex('/Completed Checkout/i'),
            'properties.app' => 'shop'
        );
        $count = count($model->collection()->distinct( 'actor_id', $complete_checkout_conditions ));
        $perc = empty($total) ? 0 : number_format((($count / $total) * 100), 1) . "%";
        $return['Completed Checkout'] = array(
            'count' => $count,
            'perc' => $perc
        );        
                
        return $return;
    }
    
    /**
     * Actually returns total visitors.
     * TODO Rename function
     *
     * @param string $start
     * @param string $end
     * @return multitype:number
     */
    public function fetchTotalVisitors($start=null, $end=null)
    {
        $conditions = array(
            'action' => 'Visited Site'
        );
    
        if (!empty($start)) {
            $conditions['created'] = array('$gte' => strtotime($start));
        }
    
        if (!empty($end)) {
            if (empty($conditions['created'])) {
                $conditions['created'] = array('$lt' => strtotime($end));
            } else {
                $conditions['created']['$lt'] = strtotime($end);
            }
    
        }
    
        $return = \Activity\Models\Actions::collection()->count($conditions);
    
        return $return;
    }    
    
}