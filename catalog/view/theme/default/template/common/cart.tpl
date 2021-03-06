<div id="cart" class="btn-group btn-block">
  <button type="button" data-toggle="dropdown" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-inverse btn-block btn-lg dropdown-toggle"><i class="fa fa-gavel"></i> <span id="cart-total"><?php echo $text_items; ?></span></button>
  <ul class="dropdown-menu pull-right">
    <?php if ($auctions) { ?>
    <li>
      <table class="table table-striped">
        <?php foreach ($auctions as $auction) { ?>
        <tr>
          <td class="text-center"><?php if ($auction['thumb']) { ?>
            <a href="<?php echo $auction['href']; ?>"><img src="<?php echo $auction['thumb']; ?>" alt="<?php echo $auction['name']; ?>" title="<?php echo $auction['name']; ?>" class="img-thumbnail" /></a>
            <?php } ?></td>
          <td class="text-left"><a href="<?php echo $auction['href']; ?>"><?php echo $auction['name']; ?></a>
            <?php if ($auction['recurring']) { ?>
            <br />
            - <small><?php echo $text_recurring; ?> <?php echo $auction['recurring']; ?></small>
            <?php } ?></td>
          <td class="text-right"><?php echo $auction['num_fees']; ?> Fees @ <?php echo $auction['amount']; ?></td>
          <td class="text-right"><?php echo $auction['total']; ?></td>
          
          <td class="text-center"><button type="button" onclick="cart.pay('<?php echo $auction['cart_id']; ?>');" title="<?php echo $button_view_fees; ?>" class="btn btn-danger btn-xs"><i class="fa fa-dollar"></i></button></td>
        </tr>
        <?php } ?>
      </table>
    </li>
    <li>
      <div>
        <table class="table table-bordered">
          <?php foreach ($totals as $total) { ?>
          <tr>
            <td class="text-right"><strong><?php echo $total['title']; ?></strong></td>
            <td class="text-right"><?php echo $total['text']; ?></td>
          </tr>
          <?php } ?>
        </table>
        <p class="text-right"><a href="<?php echo $cart; ?>"><strong><i class="fa fa-shopping-cart"></i> <?php echo $text_cart; ?></strong></a>&nbsp;&nbsp;&nbsp;<a href="<?php echo $checkout; ?>"><strong><i class="fa fa-share"></i> <?php echo $text_checkout; ?></strong></a></p>
      </div>
    </li>
    <?php } else { ?>
    <li>
      <p class="text-center"><?php echo $text_empty; ?></p>
    </li>
    <?php } ?>
  </ul>
</div>
