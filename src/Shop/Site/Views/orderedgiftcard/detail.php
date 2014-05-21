<div class="container giftcard-detail">

    <div class="text-center">
        <h2 class="margin-top">Here is your gift card!</h2>
        <h3 class="margin-top">Code: <?php echo $giftcard->code; ?></h3>
        <h4 class="margin-top">Balance: <?php echo \Shop\Models\Currency::format( $giftcard->balance() ) ?></h4>
        
        <div class="row margin-top">
            <div class="col-sm-2 col-sm-offset-4">
                <div class="form-group">
                    <a href="./shop/giftcard/print/<?php echo $giftcard->id; ?>/<?php echo $giftcard->token; ?>">
                        <i class="fa fa-lg fa-fw fa fa-print"></i>
                        Print
                    </a>
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group">
                    <a href="javascript:void(0);">
                        <i class="fa fa-lg fa-fw fa fa-envelope"></i>
                        Email
                    </a>
                </div>
            </div>
        </div>
        
        <div class="">
            [display this form only after clicking Email]
            <form class="form-inline" role="form">
                <div class="form-group">
                    <label class="sr-only">Your Name</label>
                    <input type="text" name="sender_name" value="" class="form-control" placeholder="Your Name" />
                </div>
                <div class="form-group">
                    <label class="sr-only">Recipient Name</label>
                    <input type="text" name="recipient_name" value="" class="form-control" placeholder="Recipient Name" />
                </div>
                <div class="form-group">
                    <label class="sr-only">Recipient Email</label>
                    <input type="text" name="recipient_email" value="" class="form-control" placeholder="Recipient Email" />
                </div>
                <button type="submit" class="btn btn-default">Send</button>
            </form>        
        </div>
        
        <div class="margin-top">
            <a href="./shop" class="btn btn-default custom-button">Start Shopping</a>
        </div>    
    </div>

</div>