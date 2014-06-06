<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="fa fa-table fa-fw "></i> 
				Reports 
			<span> > 
				List
			</span>
		</h1>
	</div>
	<div class="col-xs-12 col-sm-5 col-md-5 col-lg-8">
        <ul id="sparks" class="list-actions list-unstyled list-inline">
            <li>
                
            </li>
        </ul>            	
	</div>
</div>

<?php foreach ($grouped as $title=>$items) { ?>
    
    <hr/>
    
    <h3><?php echo ucwords($title); ?></h3>
    
    <?php $n=0; $count = count($items); ?>
    <?php foreach ($items as $position=>$item) { ?>
    
        <?php if ($n == 0 || ($n % 4 == 0)) { ?><div class="row"><?php } ?>
            
        <div class="col-xs-12 col-sm-6 col-md-3 text-center">
            
            <a class="btn btn-default btn-block" href="./admin/shop/reports/<?php echo $item->slug; ?>">
                
                <h4>
                    <?php if ($item->icon) { ?>
                    <i class="<?php echo $item->icon; ?>"></i>
                    <?php } ?>
                    <div class="">
                        <?php echo $item->name; ?>
                    </div>
                </h4>            
            </a>
    
        </div>
             
        <?php $n++; if (($n % 4 == 0) || $n==$count) { ?></div> <br/><?php } ?>         
    
    <?php } ?>
    
<?php } ?>
