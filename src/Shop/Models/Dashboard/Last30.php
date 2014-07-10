<?php
namespace Shop\Models\Dashboard;

class Last30 extends \Shop\Models\Dashboard
{
    public function totalSales()
    {
        return $this->fetchTotalSales(date('Y-m-d 00:00:00', strtotime('today -29 days')));
    }

    public function topSellers()
    {
        return $this->fetchtopSellers(date('Y-m-d 00:00:00', strtotime('today -29 days')));
    }

    public function salesData()
    {
        $return = array();
        
        $results = array();
        $results[] = array(
            'M/D',
            'Total'
        );
        
        $start = date('Y-m-d', strtotime('today -29 days'));
        for ($n = 0; $n < 30; $n++)
        {
            $start_date = (new \DateTime($start))->add( \DateInterval::createFromDateString( $n . ' days' ) );
            $start_datetime = $start_date->format('Y-m-d 00:00:00');
            $end_datetime = (new \DateTime($start))->add( \DateInterval::createFromDateString( ($n + 1) . ' days' ) )->format('Y-m-d 00:00:00');
            
            $result = $this->fetchTotalSales($start_datetime, $end_datetime);
            $results[] = array(
                $start_date->format('m/d'),
                $result['total']
            );
        }
        
        $return['haxis.title'] = 'Day';
        $return['results'] = $results;
        
        return $return;
    }
}