<form class="form-horizontal" action="/admin/menu/create" method="post">
	<div class="form-group">
		<label for="link-url" class="col-sm-4 control-label">Collection</label>
		<div class="col-sm-8">
			<?php if ($__items = \Shop\Models\Collections::find()) { ?>
			<select name="details[url]" class="form-control">
			<?php foreach ($__items as $__item) { ?>
			    <option value="/shop/collection/<?php echo $__item->slug; ?>">
			    	<?php echo $__item->title; ?>
			    </option>                    
			<?php } ?> 
			</select>
			<?php } ?>   
		</div>
	</div>
	<div class="form-group">
		<label for="link-text" class="col-sm-4 control-label">Link Text</label>
		<div class="col-sm-8">
			<input type="text" class="form-control" id="link-title"
				placeholder="Title of Menu Item" name="title">
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-4 col-sm-8">
			<button type="submit" class="btn btn-default">Add to Menu</button>
			<input type="hidden" name="tree" value="<?php echo $tree; ?>" />
			<input type="hidden" name="details[type]" value="shop-collection" />
		</div>
	</div>
</form>