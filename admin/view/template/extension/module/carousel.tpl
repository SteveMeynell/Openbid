<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-carousel" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <label class="panel-title">This will be a description of what the carousel module will do and how to set up each carousel.</label>
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-carousel" class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-name"><?php echo $entry_name; ?></label>
            <div class="col-sm-10">
              <input type="text" name="name" value="<?php echo $name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
              <?php if ($error_name) { ?>
              <div class="text-danger"><?php echo $error_name; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-heading-text"><?php echo $entry_heading_text; ?></label>
            <div class="col-sm-10">
              <input type="text" name="heading_text" value="<?php echo $heading_text; ?>" placeholder="<?php echo $entry_heading_text; ?>" id="input-heading-text" class="form-control" />
              <?php if ($error_heading_text) { ?>
              <div class="text-danger"><?php echo $error_heading_text; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-footer-text"><?php echo $entry_footer_text; ?></label>
            <div class="col-sm-10">
              <input type="text" name="footer_text" value="<?php echo $footer_text; ?>" placeholder="<?php echo $entry_footer_text; ?>" id="input-footer-text" class="form-control" />
              <?php if ($error_footer_text) { ?>
              <div class="text-danger"><?php echo $error_footer_text; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <div class="col-sm-12 pull-left"><h3 class="panel-title"><?php echo $text_options; ?></h3></div>
            <label class="col-sm-2 control-label" for="input-type"><?php echo $entry_type; ?></label>
            <div class="col-sm-10">
              <select name="type" id="input-type" class="form-control">
                <?php foreach ($type_options as $type_option => $type_name) { ?>
                <?php if ($type_option == $type) { ?>
                <option value="<?php echo $type_option; ?>" selected="selected"><?php echo $type_name; ?></option>
                <?php } else { ?>
                <option value="<?php echo $type_option; ?>"><?php echo $type_name; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-transition"><?php echo $entry_transition; ?></label>
            <div class="col-sm-10">
              <select name="transition_id" id="input-transition" class="form-control">
                <?php foreach ($transitions as $transition_type => $transition_name) { ?>
                <?php if ($transition_type == $transition_id) { ?>
                <option value="<?php echo $transition_type; ?>" selected="selected"><?php echo $transition_name; ?></option>
                <?php } else { ?>
                <option value="<?php echo $transition_type; ?>"><?php echo $transition_name; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-num-auctions"><?php echo $entry_num_auctions; ?></label>
            <div class="col-sm-10">
              <input type="text" name="num_auctions" value="<?php echo $num_auctions; ?>" placeholder="<?php echo $entry_num_auctions; ?>" id="input-num-auctions" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-width"><?php echo $entry_width; ?></label>
            <div class="col-sm-10">
              <input type="text" name="width" value="<?php echo $width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-width" class="form-control" />
              <?php if ($error_width) { ?>
              <div class="text-danger"><?php echo $error_width; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-height"><?php echo $entry_height; ?></label>
            <div class="col-sm-10">
              <input type="text" name="height" value="<?php echo $height; ?>" placeholder="<?php echo $entry_height; ?>" id="input-height" class="form-control" />
              <?php if ($error_height) { ?>
              <div class="text-danger"><?php echo $error_height; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="status" id="input-status" class="form-control">
                <?php if ($status) { ?>
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