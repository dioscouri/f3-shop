<h3 class="">User Settings</h3>
<hr />

<div class="form-group">
    <label>Default Shopper Group</label>
    <select name="users[default_group]" class="form-control">
        <?php foreach ($groups = \Users\Models\Groups::find() as $group) { ?>
        <option value="<?php echo (string) $group->id; ?>" <?php if ($flash->old('users.default_group') == (string) $group->id) { echo "selected='selected'"; } ?>><?php echo $group->title; ?></option>
        <?php } ?>
    </select>                        
</div>
<!-- /.form-group -->                    
