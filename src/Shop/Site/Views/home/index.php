<h1 id="page-title">Home</h1>

<div>

    <div>
    
    <?php if (!empty($paginated->items)) { ?>
        <ul class="list-inline">
        <?php foreach ($paginated->items as $position=>$item) { ?>
            <?php $item->_url = './shop/product/' . $item->{'slug'}; ?>
            
            <li class="position-<?php echo $position; ?>">
            <?php $this->item = $item; ?> 
            <?php echo $this->renderView('Shop/Site/Views::category/list_item.php'); ?> 
            </li>
            
        <?php } ?>
        </ul>

        <div class="dt-row dt-bottom-row">
            <div class="row">
                <div class="col-sm-10">
                    <?php if (!empty($paginated->total_pages) && $paginated->total_pages > 1) { ?>
                        <?php echo $paginated->serve(); ?>
                    <?php } ?>
                </div>
                <div class="col-sm-2">
                    <div class="datatable-results-count pull-right">
                        <span class="pagination">
                            <?php echo (!empty($paginated->total_pages)) ? $paginated->getResultsCounter() : null; ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    
    <?php } else { ?>
        
        <div class="">No items found.</div>
        
    <?php } ?>

    
    </div>

</div>