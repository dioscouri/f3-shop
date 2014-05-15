<div class="container">
    <ol class="breadcrumb">
        <li>
            <a href="./shop/account">My Account</a>
        </li>
        <li>
            <a href="./shop/account/addresses">Address Book</a>
        </li>
        <li class="active">Edit Address</li>
    </ol>
    
    <form method="post" id="address-form">
    
        <?php echo $this->renderLayout('Shop/Site/Views::addresses/fields_basics.php'); ?>
    
        <div class="input-group form-group">
            <button type="submit" class="btn btn-primary">Submit</button>
            <a class="btn btn-link" href="./shop/account/addresses">Cancel</a>
        </div>
    
    </form>

</div>