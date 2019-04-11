<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-review" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
      </div>
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
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-review" class="form-horizontal">
          <div class="form-group ">
            <label class="col-sm-2 control-label" for="input-auction"><?php echo $entry_auction; ?></label>
            <div class="col-sm-10">
              <input type="text" name="auction" value="<?php echo $auction; ?>" placeholder="<?php echo $entry_auction; ?>" id="input-auction" class="form-control" />
              <input type="hidden" name="auction_id" value="<?php echo $auction_id; ?>" />
            </div>
          </div>
            <div class="form-group ">
              <label class="col-sm-2 control-label" for="input-seller"><?php echo $entry_seller; ?></label>
              <div class="col-sm-10">
                <input type="text" name="seller" value="<?php echo $seller; ?>" placeholder="<?php echo $entry_seller; ?>" id="input-seller" class="form-control" />
              </div>
              <label class="col-sm-2 control-label" for="input-seller-reviewed"><?php echo $entry_seller_reviewed; ?></label>
              <div class="col-sm-2">
                <label class="radio-inline">
                    <?php if ($seller_reviewed) { ?>
                    <input type="radio" name="seller_reviewed" value="1" checked="checked" />
                    <?php echo $text_yes; ?>
                    <?php } else { ?>
                    <input type="radio" name="seller_reviewed" value="1" />
                    <?php echo $text_yes; ?>
                    <?php } ?>
                </label>
                <label class="radio-inline">
                    <?php if (!$seller_reviewed) { ?>
                    <input type="radio" name="seller_reviewed" value="0" checked="checked" />
                    <?php echo $text_no; ?>
                    <?php } else { ?>
                    <input type="radio" name="seller_reviewed" value="0" />
                    <?php echo $text_no; ?>
                    <?php } ?>
                </label>
              </div>
              <label class="col-sm-3 control-label">
                <button type="button" name="seller_reminder" id="seller_reminder" <?php if ($seller_reviewed) { ?> disabled <?php } ?> data-toggle="tooltip" class="btn btn-info" title="<?php echo $button_seller_reminder; ?>"><i class="fa fa-check"></i></button>
                  <?php echo $button_seller_reminder; ?>
              </label>
            </div>
            <div class="form-group ">
              <label class="col-sm-2 control-label" for="input-seller_question1"><?php echo $entry_seller_question1; ?></label>
              <div class="col-sm-10">
                <label class="radio-inline">
                  <?php if ($seller_question1 == 1) { ?>
                  <input type="radio" name="seller_question1" value="1" checked="checked" />
                  1
                  <?php } else { ?>
                  <input type="radio" name="seller_question1" value="1" />
                  1
                  <?php } ?>
                </label>
                <label class="radio-inline">
                  <?php if ($seller_question1 == 2) { ?>
                  <input type="radio" name="seller_question1" value="2" checked="checked" />
                  2
                  <?php } else { ?>
                  <input type="radio" name="seller_question1" value="2" />
                  2
                  <?php } ?>
                </label>
                <label class="radio-inline">
                  <?php if ($seller_question1 == 3) { ?>
                  <input type="radio" name="seller_question1" value="3" checked="checked" />
                  3
                  <?php } else { ?>
                  <input type="radio" name="seller_question1" value="3" />
                  3
                  <?php } ?>
                </label>
                <label class="radio-inline">
                  <?php if ($seller_question1 == 4) { ?>
                  <input type="radio" name="seller_question1" value="4" checked="checked" />
                  4
                  <?php } else { ?>
                  <input type="radio" name="seller_question1" value="4" />
                  4
                  <?php } ?>
                </label>
                <label class="radio-inline">
                  <?php if ($seller_question1 == 5) { ?>
                  <input type="radio" name="seller_question1" value="5" checked="checked" />
                  5
                  <?php } else { ?>
                  <input type="radio" name="seller_question1" value="5" />
                  5
                  <?php } ?>
                </label>
              </div>
            
              <label class="col-sm-2 control-label" for="input-seller_question2"><?php echo $entry_seller_question2; ?></label>
              <div class="col-sm-10">
                <label class="radio-inline">
                  <?php if ($seller_question2 == 1) { ?>
                  <input type="radio" name="seller_question2" value="1" checked="checked" />
                  1
                  <?php } else { ?>
                  <input type="radio" name="seller_question2" value="1" />
                  1
                  <?php } ?>
                </label>
                <label class="radio-inline">
                  <?php if ($seller_question2 == 2) { ?>
                  <input type="radio" name="seller_question2" value="2" checked="checked" />
                  2
                  <?php } else { ?>
                  <input type="radio" name="seller_question2" value="2" />
                  2
                  <?php } ?>
                </label>
                <label class="radio-inline">
                  <?php if ($seller_question2 == 3) { ?>
                  <input type="radio" name="seller_question2" value="3" checked="checked" />
                  3
                  <?php } else { ?>
                  <input type="radio" name="seller_question2" value="3" />
                  3
                  <?php } ?>
                </label>
                <label class="radio-inline">
                  <?php if ($seller_question2 == 4) { ?>
                  <input type="radio" name="seller_question2" value="4" checked="checked" />
                  4
                  <?php } else { ?>
                  <input type="radio" name="seller_question2" value="4" />
                  4
                  <?php } ?>
                </label>
                <label class="radio-inline">
                  <?php if ($seller_question2 == 5) { ?>
                  <input type="radio" name="seller_question2" value="5" checked="checked" />
                  5
                  <?php } else { ?>
                  <input type="radio" name="seller_question2" value="5" />
                  5
                  <?php } ?>
                </label>
              </div>
            
              <label class="col-sm-2 control-label" for="input-seller_question3"><?php echo $entry_seller_question3; ?></label>
              <div class="col-sm-10">
                <label class="radio-inline">
                  <?php if ($seller_question3 == 1) { ?>
                  <input type="radio" name="seller_question3" value="1" checked="checked" />
                  1
                  <?php } else { ?>
                  <input type="radio" name="seller_question3" value="1" />
                  1
                  <?php } ?>
                </label>
                <label class="radio-inline">
                  <?php if ($seller_question3 == 2) { ?>
                  <input type="radio" name="seller_question3" value="2" checked="checked" />
                  2
                  <?php } else { ?>
                  <input type="radio" name="seller_question3" value="2" />
                  2
                  <?php } ?>
                </label>
                <label class="radio-inline">
                  <?php if ($seller_question3 == 3) { ?>
                  <input type="radio" name="seller_question3" value="3" checked="checked" />
                  3
                  <?php } else { ?>
                  <input type="radio" name="seller_question3" value="3" />
                  3
                  <?php } ?>
                </label>
                <label class="radio-inline">
                  <?php if ($seller_question3 == 4) { ?>
                  <input type="radio" name="seller_question3" value="4" checked="checked" />
                  4
                  <?php } else { ?>
                  <input type="radio" name="seller_question3" value="4" />
                  4
                  <?php } ?>
                </label>
                <label class="radio-inline">
                  <?php if ($seller_question3 == 5) { ?>
                  <input type="radio" name="seller_question3" value="5" checked="checked" />
                  5
                  <?php } else { ?>
                  <input type="radio" name="seller_question3" value="5" />
                  5
                  <?php } ?>
                </label>
              </div>
            </div>
            <div class="form-group ">
              <label class="col-sm-2 control-label" for="input-seller-suggestion"><?php echo $entry_seller_suggestion; ?></label>
              <div class="col-sm-10">
                <textarea name="seller_suggestion" cols="60" rows="8" placeholder="<?php echo $entry_seller_suggestion; ?>" id="input-seller-suggestion" class="form-control"><?php echo $seller_suggestion; ?></textarea>
              </div>
            </div>

          <div class="form-group ">
            <label class="col-sm-2 control-label" for="input-bidder"><?php echo $entry_bidder; ?></label>
            <div class="col-sm-10">
              <input type="text" name="bidder" value="<?php echo $bidder; ?>" placeholder="<?php echo $entry_bidder; ?>" id="input-bidder" class="form-control" />
            </div>
          
            <label class="col-sm-2 control-label" for="input-bidder-reviewed"><?php echo $entry_bidder_reviewed; ?></label>
            <div class="col-sm-2">
              <label class="radio-inline">
                  <?php if ($bidder_reviewed) { ?>
                  <input type="radio" name="bidder_reviewed" value="1" checked="checked" />
                  <?php echo $text_yes; ?>
                  <?php } else { ?>
                  <input type="radio" name="bidder_reviewed" value="1" />
                  <?php echo $text_yes; ?>
                  <?php } ?>
              </label>
              <label class="radio-inline">
                  <?php if (!$bidder_reviewed) { ?>
                  <input type="radio" name="bidder_reviewed" value="0" checked="checked" />
                  <?php echo $text_no; ?>
                  <?php } else { ?>
                  <input type="radio" name="bidder_reviewed" value="0" />
                  <?php echo $text_no; ?>
                  <?php } ?>
              </label>
            </div>
              <label class="col-sm-3 control-label">
                <button type="button" name="bidder_reminder" id="bidder_reminder" <?php if ($bidder_reviewed) { ?> disabled <?php } ?> data-toggle="tooltip" class="btn btn-info" title="<?php echo $button_bidder_reminder; ?>"><i class="fa fa-check"></i></button>
                <?php echo $button_bidder_reminder; ?>
              </label>
          </div>
          <div class="form-group ">
            <label class="col-sm-2 control-label" for="input-bidder_question1"><?php echo $entry_bidder_question1; ?></label>
            <div class="col-sm-10">
              <label class="radio-inline">
                <?php if ($bidder_question1 == 1) { ?>
                <input type="radio" name="bidder_question1" value="1" checked="checked" />
                1
                <?php } else { ?>
                <input type="radio" name="bidder_question1" value="1" />
                1
                <?php } ?>
              </label>
              <label class="radio-inline">
                <?php if ($bidder_question1 == 2) { ?>
                <input type="radio" name="bidder_question1" value="2" checked="checked" />
                2
                <?php } else { ?>
                <input type="radio" name="bidder_question1" value="2" />
                2
                <?php } ?>
              </label>
              <label class="radio-inline">
                <?php if ($bidder_question1 == 3) { ?>
                <input type="radio" name="bidder_question1" value="3" checked="checked" />
                3
                <?php } else { ?>
                <input type="radio" name="bidder_question1" value="3" />
                3
                <?php } ?>
              </label>
              <label class="radio-inline">
                <?php if ($bidder_question1 == 4) { ?>
                <input type="radio" name="bidder_question1" value="4" checked="checked" />
                4
                <?php } else { ?>
                <input type="radio" name="bidder_question1" value="4" />
                4
                <?php } ?>
              </label>
              <label class="radio-inline">
                <?php if ($bidder_question1 == 5) { ?>
                <input type="radio" name="bidder_question1" value="5" checked="checked" />
                5
                <?php } else { ?>
                <input type="radio" name="bidder_question1" value="5" />
                5
                <?php } ?>
              </label>
            </div>
          
            <label class="col-sm-2 control-label" for="input-bidder_question2"><?php echo $entry_bidder_question2; ?></label>
            <div class="col-sm-10">
              <label class="radio-inline">
                <?php if ($bidder_question2 == 1) { ?>
                <input type="radio" name="bidder_question2" value="1" checked="checked" />
                1
                <?php } else { ?>
                <input type="radio" name="bidder_question2" value="1" />
                1
                <?php } ?>
              </label>
              <label class="radio-inline">
                <?php if ($bidder_question2 == 2) { ?>
                <input type="radio" name="bidder_question2" value="2" checked="checked" />
                2
                <?php } else { ?>
                <input type="radio" name="bidder_question2" value="2" />
                2
                <?php } ?>
              </label>
              <label class="radio-inline">
                <?php if ($bidder_question2 == 3) { ?>
                <input type="radio" name="bidder_question2" value="3" checked="checked" />
                3
                <?php } else { ?>
                <input type="radio" name="bidder_question2" value="3" />
                3
                <?php } ?>
              </label>
              <label class="radio-inline">
                <?php if ($bidder_question2 == 4) { ?>
                <input type="radio" name="bidder_question2" value="4" checked="checked" />
                4
                <?php } else { ?>
                <input type="radio" name="bidder_question2" value="4" />
                4
                <?php } ?>
              </label>
              <label class="radio-inline">
                <?php if ($bidder_question2 == 5) { ?>
                <input type="radio" name="bidder_question2" value="5" checked="checked" />
                5
                <?php } else { ?>
                <input type="radio" name="bidder_question2" value="5" />
                5
                <?php } ?>
              </label>
            </div>
          
            <label class="col-sm-2 control-label" for="input-bidder_question3"><?php echo $entry_bidder_question3; ?></label>
            <div class="col-sm-10">
              <label class="radio-inline">
                <?php if ($bidder_question3 == 1) { ?>
                <input type="radio" name="bidder_question3" value="1" checked="checked" />
                1
                <?php } else { ?>
                <input type="radio" name="bidder_question3" value="1" />
                1
                <?php } ?>
              </label>
              <label class="radio-inline">
                <?php if ($bidder_question3 == 2) { ?>
                <input type="radio" name="bidder_question3" value="2" checked="checked" />
                2
                <?php } else { ?>
                <input type="radio" name="bidder_question3" value="2" />
                2
                <?php } ?>
              </label>
              <label class="radio-inline">
                <?php if ($bidder_question3 == 3) { ?>
                <input type="radio" name="bidder_question3" value="3" checked="checked" />
                3
                <?php } else { ?>
                <input type="radio" name="bidder_question3" value="3" />
                3
                <?php } ?>
              </label>
              <label class="radio-inline">
                <?php if ($bidder_question3 == 4) { ?>
                <input type="radio" name="bidder_question3" value="4" checked="checked" />
                4
                <?php } else { ?>
                <input type="radio" name="bidder_question3" value="4" />
                4
                <?php } ?>
              </label>
              <label class="radio-inline">
                <?php if ($bidder_question3 == 5) { ?>
                <input type="radio" name="bidder_question3" value="5" checked="checked" />
                5
                <?php } else { ?>
                <input type="radio" name="bidder_question3" value="5" />
                5
                <?php } ?>
              </label>
            </div>
          </div>
          <div class="form-group ">
            <label class="col-sm-2 control-label" for="input-bidder-suggestion"><?php echo $entry_bidder_suggestion; ?></label>
            <div class="col-sm-10">
              <textarea name="bidder_suggestion" cols="60" rows="8" placeholder="<?php echo $entry_bidder_suggestion; ?>" id="input-bidder-suggestion" class="form-control"><?php echo $bidder_suggestion; ?></textarea>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-review-date"><?php echo $entry_review_date; ?></label>
            <div class="col-sm-3">
              <div class="input-group datetime">
                <input type="text" name="review_date" value="<?php echo $review_date; ?>" placeholder="<?php echo $entry_review_date; ?>" data-date-format="YYYY-MM-DD HH:mm:ss" id="input-review-date" class="form-control" />
                <span class="input-group-btn">
                <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                </span>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
$('.datetime').datetimepicker({
	pickDate: true,
	pickTime: true
});
//--></script>
  <script type="text/javascript"><!--
$('#bidder_reminder, #seller_reminder').on('click', function(e) {
	e.preventDefault();

  var thetarget = e.currentTarget.attributes['name'].value;
  
  if(thetarget=='bidder_reminder') {
    targetId = <?php echo $bidder_id; ?>;
  } else {
    targetId = <?php echo $seller_id; ?>;
  }
		$.ajax({
			url: 'index.php?route=catalog/review/sendReminder&token=<?php echo $token; ?>',
      type: 'post',
			dataType: 'json',			
      data: 'target=' + thetarget + '&target_id=' + targetId + '&review_id=' + <?php echo $review_id; ?>,
			success: function(json) {
        alert("email sent");
			}
		});
});
</script>
</div>
<?php echo $footer; ?>