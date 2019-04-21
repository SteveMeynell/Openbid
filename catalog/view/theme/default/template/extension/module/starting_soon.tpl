<h3><?php echo $heading_title; ?></h3>
  <div class="row">
    <?php foreach ($auctions as $auction) { ?>
        <div class="product-layout col-lg-6 col-md-3 col-sm-6 col-xs-12" id="auction-module-<?php echo $auction['auction_id']; ?>">
          <div class="product-thumb transition">
            <div class="image">
              <img src="<?php echo $auction['thumb']; ?>" alt="<?php echo $auction['name']; ?>" title="<?php echo $auction['name']; ?>" class="img-responsive" />
              <img id="opening_auction" class="overlayImage" src="<?php echo $auction['opening_image']; ?>"/>
            </div>
            <div class="caption">
              <h4><?php echo $auction['name']; ?></a></h4>
              <div class="rating">
                <?php for ($i = 1; $i <= 5; $i++) { ?>
                  <?php if ($auction['rating'] < $i) { ?>
                    <span class="fa fa-stack"><i class="far fa-star fa-stack-2x"></i></span>
                  <?php } else { ?>
                    <span class="fa fa-stack"><i class="fas fa-star fa-stack-2x"></i><i class="far fa-star fa-stack-2x"></i></span>
                  <?php } ?>
                <?php } ?>
              </div>
              <div class="text-justify"><?php echo $auction['description']; ?></div>
              <h3><?php echo $text_starting_in; ?></h3>
              <h4>
                <div id="auction-id" class="auction-id" hidden="<?php echo $auction['auction_id']; ?>"></div>
                <div id="starting_in_time" class="starting_in_time" hidden="<?php echo $auction['start_date']; ?>"></div>
                <div class="startingTime" id="time_remaining"></div>
              </h4>
            </div>
            <div class="button-group">
              <button type="button" data-toggle="tooltip" title="<?php echo $button_wishlist; ?>" onclick="wishlist.add('<?php echo $auction['auction_id']; ?>');"><i class="fa fa-heart"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $button_wishlist; ?></span></button>
            </div>
          </div>
        </div>
    <?php } ?>
  </div>

