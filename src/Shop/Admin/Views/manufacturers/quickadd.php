<div class="well">

    <h3>Add New Manufacturer</h3>

    <div id="quick-form-response-container"></div>

    <form id="quick-form" action="./admin/shop/manufacturer/create" class="form dsc-ajax-form" method="post" data-callback="Dsc.refreshDatatable" data-message_container="quick-form-response-container" data-refresh_list="true" data-list_container="manufacturers">

        <div class="form-group">
            <input type="text" name="title" placeholder="Title" class="form-control" />
        </div>
        <!-- /.form-group -->

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Create</button>
        </div>
        <!-- /.form-group -->
    </form>

</div>
<!-- /.well -->