<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>

  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>

    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" form="form-backup" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>

    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-exchange"></i> <?php echo $heading_title; ?></h3>
      </div>

      <div class="tab-content panel-body">
        <?php if ($erasepermission == true) { ?>
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-csv" data-toggle="tab"><?php echo $tab_csv; ?></a></li>
            <li><a href="#tab-truncate" data-toggle="tab"><?php echo $tab_truncate; ?></a></li>
          </ul>
        <?php } ?>

        <div class="tab-pane active" id="tab-csv">
          <div class="col-xs-12">
            <form action="<?php echo $csv_import; ?>" method="post" enctype="multipart/form-data" id="csv_import" class="form-horizontal form ed">
              <div class="form-group well">
                  <label class="col-sm-3 col-md-1 control-label" for="input-import"><?php echo $entry_import; ?></label>

                  <div class="col-sm-5 col-md-9">
                    <span>
                      <input data-toggle="tooltip" title="<?php echo $entry_import_info ?>" data-placement="right" class="btn btn-default" type="file" name="csv_import" id="input-csv_import" />
                    </span>
                  </div>

                  <div class="col-sm-3 col-md-2" style="padding-right:0">
                    <button type="submit" form="csv_import" data-toggle="tooltip" title="<?php echo $button_import_info; ?>" class="btn btn-primary pull-right">
                      <i class="fa fa-upload"></i> <?php echo $button_import; ?>
                    </button>
                  </div>

              </div>
            </form><br/>

            <form action="<?php echo $csv_export; ?>" method="post" enctype="multipart/form-data" id="csv_export" class="form-horizontal form ed">
              <div class="form-group well">
                  <label class="col-sm-3 col-md-1 control-label"><?php echo $entry_export; ?></label>

                  <div class="col-sm-4 col-md-3" data-toggle="tooltip" title="<?php echo $entry_export_info ?>">
                    <div class="input-group">
                      <span class="input-group-addon">
                          Table:
                      </span>
                      <select class="form-control" name="csv_export">
                      <?php foreach ($tables as $table) { ?>
                          <option value="<?php echo $table; ?>" /><?php echo $table; ?></option>
                      <?php } ?>
                      </select>
                    </div>
      	          </div>

                  <div class="col-sm-2 col-md-2" data-toggle="tooltip" title="<?php echo $entry_separator_help ?>">
                    <div class="input-group">
                      <span class="input-group-addon">
                          Separ.:
                      </span>
                      <select class="form-control" name="csv_separator">
                          <option value=";">;</option>
                          <option value=",">,</option>
                      </select>
                    </div>
                  </div>

                  <div class="col-sm-1 col-md-2" data-toggle="tooltip" title="<?php echo $entry_from_record_help ?>">
                    <div class="input-group">
                      <span class="input-group-addon">
                          <?php echo $entry_from_record; ?>
                      </span>
                      <input class="form-control" type="text" name="csv_from_record" value="0" size="5" id="csv_from_record" />
                    </div>
                  </div>

                  <div class="col-sm-2 col-md-2" data-toggle="tooltip" title="<?php echo $entry_number_of_record_help ?>">
                    <div class="input-group">
                      <span class="input-group-addon">
                          Rows:
                      </span>
                      <input class="form-control" type="text" name="csv_number_of_record" value="0" size="5" id="csv_number_of_record" />
                    </div>
                  </div>

                  <div class="col-sm-12 col-md-2" style="padding-right:0">
      		            <button type="submit" form="csv_export" class="btn btn-primary pull-right" data-toggle="tooltip" title="<?php echo $button_export_info; ?>">
                          <i class="fa fa-download"></i> <?php echo $button_export; ?>
                      </button>
                  </div>

              </div>

            </form>

      		</div>
        </div>

        <?php if ($erasepermission == true) { ?>
        <div class="tab-pane" id="tab-truncate">
          <form action="<?php echo $csv_erase; ?>" method="post" enctype="multipart/form-data" id="csv_erase" class="form-horizontal well">
            <div class="form-group">
              <label class="col-xs-1 control-label"><?php echo $entry_erase; ?></label>

              <div class="col-xs-9" data-toggle="tooltip" title="<?php echo $entry_erase_info ?>">
                <div class="input-group">
                  <span class="input-group-addon">Table:</span>
                  <select name="csv_erase" class="form-control">
                  <?php foreach ($tables as $table) { ?>
                      <option value="<?php echo $table; ?>" /><?php echo $table; ?></option>
                  <?php } ?>
                  </select>
                </div>
              </div>

              <div class="col-xs-2 text-right">
                 <button type="submit" form="csv_erase" data-toggle="tooltip" title="<?php echo $button_erase_info; ?>" class="btn btn-danger"><i class="fa fa-download"></i> <?php echo $button_erase; ?></button>
              </div>

            </div>
          </form>

          <div class="text-center"><b><?php echo $info_erase; ?></b></div>
        </div>
        <?php } ?>

      </div>

      <div class="col-sm-12 hidden control-label text-danger"><br/><?php echo $entry_backup_reminder; ?></div>
    </div>
  </div>
</div>

<?php if ($erasepermission == true) { ?>
<script type="text/javascript">
  if (window.location.hash == '#tab-truncate') {
      //alert(window.location.hash);
      $("ul.nav.nav-tabs li:first").removeClass('active');
      $("ul.nav.nav-tabs li:nth-child(2)").addClass('active');

      $('#tab-csv').removeClass('active');
      $('#tab-truncate').addClass('active');
  };
</script>
<?php } ?>
<?php echo $footer; ?>
