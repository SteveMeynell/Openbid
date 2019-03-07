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
      <h1><?php echo $heading_title; ?></h1>
      <?php if ($bids) { ?>
      <div class="table-responsive">
        <table class="table table-bordered table-hover">
          <thead>
            <tr>
              <td class="text-right"><?php echo $column_bid_id; ?></td>
              <td class="text-right"><?php echo $column_auction_id; ?></td>
              <td class="text-left"><?php echo $column_title; ?></td>
              <td class="text-right"><?php echo $column_bid_date; ?></td>
              <td class="text-right"><?php echo $column_total; ?></td>
              <td class="text-left"><?php echo $column_winner; ?></td>
              <td class="text-left"><?php echo $column_action; ?></td>
              <td></td>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($bids['history'] as $bid) { ?>
            <tr>
              <td class="text-right">#<?php echo $bid['bid_id']; ?></td>
              <td class="text-right">#<?php echo $bid['auction_id']; ?></td>
              <td class="text-left"><?php echo $bid['title']; ?></td>
              <td class="text-left"><?php echo $bid['bid_date']; ?></td>
              <td class="text-left"><?php echo $bid['bid_amount']; ?></td>
              <td class="text-left"><?php echo $bid['winner']; ?></td>
              <td class="text-right"><a href="<?php echo $bid['view']; ?>" data-toggle="tooltip" title="<?php echo $button_view; ?>" class="btn btn-info"><i class="fa fa-eye"></i></a></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
      <div class="row">
        <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
        <div class="col-sm-6 text-right"><?php echo $results; ?></div>
      </div>
      <?php } else { ?>
      <p><?php echo $text_empty; ?></p>
      <?php } ?>
      <div class="buttons clearfix">
        <div class="pull-right"><a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a></div>
      </div>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>
