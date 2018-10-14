<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" id="button-shipping" form="form-auction" formaction="<?php echo $shipping; ?>" formtarget="_blank" data-toggle="tooltip" title="<?php echo $button_shipping_print; ?>" class="btn btn-info"><i class="fa fa-truck"></i></button>
        <button type="submit" id="button-invoice" form="form-auction" formaction="<?php echo $invoice; ?>" formtarget="_blank" data-toggle="tooltip" title="<?php echo $button_invoice_print; ?>" class="btn btn-info"><i class="fa fa-print"></i></button>
        <a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
        <button type="button" id="button-delete" form="form-auction" formaction="<?php echo $delete; ?>" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger"><i class="fa fa-trash-o"></i></button>
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
                <label class="control-label" for="input-auction-id"><?php echo $entry_auction_id; ?></label>
                <input type="text" name="filter_auction_id" value="<?php echo $filter_auction_id; ?>" placeholder="<?php echo $entry_auction_id; ?>" id="input-auction-id" class="form-control" />
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
                <label class="control-label" for="input-customer"><?php echo $entry_seller; ?></label>
                <input type="text" name="filter_seller" value="<?php echo $filter_seller; ?>" placeholder="<?php echo $entry_seller; ?>" id="input-seller" class="form-control" />
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
        <form method="post" action="" enctype="multipart/form-data" id="form-auction">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                  <td class="text-right"><?php if ($sort == 'auction_id') { ?>
                    <a href="<?php echo $sort_auction; ?>" class="<?php echo strtolower($auction); ?>"><?php echo $column_auction_id; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_auction; ?>"><?php echo $column_auction_id; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'seller') { ?>
                    <a href="<?php echo $sort_seller; ?>" class="<?php echo strtolower($auction); ?>"><?php echo $column_seller; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_seller; ?>"><?php echo $column_seller; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'status') { ?>
                    <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($auction); ?>"><?php echo $column_status; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                    <?php } ?></td>
                  <td class="text-right"><?php if ($sort == 'title') { ?>
                    <a href="<?php echo $sort_title; ?>" class="<?php echo strtolower($auction); ?>"><?php echo $column_title; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_title; ?>"><?php echo $column_title; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'date_created') { ?>
                    <a href="<?php echo $sort_date_created; ?>" class="<?php echo strtolower($auction); ?>"><?php echo $column_date_created; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_date_created; ?>"><?php echo $column_date_created; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'start_date') { ?>
                    <a href="<?php echo $sort_start_date; ?>" class="<?php echo strtolower($auction); ?>"><?php echo $column_start_date; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_start_date; ?>"><?php echo $column_start_date; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'end_date') { ?>
                    <a href="<?php echo $sort_end_date; ?>" class="<?php echo strtolower($auction); ?>"><?php echo $column_end_date; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_end_date; ?>"><?php echo $column_end_date; ?></a>
                    <?php } ?></td>
                  <td class="text-right"><?php echo $column_action; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php if ($auctions) { ?>
                <?php foreach ($auctions as $auction) { ?>
                <tr>
                  <td class="text-center"><?php if (in_array($auction['auction_id'], $selected)) { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $auction['auction_id']; ?>" checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $auction['auction_id']; ?>" />
                    <?php } ?>
                    <input type="hidden" name="shipping_code[]" value="<?php echo $auction['shipping_code']; ?>" /></td>
                  <td class="text-right"><?php echo $auction['auction_id']; ?></td>
                  <td class="text-left"><?php echo $auction['seller']; ?></td>
                  <td class="text-left"><?php echo $auction['auction_status']; ?></td>
                  <td class="text-right"><?php echo $auction['title']; ?></td>
                  <td class="text-left"><?php echo $auction['date_created']; ?></td>
                  <td class="text-left"><?php echo $auction['start_date']; ?></td>
                  <td class="text-left"><?php echo $auction['end_date']; ?></td>
                  <td class="text-right"><a href="<?php echo $auction['view']; ?>" data-toggle="tooltip" title="<?php echo $button_view; ?>" class="btn btn-info"><i class="fa fa-eye"></i></a> <a href="<?php echo $auction['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a></td>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="8"><?php echo $text_no_results; ?></td>
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
	url = 'index.php?route=auction/auction&token=<?php echo $token; ?>';

	var filter_auction_id = $('input[name=\'filter_auction_id\']').val();

	if (filter_auction_id) {
		url += '&filter_auction_id=' + encodeURIComponent(filter_auction_id);
	}

	var filter_customer = $('input[name=\'filter_customer\']').val();

	if (filter_customer) {
		url += '&filter_customer=' + encodeURIComponent(filter_customer);
	}

	var filter_auction_status = $('select[name=\'filter_auction_status\']').val();

	if (filter_auction_status != '*') {
		url += '&filter_auction_status=' + encodeURIComponent(filter_auction_status);
	}

	var filter_total = $('input[name=\'filter_total\']').val();

	if (filter_total) {
		url += '&filter_total=' + encodeURIComponent(filter_total);
	}

	var filter_date_created = $('input[name=\'filter_date_created\']').val();

	if (filter_date_created) {
		url += '&filter_date_created=' + encodeURIComponent(filter_date_created);
	}

	var filter_date_modified = $('input[name=\'filter_date_modified\']').val();

	if (filter_date_modified) {
		url += '&filter_date_modified=' + encodeURIComponent(filter_date_modified);
	}

	location = url;
});
//--></script> 
  <script type="text/javascript"><!--
$('input[name=\'filter_customer\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=customer/customer/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['customer_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'filter_customer\']').val(item['label']);
	}
});
//--></script> 
  <script type="text/javascript"><!--
$('input[name^=\'selected\']').on('change', function() {
	$('#button-shipping, #button-invoice').prop('disabled', true);

	var selected = $('input[name^=\'selected\']:checked');

	if (selected.length) {
		$('#button-invoice').prop('disabled', false);
	}

	for (i = 0; i < selected.length; i++) {
		if ($(selected[i]).parent().find('input[name^=\'shipping_code\']').val()) {
			$('#button-shipping').prop('disabled', false);

			break;
		}
	}
});

$('#button-shipping, #button-invoice').prop('disabled', true);

$('input[name^=\'selected\']:first').trigger('change');

// IE and Edge fix!
$('#button-shipping, #button-invoice').on('click', function(e) {
	$('#form-auction').attr('action', this.getAttribute('formAction'));
});

$('#button-delete').on('click', function(e) {
	$('#form-auction').attr('action', this.getAttribute('formAction'));
	
	if (confirm('<?php echo $text_confirm; ?>')) {
		$('#form-auction').submit();
	} else {
		return false;
	}
});
//--></script> 
  <script src="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
  <link href="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css" type="text/css" rel="stylesheet" media="screen" />
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script></div>
<?php echo $footer; ?> 