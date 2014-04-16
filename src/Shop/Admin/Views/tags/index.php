<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-7">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-table fa-fw "></i> Tags <span> > List </span>
        </h1>
    </div>
    <div class="col-xs-12 col-sm-5 col-md-5 col-lg-5">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
                <form class="searchForm" method="post" action="./admin/shop/tags">
                    <div class="form-group">
                        <div class="input-group">
                            <input class="form-control" type="text" name="filter[keyword]" placeholder="Search..." maxlength="200" value="<?php echo $state->get('filter.keyword'); ?>"> <span class="input-group-btn"> <input class="btn btn-primary" type="submit"
                                onclick="this.form.submit();" value="Search"
                            />
                                <button class="btn btn-danger" type="button" onclick="Dsc.resetFormFilters(this.form);">Reset</button>
                            </span>
                        </div>
                    </div>
                </form>
            </div>        
            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                <a class="btn btn-default" href="./admin/shop/tag/create">Add New</a>
            </div>
        </div>
    </div>
</div>

<div class="widget">
    <div class="widget-content">
        <ul class='tag-cloud list-unstyled list-inline'>
            <?php
            if (! empty( $tags ) && is_array( $tags ))
            {
                foreach ( $tags as $tag )
                {
                    ?>
                    <li>
                        <div class="form-group"><a href="./admin/shop/tag/edit/<?php echo $tag; ?>" class="btn btn-default" rel="tag"><?php echo $tag . " (" . (int) $tag->productCount( (string) $tag ) . ")"; ?></a></div>
                    </li>
    				<?php
                }
            }
            ?>
		</ul>
    </div>
</div>
