<?php 
namespace Shop\Site\Controllers;

class PepperJam extends \Dsc\Controller 
{
    /**
     * \T (tab) delimited feed of products
     * 
     * http://www.pepperjamnetwork.com/doc/product_feed_advanced.html
     * 
     */
    public function productsTxt()
    {
        $settings = \Shop\Models\Settings::fetch();
        if (!$settings->{'feeds.pepperjam_products.enabled'}) 
        {
            return;
        }
        
        $this->app->set('CACHE', true);
        
        $cache = \Cache::instance();
        $cache_period = 3600*24;
        if ($cache->exists('pepperjam.products_text', $string)) 
        {
            header('Content-Type: text/plain; charset=utf-8');
            echo $string;
            exit;            
        }
        
        $base = \Dsc\Url::base();
        
        $model = (new \Shop\Models\Products)
            //->setState('filter.id', '52f6b2f2f02e25087f58f369') // color and size
            //->setState('filter.id', '52f6e0cff02e25103a74676b') // color only
            ->setState('filter.published_today', true)
    		->setState('filter.inventory_status', 'in_stock')
    		->setState('filter.publication_status', 'published');
        
        $conditions = $model->conditions();
        $conditions['product_type'] = array('$nin'=>array('giftcard', 'giftcards'));
        $cursor = \Shop\Models\Products::collection()->find($conditions)->sort(array('title'=>1));//->limit(10);

        /**
         * name	sku	buy_url	image_url	description_short	description_long	price	manufacturer
         */

        $column_headers = array(
            'name',
            'sku',
            'buy_url',
            'image_url',
            'description_short',
            'description_long',
            'price',
            'manufacturer'
        );
        
        $string = implode("\t", $column_headers) . "\r\n";
        
        foreach ($cursor as $product_doc) 
        {
            $product = new \Shop\Models\Products($product_doc);
            foreach ($product->variantsInStock() as $variant) 
            {
                $price = $product->price( $variant['id'] );
                
                // Skip products where price == 0.00                
                if (empty($price)) 
                {
                    continue;
                }
                
                $pieces = array(
                    'name' => null,
                    'sku' => null,
                    'buy_url' => null,
                    'image_url' => null,
                    'description_short' => null,
                    'description_long' => null,
                    'price' => null,
                    'manufacturer' => null
                );
                
                $pieces['name'] = $product->title;
                
                $sku = $variant['sku'] ? $variant['sku'] : $product->{'tracking.sku'};
                if (!$sku) {
                    $sku = $variant['id'];
                }                
                $pieces['sku'] = $sku;
                
                $pieces['buy_url'] = $base . 'shop/product/' . $product->slug . '?variant_id=' . $variant['id'];
                
                // image_link
                if ($image = $variant['image'] ? $variant['image'] : $product->{'featured_image.slug'})
                {
                    $pieces['image_url'] = $base . 'asset/' . $image;
                }
                
                $pieces['description_short'] = $product->title . ' ';
                if ($attribute_title = \Dsc\ArrayHelper::get($variant, 'attribute_title')) {
                    $pieces['description_short'] .= $attribute_title;
                }
                $pieces['description_short'] = trim($pieces['description_short']);
                
                $pieces['description_long'] = strip_tags( $product->getAbstract() );
                
                $pieces['price'] = $price;
                
                if ($brand = $settings->{'feeds.pepperjam_products.brand'})
                {
                    $pieces['manufacturer'] = $brand;
                }
                
                $string .= implode("\t", $pieces) . "\r\n";
            }
        }
        
        $cache->set('pepperjam.products_text', $string, $cache_period);

        header('Content-Type: text/plain; charset=utf-8');
        echo $string;
        exit;
    }
}