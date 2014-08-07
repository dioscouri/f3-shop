<div class="well">

    <form id="settings-form" role="form" method="post" class="clearfix">

        <div class="clearfix">
            <button type="submit" class="btn btn-primary pull-right">Save Changes</button>
        </div>

        <hr />

        <div class="row">
            <div class="col-md-3 col-sm-4">
                <ul class="nav nav-pills nav-stacked">
                    <li class="active">
                        <a href="#tab-default" data-toggle="tab"> Default Settings </a>
                    </li>            
                                         
                <?php if (!empty($this->event)) { foreach ((array) $this->event->getArgument('tabs') as $key => $title ) { ?>
                    <li>
                        <a href="#tab-<?php echo $key; ?>" data-toggle="tab"> <?php echo $title; ?> </a>
                    </li>
                <?php } } ?>                
            </ul>
            </div>

            <div class="col-md-9 col-sm-8">

                <div class="tab-content stacked-content">

                    <div class="tab-pane fade in active" id="tab-default">
                        <?php echo $this->renderLayout('Shop/Admin/Views::settings/currencies/default.php'); ?>
                    </div>
               
                <?php if (!empty($this->event)) { foreach ((array) $this->event->getArgument('content') as $key => $content ) { ?>
                <div class="tab-pane fade in" id="tab-<?php echo $key; ?>">
                    <?php echo $content; ?>
                </div>
                <?php } } ?>

            </div>

            </div>
        </div>

    </form>

</div>
