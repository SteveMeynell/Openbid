<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-jumbotron" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
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
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-jumbotron" class="form-horizontal">
            <div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $text_description; ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
            <div class="form-group">
            <label class="col-sm-2 control-label" for="input-jumbo-heading"><?php echo $entry_heading; ?></label>
            <div class="col-sm-10">
              <input type="text" name="jumbotron_heading" value="<?php echo $jumbotron_heading; ?>" placeholder="<?php echo $entry_heading; ?>" id="input-jumbo-heading" class="form-control" />
              <?php if ($error_heading) { ?>
              <div class="text-danger"><?php echo $error_heading; ?></div>
              <?php } ?>
            </div>
          </div>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="input-jumbo-option"><?php echo $entry_option; ?></label>
            <div class="col-sm-10">
              <select name="jumbotron_option" id="input-jumbo_option" class="form-control">
                <?php if ($jumbotron_option) { ?>
                <option value="1" selected="selected"><?php echo $text_most_viewed; ?></option>
                <option value="0"><?php echo $text_random; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_most_viewed; ?></option>
                <option value="0" selected="selected"><?php echo $text_random; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-jumbo-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="jumbotron_status" id="input-jumbo_status" class="form-control">
                <?php if ($jumbotron_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>