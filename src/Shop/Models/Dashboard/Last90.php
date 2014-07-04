<?php
namespace Shop\Models\Dashboard;

class Last90 extends \Shop\Models\Dashboard
{
    public function totalSales()
    {
        return $this->fetchTotalSales(date('Y-m-d 00:00:00', strtotime('today -90 days')));
    }
    
    public function topSellers()
    {
        return $this->fetchtopSellers(date('Y-m-d 00:00:00', strtotime('today -90 days')));
    }    
    
    public function salesData()
    {
        $return = array();
        
        $results = array();
        $results[] = array(
            'M/D',
            'Total',
            'Orders'
        );
        
        $start = date('Y-m-d', strtotime('today -90 days'));
        $n=0;
        while ($n<90) 
        {
            $result = $this->fetchTotalSales(date('Y-m-d 00:00:00', strtotime( $start . ' +' . $n . ' days' )), date('Y-m-d 00:00:00', strtotime( $start . ' +' . $n+7 . ' days' )));
            $results[] =  array(
                date('m/d', strtotime( $start . ' +' . $n . ' days' ) ),
                $result['total'],
                $result['count'],
            );
            
            $n=$n+7;
        }
        
        $return['haxis.title'] = 'Week of';
        $return['results'] = $results;
        
        return $return;
    }
}