<script>
dataLayer.push({
	'event': 'transaction',
    'transactionId': '<?php echo $this->order->number; ?>',
    'transactionTotal': <?php echo (float) $this->order->grand_total; ?>,
<?php if (!empty($this->order->tax_total)) { ?>    	    
    'transactionTax': <?php echo (float) $this->order->tax_total; ?>,
<?php } ?>
<?php if ($this->order->shipping_total - $this->order->shipping_discount_total > 0) { ?>
    'transactionShipping': <?php echo (float) $this->order->shipping_total - $this->order->shipping_discount_total; ?>,
<?php } ?>
    'transactionProducts': [
    <?php $n=0; foreach ($this->order->items as $item) { ?>
    <?php if ($n > 0) { echo ", "; } ?>
    {
        'sku': '<?php echo \Dsc\ArrayHelper::get($item, 'product.tracking.sku'); ?>',
        'name': '<?php echo \Dsc\ArrayHelper::get($item, 'product.title'); ?>',
        'price': <?php echo (float) \Dsc\ArrayHelper::get($item, 'price'); ?>,
        'quantity': <?php echo (int) \Dsc\ArrayHelper::get($item, 'quantity'); ?>
    }
    <?php $n++; } ?>
    ]
});
</script>