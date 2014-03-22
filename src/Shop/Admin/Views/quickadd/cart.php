<form class="form-horizontal" action="./admin/menu/create" method="post">
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
			<input type="hidden" name="details[type]" value="shop-cart" />
			<input type="hidden" name="details[url]" value="./shop/cart" />
		</div>
	</div>
</form>