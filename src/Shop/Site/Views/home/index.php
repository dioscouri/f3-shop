<h1 id="page-title"><?php echo $category->title; ?></h1>

<div>

    <div>
    
    <?php if (!empty($list['subset'])) { ?>
        <ul class="list-inline">
        <?php foreach ($list['subset'] as $position=>$item) { ?>
            <?php $item->_url = './shop/product/' . $item->{'metadata.slug'}; ?>
            
            <li class="position-<?php echo $position; ?>">
            <?php $this->item = $item; ?> 
            <?php echo $this->renderView('Shop/Site/Views::category/list_item.php'); ?> 
            </li>
            
        <?php } ?>
        </ul>

        <div class="row datatable-footer">
            <?php if (!empty($list['count']) && $list['count'] > 1) { ?>
            <div class="col-sm-10">
                <?php echo (!empty($list['count']) && $list['count'] > 1) ? $pagination->serve() : null; ?>
            </div>
            <?php } ?>
            <div class="col-sm-2 pull-right">
                <div class="datatable-results-count pull-right">
                <?php echo $pagination ? $pagination->getResultsCounter() : null; ?>
                </div>
            </div>
        </div>    
    
    <?php } else { ?>
        
        <div class="">No items found.</div>
        
    <?php } ?>

    
    </div>

</div>