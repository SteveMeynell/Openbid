<h3><?php echo $heading_title; ?></h3>
<div class="row">
  <?php foreach ($auctions as $auction) { ?>
  <div class="product-layout col-lg-6 col-md-3 col-sm-6 col-xs-12">
    <div class="product-thumb transition">
      <div class="image"><a href="<?php echo $auction['href']; ?>"><img src="<?php echo $auction['thumb']; ?>" alt="<?php echo $auction['name']; ?>" title="<?php echo $auction['name']; ?>" class="img-responsive" /></a></div>
      <div class="caption">
        <h4><a href="<?php echo $auction['href']; ?>"><?php echo $auction['name']; ?></a></h4>
        <p><?php echo $auction['description']; ?></p>
        <p><?php echo $text_viewed . ' ' . $auction['views']; ?></p>
        <?php if ($auction['rating']) { ?>
        <div class="rating">
          <?php for ($i = 1; $i <= 5; $i++) { ?>
          <?php if ($auction['rating'] < $i) { ?>
          <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>
          <?php } else { ?>
          <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span>
          <?php } ?>
          <?php } ?>
        </div>
        <?php } ?>
        <p class="price">
          <?php if (!$auction['current_bid'] && !$auction['buy_now']) { ?>
            <span class="price-new"><?php echo $text_please_login; ?></span>
            <?php } else { ?>
              <?php if (!$auction['buy_now_only']) { ?>
                <span class="price-new"><?php echo $text_buy_now; ?> <?php echo $auction['buy_now']; ?></span>
                <span class="price-new"><?php echo $text_current_bid; ?></span> <span class="price-new"><?php echo $auction['current_bid']; ?></span>
              <?php } else { ?>
                <span class="price-new"><?php echo $text_buy_now_only; ?></span> <span class="price-new"><?php echo $auction['buy_now']; ?></span>
              <?php } ?>
          <?php } ?>
        </p>
      </div>
      <div class="button-group">
        <button type="button" onclick="cart.add('<?php echo $auction['auction_id']; ?>');"><i class="fa fa-shopping-cart"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $button_bid; ?></span></button>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_wishlist; ?>" onclick="wishlist.add('<?php echo $auction['auction_id']; ?>');"><i class="fa fa-heart"></i></button>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_compare; ?>" onclick="compare.add('<?php echo $auction['auction_id']; ?>');"><i class="fa fa-exchange"></i></button>
      </div>
    </div>
  </div>
  <?php } ?>
</div>
