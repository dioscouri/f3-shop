<h3 class="">Order Settings</h3>
<hr />

<div class="row">
    <div class="col-md-2">
    
        <h3>Printing</h3>
                
    </div>
    <!-- /.col-md-2 -->
                
    <div class="col-md-10">

        <div class="form-group">
            <label>Header</label>
            <textarea name="orders[printing][header]" class="form-control wysiwyg"><?php echo $flash->old('orders.printing.header'); ?></textarea>
        </div>
        <!-- /.form-group -->
        
        <div class="form-group">
            <label>Footer</label>
            <textarea name="orders[printing][footer]" class="form-control wysiwyg"><?php echo $flash->old('orders.printing.footer'); ?></textarea>
        </div>
        <!-- /.form-group -->
    
    </div>
    <!-- /.col-md-10 -->
</div>
<!-- /.row -->

<hr />
