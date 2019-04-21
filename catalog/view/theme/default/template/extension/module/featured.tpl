<h3><?php echo $heading_title; ?></h3>
<div class="row">
  <?php foreach ($auctions as $auction) { ?>
  <div class="product-layout col-lg-6 col-md-3 col-sm-6 col-xs-12" id="auction-module-<?php echo $auction['auction_id']; ?>">
    <div class="product-thumb transition">
      <div class="image"><a href="<?php echo $auction['href']; ?>"><img src="<?php echo $auction['thumb']; ?>" alt="<?php echo $auction['title']; ?>" title="<?php echo $auction['title']; ?>" class="img-responsive" /></a></div>
      <div class="caption">
        <h3><a href="<?php echo $auction['href']; ?>"><?php echo $auction['title']; ?></a></h3>
        <h4><a href="<?php echo $auction['href']; ?>"><?php echo $auction['subtitle']; ?></a></h4>
        <div class="rating">
          <?php for ($i = 1; $i <= 5; $i++) { ?>
          <?php if ($auction['rating'] < $i) { ?>
          <span class="fa fa-stack"><i class="far fa-star fa-stack-2x"></i></span>
          <?php } else { ?>
          <span class="fa fa-stack"><i class="fas fa-star fa-stack-2x"></i><i class="far fa-star fa-stack-2x"></i></span>
          <?php } ?>
          <?php } ?>
        </div>
        <p><?php echo $auction['description']; ?></p>
        <p class="price">
              <?php if (!$auction['current_bid'] && !$auction['buy_now']) { ?>
                <span class="price-new"><?php echo $text_please_login; ?></span>
                <?php } else { ?>
                  <?php if (!$auction['buy_now_only']) { ?>
                    <?php if ($auction['want_buy_now']) { ?>
                      <span class="price-new"><?php echo $text_buy_now; ?> <?php echo $auction['buy_now']; ?></span>
                    <?php } ?>
                    <span class="price-new"><?php echo $text_current_bid; ?></span> <span class="price-new"><?php echo $auction['current_bid']; ?></span>
                  <?php } else { ?>
                    <span class="price-new"><?php echo $text_buy_now_only; ?></span> <span class="price-new"><?php echo $auction['buy_now']; ?></span>
                  <?php } ?>
              <?php } ?>
            </p>
      </div>
      <div class="button-group">
        <button type="button" data-toggle="tooltip" title="<?php echo $button_wishlist; ?>" onclick="wishlist.add('<?php echo $auction['auction_id']; ?>');"><i class="fa fa-heart"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $button_wishlist; ?></span></button>
      </div>
    </div>
  </div>
  <?php } ?>
</div>
