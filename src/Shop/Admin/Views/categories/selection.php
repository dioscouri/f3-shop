<div class="row">
                        <div class="col-md-2">
                            
                            <h3>Categories</h3>
                            <p class="help-block">Type the first 3 lets of the category name</p>
                                    
                        </div>
                        <!-- /.col-md-2 -->
                                    
                        <div class="col-md-10">
                        
                            <div class="form-group">
                                <label>Categories</label>
                               
                               <select id="categories"  name="category_ids[]" multiple >
                               <?php if ($categories = \Shop\Models\Categories::find()) { ?>
									
								    <?php $current = \DscArrayHelper::getColumn( (array) $flash->old('categories'), 'id' ); ?>
								    <?php foreach ($categories as $one) { ?>
								    <option <?php if (in_array($one->_id, $current)) { echo "selected='selected'"; } ?> value="<?php echo $one->_id; ?>"><?php echo  $one->title;?></option>
								    <?php } ?> 

								<?php } ?>
                               
                               </select>
                            </div>
                            <!-- /.form-group -->
                            
                        </div>
                        <!-- /.col-md-10 -->     
</div>
<!-- /.row -->
<script> jQuery("#categories").select2(); </script>