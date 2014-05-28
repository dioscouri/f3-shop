<div class="clearfix">
    <div class="pull-right">
        <a class="btn btn-default" href="./admin/shop/orders/giftcards">Close</a>
    </div>
</div>
<!-- /.form-group -->

<hr />

<div class="row">
    <div class="col-md-9">
        <div class="well">
        
            <div class="row">
                <div class="col-md-4">
                    <div class="well well-sm well-light text-center"><h5><small>Code</small><br/><?php echo $item->code; ?></h5></div>
                </div>
                <div class="col-md-4">
                    <div class="well well-sm well-light text-center"><h5><small>Initial Value</small><br/><?php echo \Shop\Models\Currency::format( $item->initial_value ); ?></h5></div>
                </div>
                <div class="col-md-4">
                    <div class="well well-sm bg-color-darken txt-color-white text-center"><h5><small>Balance</small><br/><?php echo \Shop\Models\Currency::format( $item->balance() ); ?></h5></div>
                </div>
            </div>
            
            <hr/>
            
            <div class="row">
                <div class="col-md-2">
                    
                    <h3>History</h3>
                    <p class="help-block">The activity log for this gift card.</p>
                            
                </div>
                <!-- /.col-md-2 -->
                            
                <div class="col-md-10">
                    
                    <ul class="list-group">
                    <?php foreach ($item->history as $history) { ?>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-md-2">
                                    <?php echo \Dsc\ArrayHelper::get( $history, 'created.local' ); ?>
                                </div>
                                <div class="col-md-10">
                                    <?php $dump = $history; unset( $dump['created'] ); ?>
                                    <?php echo \Dsc\Debug::dump( $dump ); ?>
                                </div>
                            </div>
                        </li>
                    <?php } ?>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-md-2">
                                    <?php echo date( 'Y-m-d', $item->{'metadata.created.time'} ); ?>
                                </div>
                                <div class="col-md-10">
                                    Issued
                                </div>
                            </div>
                        </li>                    
                    </ul>
                    
                </div>
                <!-- /.col-md-10 -->
                
            </div>        
        
        </div>        
    </div>
    
    <div class="col-md-3">
        <h5>Actions to perform on this Gift Card</h5>
        <ul class="list-group">
            <li class="list-group-item">Resend gift card to purchaser</li>
        </ul>
    </div>
    
</div>
