

<div class="pull-right">
                
                  <a  class="addNewSpec btn btn-success " href="javascript:void(0)">Add A NEW SPEC</a>
                 	<a  class="deleteAllSpecs btn btn-danger " href="javascript:void(0)">Delete All Specs</a> 
                
                
                </div>
                
                <br class="clearfix">
                 <br class="clearfix">
                  <br class="clearfix">
                  <div class="col-lg-12" id="NewSpecs" style="display:none; border-bottom: 2px dashed #eee; margin-bottom: 10px;" >
                  <h3>New Specs</h3>
                  </div>
                  
                  
                 <div class="col-lg-4">
                <ul id="draggablePanelList" class="list-unstyled panel-group" id="accordion" role="tablist">
                
                <?php $key=0; if(!empty($flash->old('product_specs'))) : ?>
                <?php foreach($flash->old('product_specs') as $key => $spec) :?>
                
                <?php 
                                $kid = preg_replace('/\s+/', '', $key);
                                ?>
           <li class="specwrapper" id="spec-<?php echo $kid?>">
			                <div class="panel panel-default" >
							  <div class="panel-heading">
							  	<div class="pull-right">
							    <a  class="move"><i class="glyphicon glyphicon-sort"></i></a>&nbsp;&nbsp;
							  	<a  class="expand" data-toggle="collapse" data-target="#panel-<?php echo $kid?>"><i class="glyphicon glyphicon-plus"></i></a>
							    </div>
							    <h3 class="panel-title pull-left">
							    <a href="#" class="editable" data-type="text" data-pk="<?php echo $kid?>" data-orig="<?php echo $key; ?>"  data-title="Edit Spec"><?php echo $key; ?></a>
							    </h3>
							    
								     <br class="clearfix">
							  </div>
							  <div class="panel-body collapse" id="panel-<?php echo $kid?>">
							    <ul class="list-group sortable" id="options<?php echo $kid; ?>">
							    <?php if(!empty($spec['options'])) : ?>
			                                <?php foreach ($spec['options'] as $i =>  $option) : ?>
			                                <?php $id = $key.$option.$i; 
			                                $id = preg_replace('/\s+/', '', $id);
			                                ?>
			                                <li class="list-group-item" id="<?php echo $id; ?>">
			                                <div class="input-group" id="<?php ?>">
											  <input class="form-control <?php echo $kid; ?>" name="product_specs[<?php echo $key; ?>][options][]" value="<?php echo $option; ?>">
											 
											  <span class="input-group-addon btn btn-danger deleteOption" data-id="<?php echo $id; ?>"><i class="fa fa-times"></i></span>
											</div>
			                         
			                                 </li>
			                                <?php endforeach; ?>
			                                <?php endif; ?>
			                                
			                                </ul>
			                                <a href="javascript:void(0)" class="addNewOption " data-id="options<?php echo $kid; ?>" data-key="<?php echo $key; ?>"> Add NEW</a>
							  </div>
							  <div class="panel-footer">
							   <label class="pull-left">
								      <input type="checkbox" class='<?php echo $kid; ?>' <?php if(!empty($flash->old('product_specs.'.$key.'.required'))) { echo 'checked="checked"'; }?> name="product_specs[<?php echo $key; ?>][required]"> Required 
								    </label>
							  
							   <input type="hidden" class="<?php echo $kid; ?>" name="product_specs[<?php echo $key; ?>][exists]" value="1">
							 <a class="pull-right deleteSpec "  data-id="spec-<?php echo $kid?>" href="javascript:void(0)">DELETE SPEC</a>
							 <br class="clearfix">
							 </div>
							</div>
                
               	</li>
                <?php endforeach; ?>
                <?php endif; ?>
                </ul>
                </div>
                <template type="text/template" id="newOptionTemplate">
                 <li class="list-group-item" id="{id}">
                                <div class="input-group" id="">
								  <input class="form-control" name="product_specs[{key}][options][]" value="">
								 
								  <span class="input-group-addon btn btn-danger deleteOption" data-id="{id}"><i class="fa fa-times"></i></span>
								</div>
                  </li>
                </template>
                
                
                <template type="text/template" id="newSpecTemplate">
                <div class="col-md-4 col-lg-4" id="spec-{id}">
              
                <div class="panel panel-default" >
				  <div class="panel-heading">
				    <h3 class="panel-title pull-left"><input name="product_specs_new[]"></h3>
				   
					     <br class="clearfix">
				  </div>
				  <div class="panel-body">
				   Save the page to enable options
				  </div>
				  <div class="panel-footer">
				 <a class="pull-right deleteSpec " data-id="spec-{id}" href="javascript:void(0)">DELETE SPEC</a>
				 <br class="clearfix">
				 </div>
				</div>
                  </div>
                
                </template>
                
               
                
                <style type="text/css">
                	.sortable {
                		list-style-type: none; 
                		margin: 0; 
                		padding: 0; 
                		margin-bottom: 10px; 
                	}
                	.dragged .input-group { display:none; }
                	.dragged {
                		z-index:9999;
						position: relative;
						display: block;
						padding: 10px 15px;
						margin-bottom: -1px;
						background-color: rgba(238,162,54,0.5);
						border: 1px dashed #666;
                	}
                	.placeholder {
                		background-color: rgba(238,162,54,1);
						border: 1px dashed rgba(238,162,54,1);
						margin:4px;
					}
                	.sortable li {
                		cursor: ns-resize;
                	}
					.tile {
					    height: 100px;
					}
					.grid {
					    margin-top: 1em;
					}               		
          			.move {
          				cursor:ns-resize;
          			}
          			.expand {
          				cursor:pointer;
          			}
          			.specwrapper { margin-bottom:10px; }
				</style>
                <script type="text/javascript">


				jQuery(document).ready(function () {

					var panelList = $('#draggablePanelList');

			        panelList.sortable({
			            // Only make the .panel-heading child elements support dragging.
			            // Omit this to make then entire <li>...</li> draggable.
			            handle: '.move'
			           
			        });
					
					$( ".sortable" ).sortable();

					$('.editable').editable({
						  type: 'text',
						  title: 'Edit Spec',
						    success: function(response, newValue) {
								var orig = $(this).data('orig');

						    	jQuery('.'+$(this).data('pk')).each( 
						    			function() {
						    				
						    			    var name = $(this).attr('name');
						    			    
						    			    $(this).attr('name',name.replace( new RegExp(orig, 'g'), newValue)); 
						    			    console.log(name);
						    			}
						    	);
						    	$(this).data('orig', newValue);
						    }

						});

					jQuery('.deleteAllSpecs').click(function() {

						if(confirm('Are you sure you want to delete all the specs?')) {
							jQuery('.specwrapper').remove();

							}

						});
					
					
					jQuery('.addNewOption').click(function() {
					
						//ID OF THE LI get the parent UL and appended
						selector = '#' + jQuery(this).data('id');
						
						 var template = jQuery('#newOptionTemplate').html();
						 var container = jQuery(selector);	
						 tempid = Math.floor((Math.random() * 1000) + 1);
						 template = template.replace( new RegExp("{id}", 'g'), tempid);
						 template = template.replace( new RegExp("{key}", 'g'), jQuery(this).data('key'));
				         container.append(template);

				     	jQuery('#'+tempid + ' input').focus();	
						
						});

						jQuery( document ).on( "click", "span.deleteOption", function() {

						if(confirm('Are you super sure you want to do this????')) {
							jQuery('#'+ jQuery(this).data('id')).remove();
							}
						});
					

						jQuery( document ).on( "click", "a.deleteSpec", function() {

							if(confirm('Are you super sure you want to do this???? This will not update the products that have these stats')) {
								jQuery('#'+ jQuery(this).data('id')).remove();
								}
							});
						
					
					
					jQuery('.addNewSpec').click(function() {
						
						 var template = jQuery('#newSpecTemplate').html();
						 var container = jQuery('#NewSpecs');
						 tempid = Math.floor((Math.random() * 1000) + 1);
						 template = template.replace( new RegExp("{id}", 'g'),'tempspec'+ tempid);
						// template = template.replace( new RegExp("{key}", 'g'), jQuery(this).data('key'));
				         container.append(template);
				         container.fadeIn();

				         jQuery('#spec-tempspec'+tempid + ' input').focus();	

							
					});







					});



                </script>
                
                
                
                