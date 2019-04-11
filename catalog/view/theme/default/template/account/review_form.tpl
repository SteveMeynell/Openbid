<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <div class="row">
        <?php if ($column_left || $column_right) { ?>
        <?php $class = 'col-sm-6'; ?>
        <?php } else { ?>
        <?php $class = 'col-sm-8'; ?>
        <?php } ?>

        <div class="container">
          <?php echo $welcome_message; ?>
        </div>


<form class="form-horizontal" id="form-review">
  <input type="hidden" name="review_id" value="<?php echo $review_id; ?>"/>
  <input type="hidden" name="customer_id" value="<?php echo $customer_id; ?>"/>
  <input type="hidden" name="group" value="<?php echo $group; ?>"/>
  <h2><?php echo $text_write; ?></h2>
  <div class="form-group required">
      <div class="col-sm-12">
        <label class="control-label" for="input-name">
          <?php echo $entry_name; ?>
        </label>
        <input type="text" name="name" value="<?php echo $customer_name; ?>" id="input-name" class="form-control" />
      </div>
    </div>
    <div class="panel-group">
    <div class="panel panel-info">
      <div class="panel-heading">Communication</div>
      <div class="panel-body">
        <div class="form-group required">
          <div class="col-sm-12">
            <label class="control-label"><?php echo $entry_question1; ?></label>
            &nbsp;&nbsp;&nbsp; <?php echo $entry_bad; ?>&nbsp;
            <input type="radio" name="question1" value="1" />
            &nbsp;
            <input type="radio" name="question1" value="2" />
            &nbsp;
            <input type="radio" name="question1" value="3" />
            &nbsp;
            <input type="radio" name="question1" value="4" />
            &nbsp;
            <input type="radio" name="question1" value="5" />
            &nbsp;<?php echo $entry_good; ?>
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-6">
            <label class="control-label-left" for="input-review">
              <?php echo $entry_suggestion1; ?>
            </label>
            <textarea name="suggestion1" rows="1" id="input-review" class="form-control"></textarea>
            <div class="help-block"><?php echo $text_note; ?></div>
          </div>
        </div>
      </div>
    </div>
    </div>
    <div class="panel panel-info">
      <div class="panel-heading">Shipping</div>
        <div class="panel-body">
          <div class="form-group required">
            <div class="col-sm-12">
              <label class="control-label"><?php echo $entry_question2; ?></label>
              &nbsp;&nbsp;&nbsp; <?php echo $entry_bad; ?>&nbsp;
              <input type="radio" name="question2" value="1" />
              &nbsp;
              <input type="radio" name="question2" value="2" />
              &nbsp;
              <input type="radio" name="question2" value="3" />
              &nbsp;
              <input type="radio" name="question2" value="4" />
              &nbsp;
              <input type="radio" name="question2" value="5" />
              &nbsp;<?php echo $entry_good; ?>
            </div>
          </div>
          <div class="form-group">
            <div class="col-sm-6">
              <label class="control-label-left" for="input-review">
                <?php echo $entry_suggestion2; ?>
              </label>
              <textarea name="suggestion2" rows="1" id="input-review" class="form-control"></textarea>
              <div class="help-block"><?php echo $text_note; ?></div>
            </div>
          </div>
      </div>
    </div>
    <div class="panel panel-info">
      <div class="panel-heading">Satisfaction</div>
        <div class="panel-body">
          <div class="form-group required">
            <div class="col-sm-12">
              <label class="control-label"><?php echo $entry_question3; ?></label>
              &nbsp;&nbsp;&nbsp; <?php echo $entry_bad; ?>&nbsp;
              <input type="radio" name="question3" value="1" />
              &nbsp;
              <input type="radio" name="question3" value="2" />
              &nbsp;
              <input type="radio" name="question3" value="3" />
              &nbsp;
              <input type="radio" name="question3" value="4" />
              &nbsp;
              <input type="radio" name="question3" value="5" />
              &nbsp;<?php echo $entry_good; ?>
            </div>
          </div>
          <div class="form-group">
            <div class="col-sm-6">
              <label class="control-label-left" for="input-review">
                <?php echo $entry_suggestion3; ?>
              </label>
              <textarea name="suggestion3" rows="1" id="input-review" class="form-control"></textarea>
              <div class="help-block"><?php echo $text_note; ?></div>
            </div>
          </div>
        </div>
    </div>
    <div class="panel panel-info">
      <div class="panel-heading">Site Design</div>
        <div class="panel-body">
          <div class="form-group required">
            <div class="col-sm-12">
              <label class="control-label"><?php echo $entry_question4; ?></label>
              &nbsp;&nbsp;&nbsp; <?php echo $entry_bad; ?>&nbsp;
              <input type="radio" name="question4" value="1" />
              &nbsp;
              <input type="radio" name="question4" value="2" />
              &nbsp;
              <input type="radio" name="question4" value="3" />
              &nbsp;
              <input type="radio" name="question4" value="4" />
              &nbsp;
              <input type="radio" name="question4" value="5" />
              &nbsp;<?php echo $entry_good; ?>
            </div>
          </div>
          <div class="form-group">
            <div class="col-sm-6">
              <label class="control-label-left" for="input-review">
                <?php echo $entry_suggestion4; ?>
              </label>
              <textarea name="suggestion4" rows="1" id="input-review" class="form-control"></textarea>
              <div class="help-block"><?php echo $text_note; ?></div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="panel panel-info">
      <div class="panel-heading">Site Layout</div>
        <div class="panel-body">
          <div class="form-group required">
            <div class="col-sm-12">
              <label class="control-label"><?php echo $entry_question5; ?></label>
              &nbsp;&nbsp;&nbsp; <?php echo $entry_bad; ?>&nbsp;
              <input type="radio" name="question5" value="1" />
              &nbsp;
              <input type="radio" name="question5" value="2" />
              &nbsp;
              <input type="radio" name="question5" value="3" />
              &nbsp;
              <input type="radio" name="question5" value="4" />
              &nbsp;
              <input type="radio" name="question5" value="5" />
              &nbsp;<?php echo $entry_good; ?>
            </div>
          </div>
          <div class="form-group">
            <div class="col-sm-6">
              <label class="control-label-left" for="input-review">
                <?php echo $entry_suggestion5; ?>
              </label>
              <textarea name="suggestion5" rows="1" id="input-review" class="form-control"></textarea>
              <div class="help-block"><?php echo $text_note; ?></div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="panel panel-info">
      <div class="panel-heading">Site Value</div>
        <div class="panel-body">
          <div class="form-group required">
            <div class="col-sm-6">
              <label class="control-label"><?php echo $entry_question6; ?></label>
              &nbsp;&nbsp;&nbsp; <?php echo $entry_bad; ?>&nbsp;
              <input type="radio" name="question6" value="1" />
              &nbsp;
              <input type="radio" name="question6" value="2" />
              &nbsp;
              <input type="radio" name="question6" value="3" />
              &nbsp;
              <input type="radio" name="question6" value="4" />
              &nbsp;
              <input type="radio" name="question6" value="5" />
              &nbsp;<?php echo $entry_good; ?>
            </div>
          </div>
          <div class="form-group">
            <div class="col-sm-6">
              <label class="control-label-left" for="input-review">
                <?php echo $entry_suggestion6; ?>
              </label>
              <textarea name="suggestion6" rows="1" id="input-review" class="form-control"></textarea>
              <div class="help-block"><?php echo $text_note; ?></div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="panel panel-info">
      <div class="panel-heading">Final Comment Visible To All</div>
        <div class="panel-body">
          <div class="form-group">
            <div class="col-sm-12">
              <label class="control-label-left" for="input-review">
                <?php echo $entry_final_suggestion; ?>
              </label>
              <textarea name="final_suggestion" rows="5" id="input-review" class="form-control"></textarea>
              <div class="help-block"><?php echo $text_note; ?></div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php echo $captcha; ?>
    <div class="buttons clearfix">
      <div class="pull-right">
        <button type="button" id="button-review" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary">
          <?php echo $button_save; ?>
        </button>
      </div>
    </div>
</form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>


<script type="text/javascript">
$('#button-review').on('click', function() {
	$.ajax({
		url: 'index.php?route=account/review/add',
		type: 'post',
		dataType: 'json',
		data: $("#form-review").serialize(),
		beforeSend: function() {
			$('#button-review').button('loading');
		},
		complete: function() {
			$('#button-review').button('reset');
		},
		success: function(json) {
			$('.alert-success, .alert-danger').remove();

			if (json['error']) {
        alert(json['error']);
				$('#review').after('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
			}

			if (json['success']) {
				//$('#review').after('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');
        alert(json['success']);	
        if (json['redirect']) {
          location = json['redirect'];
        }
			}
      
		}
	});
});
</script>