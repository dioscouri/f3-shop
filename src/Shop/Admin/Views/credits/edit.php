<div class="clearfix">
    <div class="pull-right">
        <a class="btn btn-default" href="./admin/shop/credits">Close</a>
    </div>
</div>
<!-- /.form-group -->

<hr />

<div class="row">
    <div class="col-md-9">
        <div class="well">
        
            <div class="row">
                <div class="col-md-3">
                    <h2 class="text-center"><span class="label-lg label <?php echo $item->credit_issued_to_user ? 'label-success' : 'label-warning'; ?>"><?php echo $item->credit_issued_to_user ? 'Issued' : 'Not Issued'; ?></span></h2>
                </div>            
                <div class="col-md-3">
                    <div class="well well-sm well-light text-center"><h5><small>Amount</small><br/><?php echo \Shop\Models\Currency::format( $item->amount ); ?></h5></div>
                </div>
                <div class="col-md-3">
                    <div class="well well-sm bg-color-darken txt-color-white text-center"><h5><small>Balance before:</small><br/><?php echo \Shop\Models\Currency::format( $item->balance_before ); ?></h5></div>
                </div>
                <div class="col-md-3">
                    <div class="well well-sm bg-color-darken txt-color-white text-center"><h5><small>Balance after:</small><br/><?php echo \Shop\Models\Currency::format( $item->balance_after ); ?></h5></div>
                </div>
            </div>
            
            <hr/>
            
            <div class="row">
                <div class="col-md-2">
                    
                    <h3>Customer</h3>
                            
                </div>
                <!-- /.col-md-2 -->
                            
                <div class="col-md-10">
                    
                    <p><?php echo $item->customerName(); ?></p>
                    <p><?php echo $item->user()->email; ?></p>
                    
                </div>
                <!-- /.col-md-10 -->
                
            </div>
            
            <hr/>
            
            <?php if (!empty($item->message)) { ?>
            
            <div class="row">
                <div class="col-md-2">
                    
                    <h3>Message</h3>
                            
                </div>
                <!-- /.col-md-2 -->
                            
                <div class="col-md-10">
                    
                    <?php echo $item->message; ?>
                    
                </div>
                <!-- /.col-md-10 -->
                
            </div>
            
            <hr/>   
                     
            <?php } ?>
            
            <?php if (!empty($item->order_id)) { ?>
            
            <div class="row">
                <div class="col-md-2">
                    
                    <h3>Order</h3>
                            
                </div>
                <!-- /.col-md-2 -->
                            
                <div class="col-md-10">
                    
                    <p>Used in order #<a href="./admin/shop/order/edit/<?php echo $item->order_id; ?>"><?php echo $item->order_id; ?></a></p>
                    
                </div>
                <!-- /.col-md-10 -->
                
            </div>
            
            <hr/>   
                     
            <?php } ?>
            
            <?php if (!empty($item->referral_id)) { ?>
            
            <div class="row">
                <div class="col-md-2">
                    
                    <h3>Referral</h3>
                            
                </div>
                <!-- /.col-md-2 -->
                            
                <div class="col-md-10">
                    
                    <p>Associated with referral #<a href="./admin/affiliates/referral/edit/<?php echo $item->referral_id; ?>"><?php echo $item->referral_id; ?></a></p>
                    
                </div>
                <!-- /.col-md-10 -->
                
            </div>
            
            <hr/>   
                     
            <?php } ?>
            
            <div class="row">
                <div class="col-md-2">
                    
                    <h3>History</h3>
                            
                </div>
                <!-- /.col-md-2 -->
                            
                <div class="col-md-10">
                    
                    <div class="form-group">
                        <ul class="list-group">
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-md-2">
                                        <?php echo date( 'Y-m-d H:i:s', $item->{'metadata.created.time'} ); ?>
                                    </div>
                                    <div class="col-md-10">
                                        Created
                                    </div>
                                </div>
                            </li>                        
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
                        </ul>                    
                    </div>
                    
                </div>
                <!-- /.col-md-10 -->
                
            </div>
        
        </div>        
    </div>
    
    <div class="col-md-3">
        <ul class="list-group">
            <?php if (empty($item->credit_issued_to_user)) { ?>
            <li class="list-group-item">
                <a class="btn btn-success" href="./admin/shop/credit/issue/<?php echo $item->id; ?>">Issue credit</a>
            </li>
            <?php } else { ?>
            <li class="list-group-item">
                <a class="btn btn-warning" href="./admin/shop/credit/revoke/<?php echo $item->id; ?>">Revoke credit</a>
            </li>
            <?php } ?>
            <li class="list-group-item">
                <a class="btn btn-danger" href="./admin/shop/credit/delete/<?php echo $item->id; ?>">Delete credit record</a>
            </li>            
        </ul>        
    </div>
    
</div>
