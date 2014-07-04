<?php 
namespace Shop\Site\Controllers;

class GoogleMerchant extends \Dsc\Controller 
{
    public function productsXml()
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
        $cursor = \Shop\Models\Products::collection()->find($conditions)->limit(10);

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
                // TODO Skip products where price == 0.00
                
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
                    
                    // TODO google_product_category
                    // TODO product_type
                    // TODO brand = $brand from settings
                    
                    // Apparel requires:
                    // TODO gender = female (or male, unisex)
                    // TODO age_group = adult (or newborn, infanct, toddler, kids)
                    
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
                    $x->startElement('g:id');
                        $x->text($sku);
                    $x->endElement(); // g:id
                    
                    $x->startElement('g:mpn');
                        $x->text($sku);
                    $x->endElement(); // g:mpn                    
                    
                    $x->startElement('g:price');
                        $x->text( $product->price( $variant['id'] ) . ' USD');
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