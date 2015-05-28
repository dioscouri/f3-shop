<?php 
$attribs = $flash->old('specs');

foreach ($flash->old('categories') as $cat) : 

$category = (new \RallyShop\Models\Categories)->setCondition('_id', new \MongoId($cat['id']))->getItem();

?>

<div class="row">
<h2><?php echo $category->title; ?></h2>
<?php if(!empty($category->product_specs))  :


$specs = $category->product_specs;

ksort ($specs);

foreach($specs as $key => $value) : ?>

<div class="form-group col-lg-4 <?php if(!empty($value['required'])) { echo 'required'; }  ?>">
<h3><?php echo $key; ?> <?php if(!empty($value['required'])) { echo '<span class="requiredIcon">*</span>'; }  ?></h3>
<?php $options = array();
$options[]  = array('text' => 'Select One', 'value'=> '');

if(@is_array($value['options'])) {
foreach($value['options'] as $option) {



$options[] = array('text' => $option, 'value'=> $option);
}   
}

$cur = '';
if(isset($attribs)) {

if(!empty($attribs[$key])) {
$cur = $attribs[$key];
unset($attribs[$key]);
}

if(array_key_exists ($key, $attribs)) {
	unset($attribs[$key]);
}
}


?>

				<select class="categoryValue form-control "
					<?php if(!empty($value['required'])) { echo 'required="required"'; }  ?>
					name="specs[<?php echo $key; ?>]">
		


 <?php echo \Dsc\Html\Select::options($options, $cur); ?>
</select>
</div>
<?php endforeach; endif; ?>
</div>
<?php 


endforeach;

?>



<div class="row">
    <div class="col-lg-12">
    
        <h3>Specs</h3>
        <p class="help-block">Specs are Key value pairs that are useful for customers</p>
         <a href="javascript:void(0);" id="addNewSpec" class="btn btn-success pull-right">Add New Spec</a>  	     
    </div>
</div>

    <!-- /.col-md-2 -->
        <div class="row">     
<div class="col-lg-12" id="specs">	
		 <!-- /.form-group -->
        
        <?php if(isset($attribs)) :?>
		<?php $i=0; foreach ($attribs as $key => $value) :?>
			<div class="row">		
			    	
					
			        <div class="form-group" id="spec<?php echo $i;?>">
			               <label for="specs[<?php echo $key; ?>]" class="col-sm-4 control-label"><strong><?php echo $key; ?></strong></label>
			               <div class="col-sm-6">
				     			 <input type="text" id="specs[<?php echo $key; ?>]" name="specs[<?php echo $key; ?>]" value="<?php echo $value; ?>" class="form-control" >
				    	   </div>
				    	   <div class="col-sm-2">
				    	     <a class="remove-image btn btn-xs btn-danger pull-left" onclick="jQuery('#spec<?php echo $i;?>').remove();" href="javascript:void(0);">
				               			 <i class="fa fa-times"></i>
				            	 </a>
			    		   </div>
			         </div>
			
			    </div>
			   
<hr>   
        <?php $i++; endforeach; ?>
        <?php endif; ?>
</div>  
    </div>
	
	<template type="text/template" id="add-spec-template">
			<div class="row" id="spec{id}">		
			        <div class="form-group" >
			               <div class="col-sm-4">
				     			 <input type="text" name="specs[{id}][key]" value="" placeholder="Name of spec" class="form-control" >
				    	   </div>
			               <div class="col-sm-6">
				     			 <input type="text" name="specs[{id}][value]" value="" placeholder="Value of spec"  class="form-control" >
				    	   </div>
				    	   <div class="col-sm-2">
				    	     <a class="remove-image btn btn-xs btn-danger pull-left" onclick="jQuery('#spec{id}').remove();" href="javascript:void(0);">
				               			 <i class="fa fa-times"></i>
				            	 </a>
			    		   </div>
			         </div>
			      <hr>
			   </div>  
		   
	</template>
     
    	<script>
				 window.specs_count = <?php  echo count( $flash->old('specs') ); ?>;
			        jQuery('#addNewSpec').click(function(){
			            var container = jQuery('#specs');
			            var template = jQuery('#add-spec-template').html();
			            template = template.replace( new RegExp("{id}", 'g'), window.specs_count);
			            container.append(template);
			            window.specs_count = window.specs_count + 1;
		                      
			        });
    	</script>
