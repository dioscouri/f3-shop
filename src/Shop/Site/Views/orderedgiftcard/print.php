<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js">
<!--<![endif]-->

<head>
    <?php echo $this->renderView('Theme/Views::head.php'); ?>
</head>

<body class="print">

<div class="container giftcard-detail">

    <div class="clearfix">
        <div class="pull-right hidden-print">
            <a class="btn btn-link" href="./shop/giftcard/<?php echo $giftcard->id; ?>/<?php echo $giftcard->token; ?>"><i class="fa fa-chevron-left"></i> Return to Gift Card</a>
        </div>
    </div>
        
    <div class="text-center panel panel-default">
        <h2 class="margin-top">Here is your gift card!</h2>
        <h3 class="margin-top">Code: <?php echo $giftcard->code; ?></h3>
        <h4 class="margin-top">Balance: <?php echo \Shop\Models\Currency::format( $giftcard->balance() ) ?></h4>
    </div>

</div>

</body>

</html>