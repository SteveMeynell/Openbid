<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-general-ledger" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-general-ledger" class="form-horizontal">
          <div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $text_description; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
          </div>
          
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-asset"><?php echo $entry_asset_accounts; ?></label>
            <div class="col-sm-10">
              <input type="text" name="accounting_asset_account" value="<?php echo $asset_account_start; ?>" placeholder="<?php echo $suggest_asset_accounts; ?>" id="input-asset" class="form-control" />
              <?php if ($error_asset) { ?>
                <div class="text-danger"><?php echo $error_asset; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-liability"><?php echo $entry_liability_accounts; ?></label>
            <div class="col-sm-10">
              <input type="text" name="accounting_liability_account" value="<?php echo $liability_account_start; ?>" placeholder="<?php echo $suggest_liability_accounts; ?>" id="input-liability" class="form-control" />
              <?php if ($error_liability) { ?>
                <div class="text-danger"><?php echo $error_liability; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-capital"><?php echo $entry_capital_accounts; ?></label>
            <div class="col-sm-10">
              <input type="text" name="accounting_capital_account" value="<?php echo $capital_account_start; ?>" placeholder="<?php echo $suggest_capital_accounts; ?>" id="input-capital" class="form-control" />
              <?php if ($error_capital) { ?>
                <div class="text-danger"><?php echo $error_capital; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-revenue"><?php echo $entry_revenue_accounts; ?></label>
            <div class="col-sm-10">
              <input type="text" name="accounting_revenue_account" value="<?php echo $revenue_account_start; ?>" placeholder="<?php echo $suggest_revenue_accounts; ?>" id="input-revenue" class="form-control" />
              <?php if ($error_revenue) { ?>
                <div class="text-danger"><?php echo $error_revenue; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-expense"><?php echo $entry_expense_accounts; ?></label>
            <div class="col-sm-10">
              <input type="text" name="accounting_expense_account" value="<?php echo $expense_account_start; ?>" placeholder="<?php echo $suggest_expense_accounts; ?>" id="input-expense" class="form-control" />
              <?php if ($error_expense) { ?>
                <div class="text-danger"><?php echo $error_expense; ?></div>
              <?php } ?>
            </div>
          </div>
          
        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>