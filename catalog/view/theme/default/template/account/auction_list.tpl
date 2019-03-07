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
      <?php if ($auctions) { ?>
      <div class="table-responsive">
        <table class="table table-bordered table-hover">
          <thead>
            <tr>
              <td class="text-right"><?php echo $column_auction_id; ?></td>
              <td class="text-left"><?php echo $column_title; ?></td>
              <td class="text-left"><?php echo $column_start_date; ?></td>
              <td class="text-left"><?php echo $column_end_date; ?></td>
              <td class="text-left"><?php echo $column_views; ?></td>
              <td class="text-right"><?php echo $column_bids; ?></td>
              <td class="text-left"><?php echo $column_type; ?></td>
              <td class="text-right"><?php echo $column_reserve_bid; ?></td>
              <td class="text-right"><?php echo $column_buy_now_price; ?></td>
              <td class="text-right"><?php echo $column_top_bid; ?></td>
              <td class="text-left"><?php echo $column_status; ?></td>
              <td class="text-left"><?php echo $column_action; ?></td>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($auctions as $auction) { ?>
            <tr>
              <td class="text-right">#<?php echo $auction['auction_id']; ?></td>
              <td class="text-left"><?php echo $auction['title']; ?></td>
              <td class="text-left"><?php echo $auction['date_start']; ?></td>
              <td class="text-left"><?php echo $auction['date_ended']; ?></td>
              <td class="text-left"><?php echo $auction['views']; ?></td>
              <td class="text-left"><?php echo $auction['bids']; ?></td>
              <td class="text-left"><?php echo $auction['type']; ?></td>
              <td class="text-left"><?php echo $auction['reserve_bid']; ?></td>
              <td class="text-left"><?php echo $auction['buy_now_price']; ?></td>
              <td class="text-left"><?php echo $auction['top_bid']; ?></td>
              <td class="text-right"><?php echo $auction['status']; ?></td>              
              <td class="text-right"><a href="<?php echo $auction['view']['link']; ?>" data-toggle="tooltip" title="<?php echo $button_view; ?>" class="btn btn-<?php echo $auction['view']['button_colour']; ?>"><i class="fa fa-<?php echo $auction['view']['button_pic']; ?>"></i></a></td>
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
