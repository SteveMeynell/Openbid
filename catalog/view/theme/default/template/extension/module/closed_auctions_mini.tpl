<h3><?php echo $heading_title; ?></h3>
<div class="row">
  <?php foreach ($auctions as $auction) { ?>
  <div class="product-layout col-lg-6 col-md-3 col-sm-6 col-xs-12">
    <div class="product-thumb transition">
      <div class="image">
        <a href="<?php echo $auction['href']; ?>">
          <img src="<?php echo $auction['thumb']; ?>" alt="<?php echo $auction['title']; ?>" title="<?php echo $auction['title']; ?>" class="img-responsive" />
          <img id="closed_auction" class="overlayImage" src="<?php echo $auction['overlay_image']; ?>"/>
        </a>
      </div>
      <div class="caption">
        <h3><a href="<?php echo $auction['href']; ?>"><?php echo $auction['title']; ?></a></h3>
        <h4><a href="<?php echo $auction['href']; ?>"><?php echo $auction['subtitle']; ?></a></h4>
        <p><?php echo $auction['description']; ?></p>
        <p>Seller: <?php echo $auction['seller']; ?></p>
        <div class="rating">
          <?php for ($i = 1; $i <= 5; $i++) { ?>
          <?php if ($auction['rating'] < $i) { ?>
          <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>
          <?php } else { ?>
          <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span>
          <?php } ?>
          <?php } ?>
        </div>
        <?php if ($auction['price']) { ?>
        <p class="price">Winning Bid: 
          <?php echo $auction['price']; ?>
        </p>
        <?php } ?>
      </div>
    </div>
  </div>
  <?php } ?>
</div>
