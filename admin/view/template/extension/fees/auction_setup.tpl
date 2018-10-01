<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        
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
        <form action="<?php echo $action; ?>" id="form-auction-setup" class="form-horizontal">
            <div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $text_description; ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
            <div class="form-group">
              
              <table class="table table-hover table-bordered table-sm" id="feesInput">
                <thead class="thead-dark">
                  <tr>
                    <th scope="col-auto"><?php echo $column_from; ?></th>
                    <th scope="col-auto"><?php echo $column_to; ?></th>
                    <th scope="col-auto"><?php echo $column_amount; ?></th>
                    <th scope="col-auto"><?php echo $column_type; ?></th>
                    <th scope="col-auto"><?php echo $column_action; ?></th>
                  </tr>
                </thead>
                <tbody>
                <tr>
                    <td>
                      <input type="text" class="form-control" name="fromAmount" id="fromAmount" aria-label="Amount (to the nearest dollar)">
                    </td>
                    <td>
                      <input type="text" class="form-control" name="toAmount" id="toAmount" aria-label="Amount (to the nearest dollar)">
                    </td>
                    <td>
                      <input type="text" class="form-control" name="feeAmount" id="feeAmount" aria-label="Amount (to the nearest dollar)">
                    </td>
                    <td>
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="feeType" id="exampleRadios1" value="flat" checked>
                        <label class="form-check-label" for="exampleRadios1">
                          Flat Fee
                        </label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="feeType" id="exampleRadios2" value="percent">
                        <label class="form-check-label" for="exampleRadios2">
                          Percent Fee
                        </label>
                      </div>
                    </td>
                    <td>
                      <button type="submit" class="btn btn-secondary btn-sm" id="saveButton" onclick="auction.addFeeSetting();">Add Fee</button>
                    </td>
                  </tr>
                <tr>
              </table>
              <table class="table table-hover table-bordered table-sm" id="feesList">
                <thead class="thead-dark" id="listOfFees">
                  <tr>
                    <th scope="col-auto">Row #</th>
                    <th scope="col-auto"><?php echo $column_from; ?></th>
                    <th scope="col-auto"><?php echo $column_to; ?></th>
                    <th scope="col-auto"><?php echo $column_amount; ?></th>
                    <th scope="col-auto"><?php echo $column_type; ?></th>
                    <th scope="col-auto"><?php echo $column_status; ?></th>
                    <th scope="col-auto"><?php echo $column_action; ?></th>
                  </tr>
                </thead>
                <tbody>
              </table>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>

