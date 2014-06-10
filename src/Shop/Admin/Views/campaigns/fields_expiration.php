<div class="row">
    <div class="col-md-2">
    
        <h3>Groups</h3>
        <p class="help-block">Remove the customer from these user groups when their benefits expire.</p>
                
    </div>
    <!-- /.col-md-2 -->
                
    <div class="col-md-10">

        <div class="form-group">
            <label>Search...</label>
            <div class="input-group">
                <input id="expire_groups" name="expire_groups" value="<?php echo implode(",", (array) $flash->old('expire_groups') ); ?>" type="text" class="form-control" /> 
            </div>       
        </div>
        <!-- /.form-group -->
    
    </div>
    <!-- /.col-md-10 -->
</div>
<!-- /.row -->

<script>
jQuery(document).ready(function() {
    
    jQuery("#expire_groups").select2({
        allowClear: true, 
        placeholder: "Search...",
        multiple: true,
        minimumInputLength: 3,
        ajax: {
            url: "./admin/users/groups/forSelection",
            dataType: 'json',
            data: function (term, page) {
                return {
                    q: term
                };
            },
            results: function (data, page) {
                return {results: data.results};
            }
        }
        <?php if ($flash->old('expire_groups')) { ?>
        , initSelection : function (element, callback) {
            var data = <?php echo json_encode( \Users\Models\Groups::forSelection( array('_id'=>array('$in'=>array_map( function($input){ return new \MongoId($input); }, (array) $flash->old('expire_groups') ) ) ) ) ); ?>;
            callback(data);            
        }
        <?php } ?>
    });

});
</script>

<hr/>