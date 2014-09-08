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
    public function products()
    {
        $settings = \Shop\Models\Settings::fetch();
        if (!$settings->{'feeds.gm_products.enabled'}) 
        {
            return;
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
         * Generate XML
         */
        $x = new \XMLWriter();
        $x->openMemory();
        $x->setIndent(true);
        $x->setIndentString(" ");
        $x->startDocument('1.0', 'UTF-8');        
        
        $x->startElement('rss');
            $x->writeAttribute('version', '2.0');
            $x->writeAttribute('xmlns:g', 'http://base.google.com/ns/1.0');
        
        $x->startElement('channel');
        
        $title = $settings->{'feeds.gm_products.title'} ? $settings->{'feeds.gm_products.title'} : 'Product Feed';
        $x->startElement('title');
            $x->text($title);
        $x->endElement(); // title
        
        $link = $base;
        $x->startElement('link');
            $x->text($link);
        $x->endElement(); // link

        if ($description = $settings->{'feeds.gm_products.description'}) 
        {
            $x->startElement('description');
                $x->text($description);
            $x->endElement(); // description            
        }
        
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
                
                $x->startElement('item');
                    $x->startElement('title');
                        $x->text($product->title);
                    $x->endElement(); // title
                    
                    $x->startElement('description');
                        $x->text(strip_tags( $product->getAbstract() ));
                    $x->endElement(); // description
                    
                    $x->startElement('g:link');
                        $x->text($base . 'shop/product/' . $product->slug);
                    $x->endElement(); // g:link

                    // image_link
                    if ($image = $variant['image'] ? $variant['image'] : $product->{'featured_image.slug'}) 
                    {
                        $x->startElement('g:image_link');
                            $x->text($base . 'asset/' . $image);
                        $x->endElement(); // g:image_link                        
                    }
                    
                    // google_product_category
                    if ($product->{'gm_product_category'})
                    {
                        $x->startElement('g:google_product_category');
                            $x->text($product->{'gm_product_category'});
                        $x->endElement(); // g:google_product_category
                    }
                    
                    // TODO product_type
                    
                    // gender = female (or male, unisex)
                    $gender = $settings->{'feeds.gm_products.gender'};
                    if ($product->{'gm_products.gender'}) 
                    {
                        $gender = $product->{'gm_products.gender'};
                    }
                    
                    if ($gender)
                    {
                        $x->startElement('g:gender');
                            $x->text($gender);
                        $x->endElement(); // g:gender
                    }
                                        
                    // age_group = adult (or newborn, infanct, toddler, kids)
                    $age_group = $settings->{'feeds.gm_products.age_group'};
                    if ($product->{'gm_products.age_group'})
                    {
                        $age_group = $product->{'gm_products.age_group'};
                    }
                    
                    if ($age_group)
                    {
                        $x->startElement('g:age_group');
                           $x->text($age_group);
                        $x->endElement(); // g:age_group
                    }                    
                    
                    // following handles color, size, pattern, material (if they are set as attributes)
                    foreach ($product->attributes as $attribute) 
                    {
                        $att_title = strtolower($attribute['title']);
                        if (in_array($att_title, array(
                            'color', 'material', 'pattern', 'size'
                        ))) {
                            
                            $att_id = $attribute['id'];
                            // get the attribute options
                            $options = array();
                            foreach ($attribute['options'] as $option) 
                            {
                                $options[] = $option['id'];
                            }
                                
                            if ($found = array_intersect($options, $variant['attributes'])) 
                            {
                                $key = array_search($found, $variant['attributes']);                                
                                if (!empty($variant['attribute_titles'][$key])) 
                                {
                                    $x->startElement('g:' . $att_title);
                                        $x->text($variant['attribute_titles'][$key]);
                                    $x->endElement(); // g:$att_title
                                }
                            }
                        }
                    }
                    
                    // since we do variants: item_group_id
                    $x->startElement('g:item_group_id');
                        $x->text($product->{'tracking.sku'});
                    $x->endElement(); // g:item_group_id                    
                                        
                    $sku = $variant['sku'] ? $variant['sku'] : $product->{'tracking.sku'};
                    if (!$sku) {
                        $sku = $variant['id'];
                    }                    
                    $x->startElement('g:id');
                        $x->text($sku);
                    $x->endElement(); // g:id
                    
                    if ($brand = $settings->{'feeds.gm_products.brand'})
                    {
                        $x->startElement('g:brand');
                            $x->text($brand);
                        $x->endElement(); // g:brand
                    }
                    
                    $x->startElement('g:mpn');
                        $x->text($sku);
                    $x->endElement(); // g:mpn                    
                    
                    $x->startElement('g:price');
                        $x->text( $price . ' USD');
                    $x->endElement(); // g:price
                    
                    $x->startElement('g:condition');
                        $x->text('new');
                    $x->endElement(); // g:condition
                    
                    $x->startElement('g:availability');
                        $x->text('in stock');
                    $x->endElement(); // g:availability                                        
                    
                $x->endElement(); // item
            }
        }

        $x->endElement(); // channel
        $x->endElement(); // rss
        
        $x->endDocument();

        header('Content-Type: application/xml; charset=utf-8');
        echo $x->outputMemory();
    }
}