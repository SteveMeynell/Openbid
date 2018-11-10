<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title"><i class="fa fa-gavel"></i> <?php echo $heading_title; ?></h3>
  </div>
  <div class="table-responsive">
    <table class="table">
      <thead>
        <tr>
          <td class="text-right"><?php echo $column_auction_id; ?></td>
          <td><?php echo $column_seller; ?></td>
          <td><?php echo $column_date_added; ?></td>
          <td class="text-right"><?php echo $column_reserve; ?></td>
          <td class="text-right"><?php echo $column_buy_now; ?></td>
          <td class="text-right"><?php echo $column_action; ?></td>
        </tr>
      </thead>
      <tbody>
        <?php if ($auctions) { ?>
        <?php foreach ($auctions as $auction) { ?>
        <tr>
          <td class="text-right"><?php echo $auction['auction_id']; ?></td>
          <td><?php echo $auction['seller']; ?></td>
          <td><?php echo $auction['date_created']; ?></td>
          <td class="text-right"><?php echo $auction['reserve_bid']; ?></td>
          <td class="text-right"><?php echo $auction['buy_now']; ?></td>
          <td class="text-right"><a href="<?php echo $auction['view']; ?>" data-toggle="tooltip" title="<?php echo $button_view; ?>" class="btn btn-info"><i class="fa fa-eye"></i></a></td>
        </tr>
        <?php } ?>
        <?php } else { ?>
        <tr>
          <td class="text-center" colspan="6"><?php echo $text_no_results; ?></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</div>
