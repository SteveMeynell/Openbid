<h3><?php echo $heading_title; ?></h3>
  <div class="row">
    <?php foreach ($auctions as $auction) { ?>
        <div class="product-layout col-lg-6 col-md-3 col-sm-6 col-xs-12" id="auction-main-<?php echo $auction['auction_id']; ?>">
          <div class="product-thumb transition">
            <div class="image"><img src="<?php echo $auction['thumb']; ?>" alt="<?php echo $auction['name']; ?>" title="<?php echo $auction['name']; ?>" class="img-responsive" /></a></div>
            <div class="caption">
              <h4><?php echo $auction['name']; ?></a></h4>
              <div class="text-justify"><?php echo $auction['description']; ?></div>
              <h3><?php echo $text_starting_in; ?></h3>
              <h4>
                <div id="auction-id" class="auction-id" hidden="<?php echo $auction['auction_id']; ?>"></div>
                <div id="starting_in_time" class="starting_in_time" hidden="<?php echo $auction['start_date']; ?>"></div>
                <div class="startingTime" id="time_remaining"></div>
              </h4>
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
            </div>
            <div class="button-group">
              <button type="button" data-toggle="tooltip" title="<?php echo $button_wishlist; ?>" onclick="wishlist.add('<?php echo $auction['auction_id']; ?>');"><i class="fa fa-heart"></i></button>
              <button type="button" data-toggle="tooltip" title="<?php echo $button_compare; ?>" onclick="compare.add('<?php echo $auction['auction_id']; ?>');"><i class="fa fa-exchange"></i></button>
            </div>
          </div>
        </div>
    <?php } ?>
  </div>

