<div class="list-group">
  <?php if (!$logged) { ?>
  <a href="<?php echo $login; ?>" class="list-group-item"><?php echo $text_login; ?></a> <a href="<?php echo $register; ?>" class="list-group-item"><?php echo $text_register; ?></a> <a href="<?php echo $forgotten; ?>" class="list-group-item"><?php echo $text_forgotten; ?></a>
  <?php } ?>
  <a href="<?php echo $account; ?>" class="list-group-item"><?php echo $text_account; ?></a>
  <?php if ($logged) { ?>
  <a href="<?php echo $edit; ?>" class="list-group-item"><?php echo $text_edit; ?></a> <a href="<?php echo $password; ?>" class="list-group-item"><?php echo $text_password; ?></a>
  <?php } ?>
  <a href="<?php echo $address; ?>" class="list-group-item"><?php echo $text_address; ?></a> 
  <a href="<?php echo $wishlist; ?>" class="list-group-item"><?php echo $text_wishlist; ?></a> 
  <a href="<?php echo $order; ?>" class="list-group-item"><?php echo $text_order; ?></a> 
  <a href="<?php echo $auctions; ?>" class="list-group-item"><?php echo $text_auction; ?></a>
  <a href="<?php echo $bids; ?>" class="list-group-item"><?php echo $text_bid; ?></a> 
  <a href="<?php echo $fees; ?>" class="list-group-item"><?php echo $text_fee; ?></a> 
  <a href="<?php echo $reviews; ?>" class="list-group-item"><?php echo $text_review; ?></a> 
  <a href="<?php echo $transaction; ?>" class="list-group-item"><?php echo $text_transaction; ?></a> 
  <a href="<?php echo $newsletter; ?>" class="list-group-item"><?php echo $text_newsletter; ?></a>
  <?php if ($logged) { ?>
  <a href="<?php echo $logout; ?>" class="list-group-item"><?php echo $text_logout; ?></a>
  <?php } ?>
</div>
