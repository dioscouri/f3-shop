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
                    <h2 class="text-center"><span class="label-lg label <?php echo $item->credit_issued_to_user ? 'label-default' : 'label-warning'; ?>"><?php echo $item->credit_issued_to_user ? 'Issued' : 'Not Issued'; ?></span></h2>
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
                    
                    <?php echo $item->customerName(); ?>
                    
                </div>
                <!-- /.col-md-10 -->
                
            </div>
            
            <hr/>
            
            <div class="row">
                <div class="col-md-2">
                    
                    <h3>History</h3>
                            
                </div>
                <!-- /.col-md-2 -->
                            
                <div class="col-md-10">
                    
                    <div class="form-group">
                        <label>
                            Created
                        </label>
                        <?php echo date( 'Y-m-d', $item->{'metadata.created.time'} ); ?>
                    </div>
                    
                </div>
                <!-- /.col-md-10 -->
                
            </div>
        
        </div>        
    </div>
    
    <div class="col-md-3">
        <ul class="list-group">
            <li class="list-group-item">
                <a class="btn btn-success" href="./admin/shop/credit/issue/<?php echo $item->id; ?>">Issue credit</a>
            </li>
            <li class="list-group-item">
                <a class="btn btn-danger" href="./admin/shop/credit/revoke/<?php echo $item->id; ?>">Revoke credit</a>
            </li>
        </ul>        
    </div>
    
</div>
