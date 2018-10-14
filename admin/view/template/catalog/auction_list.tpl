<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right"><a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
        <button type="submit" form="form-product" formaction="<?php echo $copy; ?>" data-toggle="tooltip" title="<?php echo $button_copy; ?>" class="btn btn-default"><i class="fa fa-copy"></i></button>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-product').submit() : false;"><i class="fa fa-trash-o"></i></button>
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
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">
            
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-seller"><?php echo $entry_seller; ?></label>
                <input type="text" name="filter_seller" value="<?php echo $filter_seller; ?>" placeholder="<?php echo $entry_seller; ?>" id="input-seller" class="form-control" />
              </div>
              <div class="form-group">
                <label class="control-label" for="input-date-created"><?php echo $entry_date_created; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_date_created" value="<?php echo $filter_date_created; ?>" placeholder="<?php echo $entry_date_created; ?>" data-date-format="YYYY-MM-DD" id="input-date-created" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
            </div>
            
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-type"><?php echo $entry_type; ?></label>
                <select name="filter_auction_type" id="input-type" class="form-control">
                  <option value="*"></option>
                  <option value="0"><?php echo $text_regular; ?></option>
                  <option value="1"><?php echo $text_dutch; ?></option>
                </select>
              </div>
              <div class="form-group">
                <label class="control-label" for="input-start-date"><?php echo $entry_start_date; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_start_date" value="<?php echo $filter_start_date; ?>" placeholder="<?php echo $entry_start_date; ?>" data-date-format="YYYY-MM-DD" id="input-start-date" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
            </div>
            
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-auction-status"><?php echo $entry_auction_status; ?></label>
                <select name="filter_auction_status" id="input-auction-status" class="form-control">
                  <option value="*"></option>
                  <?php foreach ($auction_statuses as $auction_status) { ?>
                  <?php if ($auction_status['auction_status_id'] == $filter_auction_status) { ?>
                  <option value="<?php echo $auction_status['auction_status_id']; ?>" selected="selected"><?php echo $auction_status['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $auction_status['auction_status_id']; ?>"><?php echo $auction_status['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </div>
              <div class="form-group">
                <label class="control-label" for="input-end-date"><?php echo $entry_end_date; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_end_date" value="<?php echo $filter_end_date; ?>" placeholder="<?php echo $entry_end_date; ?>" data-date-format="YYYY-MM-DD" id="input-end-date" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-filter"></i> <?php echo $button_filter; ?></button>
            </div>
            
          </div>
        </div>
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-product">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                  <td class="text-center"><?php echo $column_image; ?></td>
                  <td class="text-center"><?php if ($sort == 'a.auction_type') { ?>
                    <a href="<?php echo $sort_type; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_type; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_type; ?>"><?php echo $column_type; ?></a>
                    <?php } ?></td>
                  <td class="text-center"><?php if ($sort == 'ad.title') { ?>
                    <a href="<?php echo $sort_title; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_title; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_title; ?>"><?php echo $column_title; ?></a>
                    <?php } ?></td>
                  <td class="text-center"><?php if ($sort == 'seller') { ?>
                    <a href="<?php echo $sort_seller; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_seller; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_seller; ?>"><?php echo $column_seller; ?></a>
                    <?php } ?></td>
                  <td class="text-center"><?php if ($sort == 'a.date_create') { ?>
                    <a href="<?php echo $sort_createdate; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_createdate; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_createdate; ?>"><?php echo $column_createdate; ?></a>
                    <?php } ?></td>
                  <td class="text-center"><?php if ($sort == 'ad.start_date') { ?>
                    <a href="<?php echo $sort_startdate; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_startdate; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_startdate; ?>"><?php echo $column_startdate; ?></a>
                    <?php } ?></td>
                  <td class="text-center"><?php if ($sort == 'ad.end_date') { ?>
                    <a href="<?php echo $sort_enddate; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_enddate; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_enddate; ?>"><?php echo $column_enddate; ?></a>
                    <?php } ?></td>
                  <td class="text-center"><?php if ($sort == 'a.status') { ?>
                    <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                    <?php } ?></td>
                  <td class="text-right"><?php echo $column_action; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php if ($auctions) { ?>
                <?php foreach ($auctions as $auction) {
                  if ($auction['end_date'] < getdate()) {?>
                <tr class="table-danger">
                  <?php } else { ?>
                    <tr class="table-success">
                  <?php }; ?>
                  <td class="text-center"><?php if (in_array($auction['auction_id'], $selected)) { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $auction['auction_id']; ?>" checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $auction['auction_id']; ?>" />
                    <?php } ?></td>
                  <td class="text-center"><?php if ($auction['image']) { ?>
                    <img src="<?php echo $auction['image']; ?>" alt="<?php echo $auction['title']; ?>" class="img-thumbnail" />
                    <?php } else { ?>
                    <span class="img-thumbnail list"><i class="fa fa-camera fa-2x"></i></span>
                    <?php } ?></td>
                  <td class="text-left"><?php echo $auction['type']; ?></td>
                  <td class="text-left"><?php echo $auction['title']; ?></td>
                  <td class="text-left"><?php echo $auction['seller']; ?></td>
                  <td class="text-left"><?php echo $auction['date_created']; ?></td>
                  <td class="text-left"><?php echo $auction['start_date']; ?></td>
                  <td class="text-left"><?php echo $auction['end_date']; ?></td>
                  <td class="text-left"><?php echo $auction['auction_status']; ?></td>
                  <td class="text-right"><a href="<?php echo $auction['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a></td>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="10"><?php echo $text_no_results; ?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </form>
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
$('#button-filter').on('click', function() {
	var url = 'index.php?route=catalog/auction&token=<?php echo $token; ?>';

	var filter_seller = $('input[name=\'filter_seller\']').val();

	if (filter_seller) {
		url += '&filter_seller=' + encodeURIComponent(filter_seller);
	}

	var filter_auction_status = $('select[name=\'filter_auction_status\']').val();

	if (filter_auction_status != '*') {
		url += '&filter_auction_status=' + encodeURIComponent(filter_auction_status);
	}

  var filter_auction_type = $('select[name=\'filter_auction_type\']').val();

	if (filter_auction_type != '*') {
		url += '&filter_auction_type=' + encodeURIComponent(filter_auction_type);
	}
  
  var filter_date_created = $('input[name=\'filter_date_created\']').val();

	if (filter_date_created) {
		url += '&filter_date_created=' + encodeURIComponent(filter_date_created);
	}

  var filter_start_date = $('input[name=\'filter_start_date\']').val();

	if (filter_start_date) {
		url += '&filter_start_date=' + encodeURIComponent(filter_start_date);
	}

  var filter_end_date = $('input[name=\'filter_end_date\']').val();

	if (filter_end_date) {
		url += '&filter_end_date=' + encodeURIComponent(filter_end_date);
	}

  
	location = url;
});
//--></script>
  <script type="text/javascript"><!--
$('input[name=\'filter_seller\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/auction/autocomplete&token=<?php echo $token; ?>&filter_seller=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['seller'],
						value: item['auction_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'filter_seller\']').val(item['label']);
	}
});

$('input[name=\'filter_model\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_model=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['model'],
						value: item['product_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'filter_model\']').val(item['label']);
	}
});
//--></script>
<script src="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
  <link href="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css" type="text/css" rel="stylesheet" media="screen" />
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script>
</div>
<?php echo $footer; ?>