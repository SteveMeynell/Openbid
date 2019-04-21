<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right"><a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-bid-increments').submit() : false;"><i class="fas fa-trash"></i></button>
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
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-bid-increments">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                  <td class="text-left"><?php if ($sort == 'low_bid') { ?>
                    <a href="<?php echo $sort_low_bid; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_low_bid; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_low_bid; ?>"><?php echo $column_low_bid; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'high_bid') { ?>
                    <a href="<?php echo $sort_high_bid; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_high_bid; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_high_bid; ?>"><?php echo $column_high_bid; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'increment') { ?>
                    <a href="<?php echo $sort_increment; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_increment; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_increment; ?>"><?php echo $column_increment; ?></a>
                    <?php } ?></td>
                  <td class="text-right"><?php echo $column_action; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php if ($increments) { ?>
                <?php foreach ($increments as $increment) { ?>
                <tr>
                  <td class="text-center"><?php if (in_array($increment['increment_id'], $selected)) { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $increment['increment_id']; ?>" checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $increment['increment_id']; ?>" />
                    <?php } ?></td>
                  <td class="text-left"><?php echo $increment['low_bid']; ?></td>
                  <td class="text-left"><?php echo $increment['high_bid']; ?></td>
                  <td class="text-left"><?php echo $increment['increment']; ?></td>
                  <td class="text-right"><a href="<?php echo $increment['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fas fa-pencil-alt"></i></a></td>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="5"><?php echo $text_no_results; ?></td>
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
</div>
<?php echo $footer; ?>