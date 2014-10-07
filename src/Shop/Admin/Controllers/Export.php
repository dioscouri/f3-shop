<?php
namespace Shop\Admin\Controllers;

class Export extends \Admin\Controllers\BaseAuth
{
    public function beforeRoute()
    {
        $this->app->set('meta.title', 'Export | Shop');
    }

    public function index()
    {
        $this->app->set('meta.title', 'Export | Shop');
        
        echo $this->theme->render('Shop/Admin/Views::export/index.php');
    }
    
    public function all_wishlists()
    {
        $time = time();
        $filename = \Base::instance()->get('PATH_ROOT') . 'tmp/' . $time . '.csv';
        
        $writer = (new \Ddeboer\DataImport\Writer\CsvWriter(","))->setStream(fopen($filename, 'w'));
        
        // Write column headers:
        $writer->writeItem(array(
            'id',
            'items_count',
            'email',
            'first_name',
            'last_name'            
        ));
        
        // write items
        $cursor = (new \Shop\Models\Wishlists)->collection()->find(array(
            'items_count' => array( '$gt' => 0 ),
            'user_id' => array( '$nin' => array('', null) ),
        ), array(
            '_id' => 1,
            'user_id' => 1,
            'items_count' => 1,
        ))->sort(array(
            'items_count' => -1
        ));
        
        foreach ($cursor as $doc)
        {
            $item = new \Shop\Models\Wishlists( $doc );
            $user = $item->user();
            
            $writer->writeItem(array(
                $doc['_id'],
                (int) $doc['items_count'],
                $user->email(),
                $user->first_name,
                $user->last_name
            ));
        }
        
        \Web::instance()->send($filename, null, 0, true);
    }
}