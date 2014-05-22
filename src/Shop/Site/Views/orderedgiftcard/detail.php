<div class="container giftcard-detail">

    <div class="clearfix">
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
                        <a href="javascript::void(0);" onclick="jQuery('#email-giftcard-form').slideToggle();">
                            <i class="fa fa-lg fa-fw fa fa-envelope"></i>
                            Email
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div id="email-giftcard-form" style="display: none;">
            <div class="row">
                <div class="col-md-4 col-md-offset-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title text-center">Email your gift card to a friend</h3>
                        </div>                    
                        <div class="panel-body">
                            <form action="./shop/giftcard/email/<?php echo $giftcard->id; ?>/<?php echo $giftcard->token; ?>" method="post" role="form">
                                <div class="form-group">
                                    <label class="sr-only">Your Name</label>
                                    <input type="text" name="sender_name" value="" class="form-control" placeholder="Your Name" required />
                                </div>
                                <div class="form-group">
                                    <label class="sr-only">Your Email</label>
                                    <input type="email" name="sender_email" value="" class="form-control" placeholder="Your Email" required />
                                </div>                                
                                <div class="form-group">
                                    <label class="sr-only">Recipient Name</label>
                                    <input type="text" name="recipient_name" value="" class="form-control" placeholder="Recipient Name" required />
                                </div>
                                <div class="form-group">
                                    <label class="sr-only">Recipient Email</label>
                                    <input type="email" name="recipient_email" value="" class="form-control" placeholder="Recipient Email" required />
                                </div>
                                <div class="form-group">
                                    <label>Message to Recipient <small>(optional)</small></label>
                                    <textarea name="message" class="form-control"></textarea>
                                </div>
                                <button type="submit" class="btn btn-default">Send</button>                
                            </form>
                        </div>
                    </div>
                </div>
            </div>    
        </div>
        
        <div class="margin-top text-center">
            <a href="./shop" class="btn btn-default custom-button">Start Shopping</a>
        </div>    
    </div>

</div>