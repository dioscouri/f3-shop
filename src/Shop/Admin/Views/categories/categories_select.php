<?php if ($categories = \Shop\Models\Categories::find()) { ?>
<div>

<select name="category_ids[]" class="select2" >

    <?php $current = \Dsc\ArrayHelper::getColumn( (array) $flash->old('categories'), 'id' ); ?>
    <?php foreach ($categories as $one) { ?>
    <option value="<?php echo $one->_id; ?>" <?php if (in_array($one->_id, $current)) { echo "selected='selected'"; } ?>>
        <?php echo @str_repeat( "&ndash;", substr_count( @$one->path, "/" ) - 1 ) . " " . $one->title; ?>
    </option>
    <?php } ?> 
    </select>
</div>
<?php } ?>