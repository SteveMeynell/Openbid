<h3><?php echo $heading_title; ?></h3>
<div class="row product-layout">
  <?php foreach ($auctions as $auction) { ?>
  <div class="auction-layout col-lg-3 col-md-3 col-sm-6 col-xs-12">
    <div class="auction-thumb transition">
      <div class="image"><a href="<?php echo $auction['href']; ?>"><img src="<?php echo $auction['thumb']; ?>" alt="<?php echo $auction['title']; ?>" title="<?php echo $auction['title']; ?>" class="img-responsive" /></a></div>
      <div class="caption">
        <h3><a href="<?php echo $auction['href']; ?>"><?php echo $auction['title']; ?></a></h3>
        <h4><a href="<?php echo $auction['href']; ?>"><?php echo $auction['subtitle']; ?></a></h4>
        <p><?php echo $auction['description']; ?></p>
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
        <?php if ($auction['price']) { ?>
        <p class="price">
          <?php if (!$auction['special']) { ?>
          <?php echo $auction['price']; ?>
          <?php } else { ?>
          <span class="price-new"><?php echo $auction['special']; ?></span> <span class="price-old"><?php echo $auction['price']; ?></span>
          <?php } ?>
          <?php if ($auction['tax']) { ?>
          <span class="price-tax"><?php echo $text_tax; ?> <?php echo $auction['tax']; ?></span>
          <?php } ?>
        </p>
        <?php } ?>
      </div>
      <div class="button-group">
        <button type="button" onclick="cart.add('<?php echo $auction['auction_id']; ?>');"><i class="fa fa-shopping-cart"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $button_cart; ?></span></button>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_wishlist; ?>" onclick="wishlist.add('<?php echo $auction['auction_id']; ?>');"><i class="fa fa-heart"></i></button>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_compare; ?>" onclick="compare.add('<?php echo $auction['auction_id']; ?>');"><i class="fa fa-exchange"></i></button>
      </div>
    </div>
  </div>
  <?php } ?>
</div>
