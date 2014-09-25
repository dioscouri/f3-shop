<div class="reviews-form">
    <div class="panel-group">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" href="#create-review-form-<?php echo $review_key; ?>"> Write a Review </a>
                </h4>
            </div>
            <div id="create-review-form-<?php echo $review_key; ?>" class="panel-collapse collapse">
                <div class="panel-body">
                    <form class="" action="./shop/product/<?php echo $item->slug; ?>/review" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <div class="row">
                                <label class="control-label col-sm-3">Overall rating</label>
                                <div class="col-sm-2">
                                    <select name="rating" class="form-control">
                                        <?php 
                                        echo \Dsc\Html\Select::options(array(
                                            array('value'=>'1', 'text'=>'1'),
                                            array('value'=>'2', 'text'=>'2'),
                                            array('value'=>'3', 'text'=>'3'),
                                            array('value'=>'4', 'text'=>'4'),
                                            array('value'=>'5', 'text'=>'5 (best)'),
                                        ), 1); 
                                        ?>                                
                                    </select> 
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="row">
                                <label class="control-label col-sm-3">Give it a title</label>
                                <div class="col-sm-9">
                                    <input name="title" type="text" class="form-control">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="row">
                                <label class="control-label col-sm-3">Now the details</label>
                                <div class="col-sm-9">
                                    <textarea name="description" class="form-control" rows="5"></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="row">
                                <label class="control-label col-sm-3">Add up to 3 photos</label>
                                <div class="col-sm-9">
                                    <p><input type="file" name="image_1" class="form-control" /></p>
                                    <p><input type="file" name="image_2" class="form-control" /></p>
                                    <p><input type="file" name="image_3" class="form-control" /></p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-offset-3 col-sm-9">
                                    <input type="submit" class="btn btn-primary" value="Submit"> 
                                </div>
                            </div>
                        </div>                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>