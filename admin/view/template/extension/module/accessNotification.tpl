<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-accessNotification" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <img src="view/image/oc.png" width="80" height="80" style="margin-right:10px;"><h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  	<hr>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>

    <div class="" style="display:inline-block; border:0; margin-bottom:15px">
	<a target="_blank "href="https://www.siteguarding.com/en/protect-your-website"><img src="view/image/rek3.png" style="border:1px solid #ccc"></a>
	<a target="_blank "href="https://www.siteguarding.com/en/website-extensions"><img src="view/image/rek1.png" style="margin: 0 10px;border:1px solid #ccc"></a>
	<a target="_blank "href="https://www.siteguarding.com/en/secure-web-hosting"><img src="view/image/rek4.png" style="border:1px solid #ccc"></a>
	</div>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-accessNotification" class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="accessNotification_status" id="input-status" class="form-control">
                <?php if ($accessNotification_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>	
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-email"><?php echo $entry_email; ?></label>
            <div class="col-sm-10">
              <input type="text" name="accessNotification_email" value="<?php echo $accessNotification_email; ?>" placeholder="<?php echo $entry_email_place; ?>" id="input-email" class="form-control" />
              <?php if ($error_accessNotification_email) { ?>
              <div class="text-danger"><?php echo $error_accessNotification_email; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-fail"><?php echo $entry_fail; ?></label>
            <div class="col-sm-10">
              <input type="checkbox" style="margin-top:0.7%;" name="accessNotification_fail" <?php if ($accessNotification_fail) echo 'checked'; ?> id="input-fail" class="form-control" />
              <?php if ($error_accessNotification_fail) { ?>
              <div class="text-danger"><?php echo $error_accessNotification_fail; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-success"><?php echo $entry_success; ?></label>
            <div class="col-sm-10">
              <input type="checkbox" style="margin-top:0.7%;" name="accessNotification_success" <?php if ($accessNotification_success) echo 'checked'; ?> id="input-success" class="form-control" />
              <?php if ($error_accessNotification_success) { ?>
              <div class="text-danger"><?php echo $error_accessNotification_success; ?></div>
              <?php } ?>
            </div>
          </div>		
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-telegram"><?php echo $entry_telegram; ?></label>
            <div class="col-sm-10">
              <input type="checkbox" style="margin-top:0.7%;" name="accessNotification_telegram" <?php if ($accessNotification_telegram) echo 'checked'; ?> id="input-telegram" class="form-control" />
              <?php if ($error_accessNotification_telegram) { ?>
              <div class="text-danger"><?php echo $error_accessNotification_telegram; ?></div>
              <?php } ?>
            </div>
          </div>			
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-email"><?php echo $entry_telegram_key; ?></label>
            <div class="col-sm-10">
              <input type="text" name="accessNotification_telegram_key" value="<?php echo $accessNotification_telegram_key; ?>" placeholder="<?php echo $entry_telegram_key_place; ?>" id="input-telegram_key" class="form-control" />
              <?php if ($error_accessNotification_telegram_key) { ?>
              <div class="text-danger"><?php echo $error_accessNotification_telegram_key; ?></div>
              <?php } ?>
			  <a target="_blank" href="https://www.siteguarding.com/en/how-to-get-telegram-bot-api-token"><h5 style="text-align:center;"><?php echo $link_get_api; ?></h5></a>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-email"><?php echo $entry_chat_id; ?></label>
            <div class="col-sm-10">
              <input type="text" name="accessNotification_chat_id" value="<?php echo $accessNotification_chat_id; ?>" placeholder="<?php echo $entry_chat_id_place; ?>" id="input-chat_id" class="form-control" />
              <?php if ($error_accessNotification_chat_id) { ?>
              <div class="text-danger"><?php echo $error_accessNotification_chat_id; ?></div>
              <?php } ?>
			<h5 style="text-align:center;color:red;"><?php echo $entry_chat_id_sendmsg; ?></h5>
            </div>
          </div>

        </form>
      </div>
    </div>
    <div>
		<h4><?php echo $for_more_information; ?><a target="_blank" href="https://www.siteguarding.com/en/opencart-user-access-notification"><?php echo $link_click; ?></a></h4>
		<a target="_blank "href="http://www.siteguarding.com/livechat/index.html"><img src="view/image/livechat.png"></a>
		<h4><?php echo $for_any_questions; ?><a target="_blank" href="https://www.siteguarding.com/en/contacts"><?php echo $link_contact; ?></a></h4>
		<h4><a target="_blank" href="https://www.siteguarding.com/en"><?php echo $link_siteguarding; ?></a><?php echo $siteguarding; ?></h4>
		<hr>
	</div>	
  </div>
</div>
<?php echo $footer; ?>