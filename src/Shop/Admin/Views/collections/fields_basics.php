<div class="row">
    <div class="col-md-2">
        
        <h3>Basics</h3>
                
    </div>
    <!-- /.col-md-2 -->
                
    <div class="col-md-10">
    
        <div class="form-group">
            <label>Title</label>
            <input type="text" name="title" placeholder="Title" value="<?php echo $flash->old('title'); ?>" class="form-control" />
        </div>
        <!-- /.form-group -->
        
        <div class="form-group">
        <label>Slug</label>
             <input type="text" name="slug" placeholder="Slug" value="<?php echo $flash->old('slug'); ?>" class="form-control" />
        </div>
        <!-- /.form-group -->
        
    </div>
    <!-- /.col-md-10 -->
    
</div>
<!-- /.row -->

<hr />

<div class="row">
    <div class="col-md-2">
        
        <h3>Sorting</h3>
        <p class="help-block">Determines the default sort order for products in this collection.  Customers will still be able to change the sort.</p>
                
    </div>
    <!-- /.col-md-2 -->
                
    <div class="col-md-10">
        
        <div class="row">
        
            <div class="col-md-4">
                <select name="sort_by" class="form-control">
                    <option value="title-asc" <?php if ($flash->old('sort_by') == 'title-asc') { echo "selected"; } ?>>Title +</option>
                    <option value="title-desc" <?php if ($flash->old('sort_by') == 'title-desc') { echo "selected"; } ?>>Title -</option>
                    <option value="price-asc" <?php if ($flash->old('sort_by') == 'price-asc') { echo "selected"; } ?>>Price +</option>
                    <option value="price-desc" <?php if ($flash->old('sort_by') == 'price-desc') { echo "selected"; } ?>>Price -</option>
                    <option value="ordering-asc" <?php if ($flash->old('sort_by') == 'ordering-asc') { echo "selected"; } ?>>Manual Ordering</option>
                </select>
            </div>
            <div class="col-md-8">
                <?php if (empty($item->id)) { ?>
                    <div class="form-group">
                        <p class="help-block">You must save your collection before your can sort its products</p>
                    </div>
                    <!-- /.form-group -->                
                <?php } else { ?>
                    <div class="form-group">
                        <p>
                            <a class="btn btn-link" href="./admin/shop/collection/<?php echo $item->id; ?>/products">Manually sort this collection's products <b>(Save your work first!)</b></a>
                        </p>
                    </div>
                    <!-- /.form-group -->                
                <?php } ?>            
            </div>
        </div>
        <!-- /.form-group -->        
        
    </div>
    <!-- /.col-md-10 -->
    
</div>
<!-- /.row -->

<hr />

<?php echo $this->renderLayout('Shop/Admin/Views::collections/fields_basics_products.php'); ?>

<hr />

<?php echo $this->renderLayout('Shop/Admin/Views::collections/fields_basics_prices.php'); ?>

<hr />

<?php echo $this->renderLayout('Shop/Admin/Views::collections/fields_basics_inventory.php'); ?>

<hr />

<?php echo $this->renderLayout('Shop/Admin/Views::collections/fields_basics_tags.php'); ?>

<hr />

<?php echo $this->renderLayout('Shop/Admin/Views::collections/fields_basics_publication.php'); ?>

<hr />

<?php echo $this->renderLayout('Shop/Admin/Views::collections/fields_basics_categories.php'); ?>
