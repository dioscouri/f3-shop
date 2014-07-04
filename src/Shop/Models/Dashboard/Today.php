<?php
namespace Shop\Models\Dashboard;

class Today extends \Shop\Models\Dashboard
{
    public function totalSales()
    {
        return $this->fetchTotalSales(date('Y-m-d'));
    }
    
    public function topSellers()
    {
        return $this->fetchtopSellers(date('Y-m-d'));
    }
    
    public function salesData()
    {
        $return = array();
        
        $results = array();
        $results[] = array(
            'Hour',
            'Total',
            'Orders'
        );
        
        for ($n=0; $n<24; $n++) 
        {
            $result = $this->fetchTotalSales(date('Y-m-d '.$n.':00:00', strtotime('today')), date('Y-m-d '.$n.':59:59', strtotime('today')));
            $results[] =  array(
                $n,
                $result['total'],
                $result['count'],
            );
        }
        
        $return['haxis.title'] = 'Hour';
        $return['results'] = $results;
        
        return $return;
    }    
}