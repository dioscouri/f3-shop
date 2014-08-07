<?php 
if (!$currencies = (new \Shop\Models\Currencies)->getItems()) 
{
    $currencies = array(
        new \Shop\Models\Currencies(array(
            'code' => 'USD',
            'title' => 'United States Dollar'
        ))
    );
}
$options = array();
foreach ($currencies as $currency) 
{
    $options[] = array(
        'id' => $currency->code,
        'value' => $currency->code,
        'text' => $currency->code .' - ' . $currency->title
    );
}
?>

<h4>Currency Settings</h4>
<hr />
<p class="alert alert-info">
    Your currency exchange rates will be pulled nightly from:
    <a href="https://openexchangerates.org" target="_blank">https://openexchangerates.org</a>
    so please sign up for a FREE account with them now. Provide the API ID below.
</p>

<div class="row">
    <div class="col-md-12">
    
        <div class="form-group">
            <label>Default Currency for Display</label>
            <select name="currency[default]" class="form-control">
                <?php
                echo Dsc\Html\Select::options($options, $flash->old('currency.default'));
                ?>            
            </select>
            <p class="help-block">
            The default currency used for displaying prices to the customer.
            </p>
        </div>
        <!-- /.form-group -->    
    
        <div class="form-group">
            <label>Currency used in Database</label>
            <select name="currency[database]" class="form-control">
                <?php
                echo Dsc\Html\Select::options($options, $flash->old('currency.database'));
                ?>            
            </select>
            <p class="help-block">
            All monetary values in the database (product prices, order amounts, etc) are assumed to be in this currency and this currency will be used as the basis for all conversions when a customer selects a different currency for display.
            </p>
        </div>
        <!-- /.form-group -->
        
        <div class="form-group">
            <label>Open Exchange Rates API ID</label>
            <input name="currency[openexchangerates_api_id]" placeholder="Get this from https://openexchangerates.org" value="<?php echo $flash->old('currency.openexchangerates_api_id'); ?>" class="form-control" type="text" />
        </div>
        <!-- /.form-group -->
        
        <div class="form-group">
            <label>Enabled Currencies</label>
            <input name="currency[enabled_currencies]" value="<?php echo implode( ",", (array) $flash->old('currency.enabled_currencies') ); ?>" class="ui-select2-data" data-data='<?php echo json_encode( $options ); ?>' data-multiple="true" data-maximum="0" />
        </div>
        <!-- /.form-group -->        
        

    </div>
    <!-- /.col-md-12 -->
    
</div>
<!-- /.row -->

