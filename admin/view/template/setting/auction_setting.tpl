<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" id="button-save" form="form-setting" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-setting" class="form-horizontal">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-auction" data-toggle="tab"><?php echo $tab_auction; ?></a></li>
            <li><a href="#tab-display" data-toggle="tab"><?php echo $tab_display; ?></a></li>
            <li><a href="#tab-option" data-toggle="tab"><?php echo $tab_option; ?></a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab-auction">
              <fieldset>
                <legend><?php echo $text_auction; ?></legend>
                <div class="form-group">
                  <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_auction_dutch; ?>"><?php echo $entry_auction_dutch; ?></span></label>
                  <div class="col-sm-10">
                    <label class="radio-inline">
                      <?php if ($config_auction_dutch) { ?>
                      <input type="radio" name="config_auction_dutch" value="1" checked="checked" />
                      <?php echo $text_yes; ?>
                      <?php } else { ?>
                      <input type="radio" name="config_auction_dutch" value="1" />
                      <?php echo $text_yes; ?>
                      <?php } ?>
                    </label>
                    <label class="radio-inline">
                      <?php if (!$config_auction_dutch) { ?>
                      <input type="radio" name="config_auction_dutch" value="0" checked="checked" />
                      <?php echo $text_no; ?>
                      <?php } else { ?>
                      <input type="radio" name="config_auction_dutch" value="0" />
                      <?php echo $text_no; ?>
                      <?php } ?>
                    </label>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_auction_count; ?>"><?php echo $entry_auction_count; ?></span></label>
                  <div class="col-sm-10">
                    <label class="radio-inline">
                      <?php if ($config_auction_count) { ?>
                      <input type="radio" name="config_auction_count" value="1" checked="checked" />
                      <?php echo $text_yes; ?>
                      <?php } else { ?>
                      <input type="radio" name="config_auction_count" value="1" />
                      <?php echo $text_yes; ?>
                      <?php } ?>
                    </label>
                    <label class="radio-inline">
                      <?php if (!$config_auction_count) { ?>
                      <input type="radio" name="config_auction_count" value="0" checked="checked" />
                      <?php echo $text_no; ?>
                      <?php } else { ?>
                      <input type="radio" name="config_auction_count" value="0" />
                      <?php echo $text_no; ?>
                      <?php } ?>
                    </label>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-admin-limit"><span data-toggle="tooltip" title="<?php echo $help_limit_admin; ?>"><?php echo $entry_limit_admin; ?></span></label>
                  <div class="col-sm-1">
                    <input type="text" name="config_auction_limit_admin" value="<?php echo $config_auction_limit_admin; ?>" placeholder="<?php echo $entry_limit_admin; ?>" id="input-admin-limit" class="form-control" />
                    <?php if ($error_limit_admin) { ?>
                    <div class="text-danger"><?php echo $error_limit_admin; ?></div>
                    <?php } ?>
                  </div>
                  <div class="col-sm-9">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_auction_extension; ?>"><?php echo $entry_auction_extension; ?></span></label>
                  <div class="col-sm-2">
                    <label class="radio-inline">
                      <?php if ($config_auction_extension) { ?>
                      <input type="radio" name="config_auction_extension" value="1" checked="checked" />
                      <?php echo $text_yes; ?>
                      <?php } else { ?>
                      <input type="radio" name="config_auction_extension" value="1" />
                      <?php echo $text_yes; ?>
                      <?php } ?>
                    </label>
                    <label class="radio-inline">
                      <?php if (!$config_auction_extension) { ?>
                      <input type="radio" name="config_auction_extension" value="0" checked="checked" />
                      <?php echo $text_no; ?>
                      <?php } else { ?>
                      <input type="radio" name="config_auction_extension" value="0" />
                      <?php echo $text_no; ?>
                      <?php } ?>
                    </label>
                  </div>
                  <label class="col-sm-2 control-label" for="input-admin-extension"><span data-toggle="tooltip" title="<?php echo $help_auction_extension_left; ?>"><?php echo $entry_auction_extension_left; ?></span></label>
                  <div class="col-sm-1">
                    <input type="text" name="config_auction_extension_left" value="<?php echo $config_auction_extension_left; ?>" placeholder="<?php echo $entry_auction_extension_left; ?>" id="input-auction-extension" class="form-control" />Minutes
                    <?php if ($error_auction_extension_left) { ?>
                    <div class="text-danger"><?php echo $error_auction_extension_left; ?></div>
                    <?php } ?>
                  </div>
                  <label class="col-sm-2 control-label" for="input-admin-extension-for"><span data-toggle="tooltip" title="<?php echo $help_auction_extension_for; ?>"><?php echo $entry_auction_extension_for; ?></span></label>
                  <div class="col-sm-1">
                    <input type="text" name="config_auction_extension_for" value="<?php echo $config_auction_extension_for; ?>" placeholder="<?php echo $entry_auction_extension_for; ?>" id="input-auction-extension-for" class="form-control" />Minutes
                    <?php if ($error_auction_extension_for) { ?>
                    <div class="text-danger"><?php echo $error_auction_extension_for; ?></div>
                    <?php } ?>
                  </div>
                </div>
                
                <div class="form-group">
                  <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_auction_countdown; ?>"><?php echo $entry_auction_countdown; ?></span></label>
                  <div class="col-sm-2">
                    <label class="radio-inline">
                      <?php if ($config_auction_countdown) { ?>
                      <input type="radio" name="config_auction_countdown" value="1" checked="checked" />
                      <?php echo $text_yes; ?>
                      <?php } else { ?>
                      <input type="radio" name="config_auction_countdown" value="1" />
                      <?php echo $text_yes; ?>
                      <?php } ?>
                    </label>
                    <label class="radio-inline">
                      <?php if (!$config_auction_countdown) { ?>
                      <input type="radio" name="config_auction_countdown" value="0" checked="checked" />
                      <?php echo $text_no; ?>
                      <?php } else { ?>
                      <input type="radio" name="config_auction_countdown" value="0" />
                      <?php echo $text_no; ?>
                      <?php } ?>
                    </label>
                  </div>
                  <label class="col-sm-2 control-label" for="input-admin-countdown"><span data-toggle="tooltip" title="<?php echo $help_auction_countdown_time; ?>"><?php echo $entry_auction_countdown_time; ?></span></label>
                  <div class="col-sm-1">
                    <input type="text" name="config_auction_countdown_time" value="<?php echo $config_auction_countdown_time; ?>" placeholder="<?php echo $entry_auction_countdown_time; ?>" id="input-auction-countdown" class="form-control" />Hours
                    <?php if ($error_auction_countdown_time) { ?>
                    <div class="text-danger"><?php echo $error_auction_countdown_time; ?></div>
                    <?php } ?>
                  </div>
                </div>
                <div class="form-group"></div>
              </fieldset>
              
              <fieldset>
                <legend><?php echo $text_auction_status; ?></legend>
                <div class="form-group">
                  <label class="col-sm-3 control-label" for="input-created-status"><span data-toggle="tooltip" title="<?php echo $help_auction_created_status; ?>"><?php echo $entry_auction_created_status; ?></span></label>
                  <div class="col-sm-3">
                    <select name="config_auction_created_status" id="input-created-status" class="form-control">
                      <?php foreach ($auction_statuses as $auction_status) { ?>
                      <?php if ($auction_status['auction_status_id'] == $config_auction_created_status) { ?>
                      <option value="<?php echo $auction_status['auction_status_id']; ?>" selected="selected"><?php echo $auction_status['name']; ?></option>
                      <?php } else { ?>
                      <option value="<?php echo $auction_status['auction_status_id']; ?>"><?php echo $auction_status['name']; ?></option>
                      <?php } ?>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label" for="input-open-status"><span data-toggle="tooltip" title="<?php echo $help_auction_open_status; ?>"><?php echo $entry_auction_open_status; ?></span></label>
                  <div class="col-sm-3">
                    <select name="config_auction_open_status" id="input-open-status" class="form-control">
                      <?php foreach ($auction_statuses as $auction_status) { ?>
                      <?php if ($auction_status['auction_status_id'] == $config_auction_open_status) { ?>
                      <option value="<?php echo $auction_status['auction_status_id']; ?>" selected="selected"><?php echo $auction_status['name']; ?></option>
                      <?php } else { ?>
                      <option value="<?php echo $auction_status['auction_status_id']; ?>"><?php echo $auction_status['name']; ?></option>
                      <?php } ?>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label" for="input-closed-status"><span data-toggle="tooltip" title="<?php echo $help_auction_closed_status; ?>"><?php echo $entry_auction_closed_status; ?></span></label>
                  <div class="col-sm-3">
                    <select name="config_auction_closed_status" id="input-closed-status" class="form-control">
                      <?php foreach ($auction_statuses as $auction_status) { ?>
                      <?php if ($auction_status['auction_status_id'] == $config_auction_closed_status) { ?>
                      <option value="<?php echo $auction_status['auction_status_id']; ?>" selected="selected"><?php echo $auction_status['name']; ?></option>
                      <?php } else { ?>
                      <option value="<?php echo $auction_status['auction_status_id']; ?>"><?php echo $auction_status['name']; ?></option>
                      <?php } ?>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label" for="input-suspended-status"><span data-toggle="tooltip" title="<?php echo $help_auction_suspended_status; ?>"><?php echo $entry_auction_suspended_status; ?></span></label>
                  <div class="col-sm-3">
                    <select name="config_auction_suspended_status" id="input-suspended-status" class="form-control">
                      <?php foreach ($auction_statuses as $auction_status) { ?>
                      <?php if ($auction_status['auction_status_id'] == $config_auction_suspended_status) { ?>
                      <option value="<?php echo $auction_status['auction_status_id']; ?>" selected="selected"><?php echo $auction_status['name']; ?></option>
                      <?php } else { ?>
                      <option value="<?php echo $auction_status['auction_status_id']; ?>"><?php echo $auction_status['name']; ?></option>
                      <?php } ?>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label" for="input-moderation-status"><span data-toggle="tooltip" title="<?php echo $help_auction_moderation_status; ?>"><?php echo $entry_auction_moderation_status; ?></span></label>
                  <div class="col-sm-3">
                    <select name="config_auction_moderation_status" id="input-moderation-status" class="form-control">
                      <?php foreach ($auction_statuses as $auction_status) { ?>
                      <?php if ($auction_status['auction_status_id'] == $config_auction_moderation_status) { ?>
                      <option value="<?php echo $auction_status['auction_status_id']; ?>" selected="selected"><?php echo $auction_status['name']; ?></option>
                      <?php } else { ?>
                      <option value="<?php echo $auction_status['auction_status_id']; ?>"><?php echo $auction_status['name']; ?></option>
                      <?php } ?>
                      <?php } ?>
                    </select>
                  </div>
                </div>
              </fieldset>
            </div>

            <div class="tab-pane" id="tab-display">
              <fieldset>
                <legend><?php echo $text_auction_display; ?></legend>
                
                <div class="form-group">
                  <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_auction_picture_gallery; ?>"><?php echo $entry_auction_picture_gallery; ?></span></label>
                  <div class="col-sm-10">
                    <label class="radio-inline">
                      <?php if ($config_auction_picture_gallery) { ?>
                      <input type="radio" name="config_auction_picture_gallery" value="1" checked="checked" />
                      <?php echo $text_yes; ?>
                      <?php } else { ?>
                      <input type="radio" name="config_auction_picture_gallery" value="1" />
                      <?php echo $text_yes; ?>
                      <?php } ?>
                    </label>
                    <label class="radio-inline">
                      <?php if (!$config_auction_picture_gallery) { ?>
                      <input type="radio" name="config_auction_picture_gallery" value="0" checked="checked" />
                      <?php echo $text_no; ?>
                      <?php } else { ?>
                      <input type="radio" name="config_auction_picture_gallery" value="0" />
                      <?php echo $text_no; ?>
                      <?php } ?>
                    </label>
                  </div>
                </div>
              </fieldset>
              <fieldset>
                <legend><?php echo $text_auction_gallery; ?></legend>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-max-pictures">
                      <span data-toggle="tooltip" title="<?php echo $help_auction_max_gallery_pictures; ?>"><?php echo $entry_auction_max_gallery_pictures; ?></span>
                      <?php if ($error_auction_max_gallery_pictures) { ?>
                      <div class="text-danger"><?php echo $error_auction_max_gallery_pictures; ?></div>
                      <?php } ?>
                  </label>
                  <div class="col-sm-1">
                    <input type="text" name="config_auction_max_gallery_pictures" value="<?php echo $config_auction_max_gallery_pictures; ?>" placeholder="<?php echo $entry_auction_max_gallery_pictures; ?>" id="input-max-pictures" class="form-control" />
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-max-picture-size">
                      <span data-toggle="tooltip" title="<?php echo $help_auction_max_picture_size; ?>"><?php echo $entry_auction_max_picture_size; ?></span>
                      <?php if ($error_auction_max_picture_size) { ?>
                      <div class="text-danger"><?php echo $error_auction_max_picture_size; ?></div>
                      <?php } ?>
                  </label>
                  <div class="col-sm-1">
                    <input type="text" name="config_auction_max_picture_size" value="<?php echo $config_auction_max_picture_size; ?>" placeholder="<?php echo $entry_auction_max_picture_size; ?>" id="input-max-pictures-size" class="form-control" />kB
                  </div>
                </div>
              </fieldset>
            </div>

            <div class="tab-pane" id="tab-option">
              <fieldset>
                <legend><?php echo $text_auction_options; ?></legend>
                
                <div class="form-group">
                  <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_auction_proxy; ?>"><?php echo $entry_auction_proxy; ?></span></label>
                  <div class="col-sm-10">
                    <label class="radio-inline">
                      <?php if ($config_auction_proxy) { ?>
                      <input type="radio" name="config_auction_proxy" value="1" checked="checked" />
                      <?php echo $text_yes; ?>
                      <?php } else { ?>
                      <input type="radio" name="config_auction_proxy" value="1" />
                      <?php echo $text_yes; ?>
                      <?php } ?>
                    </label>
                    <label class="radio-inline">
                      <?php if (!$config_auction_proxy) { ?>
                      <input type="radio" name="config_auction_proxy" value="0" checked="checked" />
                      <?php echo $text_no; ?>
                      <?php } else { ?>
                      <input type="radio" name="config_auction_proxy" value="0" />
                      <?php echo $text_no; ?>
                      <?php } ?>
                    </label>
                  </div>
                </div>
                
                <div class="form-group">
                  <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_auction_custom_start_date; ?>"><?php echo $entry_auction_custom_start_date; ?></span></label>
                  <div class="col-sm-10">
                    <label class="radio-inline">
                      <?php if ($config_auction_custom_start_date) { ?>
                      <input type="radio" name="config_auction_custom_start_date" value="1" checked="checked" />
                      <?php echo $text_yes; ?>
                      <?php } else { ?>
                      <input type="radio" name="config_auction_custom_start_date" value="1" />
                      <?php echo $text_yes; ?>
                      <?php } ?>
                    </label>
                    <label class="radio-inline">
                      <?php if (!$config_auction_custom_start_date) { ?>
                      <input type="radio" name="config_auction_custom_start_date" value="0" checked="checked" />
                      <?php echo $text_no; ?>
                      <?php } else { ?>
                      <input type="radio" name="config_auction_custom_start_date" value="0" />
                      <?php echo $text_no; ?>
                      <?php } ?>
                    </label>
                  </div>
                </div>
                
                <div class="form-group">
                  <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_auction_custom_end_date; ?>"><?php echo $entry_auction_custom_end_date; ?></span></label>
                  <div class="col-sm-10">
                    <label class="radio-inline">
                      <?php if ($config_auction_custom_end_date) { ?>
                      <input type="radio" name="config_auction_custom_end_date" value="1" checked="checked" />
                      <?php echo $text_yes; ?>
                      <?php } else { ?>
                      <input type="radio" name="config_auction_custom_end_date" value="1" />
                      <?php echo $text_yes; ?>
                      <?php } ?>
                    </label>
                    <label class="radio-inline">
                      <?php if (!$config_auction_custom_end_date) { ?>
                      <input type="radio" name="config_auction_custom_end_date" value="0" checked="checked" />
                      <?php echo $text_no; ?>
                      <?php } else { ?>
                      <input type="radio" name="config_auction_custom_end_date" value="0" />
                      <?php echo $text_no; ?>
                      <?php } ?>
                    </label>
                  </div>
                </div>
                
                <div class="form-group">
                  <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_auction_bid_increments; ?>"><?php echo $entry_auction_bid_increments; ?></span></label>
                  <div class="col-sm-10">
                    <label class="radio-inline">
                      <?php if ($config_auction_bid_increments) { ?>
                      <input type="radio" name="config_auction_bid_increments" value="1" checked="checked" />
                      <?php echo $text_yes; ?>
                      <?php } else { ?>
                      <input type="radio" name="config_auction_bid_increments" value="1" />
                      <?php echo $text_yes; ?>
                      <?php } ?>
                    </label>
                    <label class="radio-inline">
                      <?php if (!$config_auction_bid_increments) { ?>
                      <input type="radio" name="config_auction_bid_increments" value="0" checked="checked" />
                      <?php echo $text_no; ?>
                      <?php } else { ?>
                      <input type="radio" name="config_auction_bid_increments" value="0" />
                      <?php echo $text_no; ?>
                      <?php } ?>
                    </label>
                  </div>
                </div>
                
                <div class="form-group">
                  <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_auction_subtitles; ?>"><?php echo $entry_auction_subtitles; ?></span></label>
                  <div class="col-sm-10">
                    <label class="radio-inline">
                      <?php if ($config_auction_subtitles) { ?>
                      <input type="radio" name="config_auction_subtitles" value="1" checked="checked" />
                      <?php echo $text_yes; ?>
                      <?php } else { ?>
                      <input type="radio" name="config_auction_subtitles" value="1" />
                      <?php echo $text_yes; ?>
                      <?php } ?>
                    </label>
                    <label class="radio-inline">
                      <?php if (!$config_auction_subtitles) { ?>
                      <input type="radio" name="config_auction_subtitles" value="0" checked="checked" />
                      <?php echo $text_no; ?>
                      <?php } else { ?>
                      <input type="radio" name="config_auction_subtitles" value="0" />
                      <?php echo $text_no; ?>
                      <?php } ?>
                    </label>
                  </div>
                </div>
                
                <div class="form-group">
                  <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_auction_additional_cat; ?>"><?php echo $entry_auction_additional_cat; ?></span></label>
                  <div class="col-sm-10">
                    <label class="radio-inline">
                      <?php if ($config_auction_additional_cat) { ?>
                      <input type="radio" name="config_auction_additional_cat" value="1" checked="checked" />
                      <?php echo $text_yes; ?>
                      <?php } else { ?>
                      <input type="radio" name="config_auction_additional_cat" value="1" />
                      <?php echo $text_yes; ?>
                      <?php } ?>
                    </label>
                    <label class="radio-inline">
                      <?php if (!$config_auction_additional_cat) { ?>
                      <input type="radio" name="config_auction_additional_cat" value="0" checked="checked" />
                      <?php echo $text_no; ?>
                      <?php } else { ?>
                      <input type="radio" name="config_auction_additional_cat" value="0" />
                      <?php echo $text_no; ?>
                      <?php } ?>
                    </label>
                  </div>
                </div>
                
                <div class="form-group">
                  <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_auction_auto_relist; ?>"><?php echo $entry_auction_auto_relist; ?></span></label>
                  <div class="col-sm-10">
                    <label class="radio-inline">
                      <?php if ($config_auction_auto_relist) { ?>
                      <input type="radio" name="config_auction_auto_relist" value="1" checked="checked" />
                      <?php echo $text_yes; ?>
                      <?php } else { ?>
                      <input type="radio" name="config_auction_auto_relist" value="1" />
                      <?php echo $text_yes; ?>
                      <?php } ?>
                    </label>
                    <label class="radio-inline">
                      <?php if (!$config_auction_auto_relist) { ?>
                      <input type="radio" name="config_auction_auto_relist" value="0" checked="checked" />
                      <?php echo $text_no; ?>
                      <?php } else { ?>
                      <input type="radio" name="config_auction_auto_relist" value="0" />
                      <?php echo $text_no; ?>
                      <?php } ?>
                    </label>
                  </div>
                  <label class="col-sm-2 control-label" for="input-admin-max-relists">
                    <span data-toggle="tooltip" title="<?php echo $help_auction_max_relists; ?>"><?php echo $entry_auction_max_relists; ?></span>
                    <?php if ($error_auction_max_relists) { ?>
                    <div class="text-danger"><?php echo $error_auction_max_relists; ?></div>
                    <?php } ?>
                  </label>
                  <div class="col-sm-1">
                    <input type="text" name="config_auction_max_relists" value="<?php echo $config_auction_max_relists; ?>" placeholder="<?php echo $entry_auction_max_relists; ?>" id="input-auction-max-relists" class="form-control" />
                  </div>
                </div>
              </fieldset>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
  
  
  
  <?php echo $footer; ?> 