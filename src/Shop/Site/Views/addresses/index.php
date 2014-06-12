<div class="container">
    <ol class="breadcrumb">
        <li>
            <a href="./shop/account">My Account</a>
        </li>
        <li class="active">Address Book</li>
    </ol>
    
    <div class="row">
        <div class="col-xs-2 col-sm-2 col-md-2">
            <a class="btn btn-info" href="./shop/account/addresses/create">New Address</a>
        </div>
        <div class="col-xs-10 col-sm-10 col-md-10">
            <?php /* ?>
            <form action="./shop/account/addresses" method="post">
            <div class="input-group">
                <input class="form-control" type="text" name="filter[keyword]" placeholder="Search..." maxlength="200" value="<?php echo $state->get('filter.keyword'); ?>"> 
                <span class="input-group-btn">
                    <input class="btn btn-primary" type="submit" onclick="this.form.submit();" value="Search" />
                    <button class="btn btn-danger" type="button" onclick="Dsc.resetFormFilters(this.form);">Reset</button>
                </span>
            </div>
            </form>
            */ ?>
        </div>
    </div>
    
    <hr/>
    
    <?php if (empty($paginated->items)) { ?>
        <p>No addresses found.</p>
    <?php } ?>
    
    <?php $n=0; $count = count($paginated->items); ?>
    
    <?php foreach ($paginated->items as $item) { ?>
        
        <?php if ($n == 0 || ($n % 4 == 0)) { ?><div class="row"><?php } ?>
        
        <div class="col-xs-6 col-sm-6 col-md-3 category-article category-grid">
            
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <a class="btn btn-default btn-sm" href="./shop/account/addresses/edit/<?php echo $item->id; ?>">Edit</a>
                    
                    <a class="btn btn-xs btn-danger pull-right" data-bootbox="confirm" href="./shop/account/addresses/delete/<?php echo $item->id; ?>">
                        <i class="fa fa-times"></i>
                    </a>                                        
                </div>
                <div class="panel-body">
                    <address><?php echo $item; ?></address>
                    <?php echo $item->phone_number; ?>
                </div>                
                <div class="panel-footer">
                    <?php if (!empty($item->primary_billing)) { ?>
                    <div class="alert alert-info">
                        <i class="fa fa-star"></i> Default Billing
                    </div>
                    <?php } ?>
                    <?php if (!empty($item->primary_shipping)) { ?>
                    <div class="alert alert-success">
                        <i class="fa fa-star"></i> Default Shipping
                    </div>
                    <?php } ?>
                                    
                    <?php if (empty($item->primary_billing)) { ?>
                    <a class="btn btn-link" href="./shop/account/address/setprimarybilling/<?php echo $item->id; ?>"><small>Set as default billing</small></a>
                    <?php } ?>
                    <?php if (empty($item->primary_shipping)) { ?>
                    <a class="btn btn-link" href="./shop/account/address/setprimaryshipping/<?php echo $item->id; ?>"><small>Set as default shipping</small></a>
                    <?php } ?>                
                </div>
            </div>
    
        </div>
        
        <?php $n++; if (($n % 4 == 0) || $n==$count) { ?></div><?php } ?>
        
    <?php } ?>

</div>