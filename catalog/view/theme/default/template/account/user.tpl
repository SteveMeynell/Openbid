<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($success) { ?>
  <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?></div>
  <?php } ?>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>">
      <?php echo $content_top; ?>
      <div class="row">
        <div class="panel panel-info">
          <div class="panel-heading">
            <h1 class="panel-title"><?php echo $heading_user; ?></h1>
          </div>
          <div class="panel-body">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title"><?php echo $heading_user_info; ?></h3>
              </div>
              <div class="panel-body">
                <div class="col-sm-12">
                  <div class="col-sm-6">
                    <div class="row">
                      <div class="col-sm-1 text-right"><?php echo $text_username; ?></div>
                      <div class="col-sm-1"><?php echo $firstname; ?></div>
                    </div>
                    <div class="row">
                      <div class="col-sm-3">
                        <label><?php echo $heading_seller; ?></label>
                      </div>
                      <div class="col-sm-6">
                        <div class="rating">
                          <p>
                            <?php for ($i = 1; $i <= 5; $i++) { ?>
                              <?php if (($seller_info['star_rating']['communication'] + $seller_info['star_rating']['shipping'] + $seller_info['star_rating']['quality'])/3 < $i) { ?>
                                <span class="fa fa-stack"><i class="far fa-star fa-stack-1x"></i></span>
                              <?php } else { ?>
                                <span class="fa fa-stack"><i class="fas fa-star fa-stack-1x"></i><i class="far fa-star fa-stack-1x"></i></span>
                              <?php } ?>
                            <?php } ?>
                            <button type="button" id="button-view" class="btn btn-info button-view" data-toggle="tooltip" title="Reviews as a Seller" data-review-type="seller" data-loading-text="<i class='icon-spinner icon-spin icon-large'></i>">
                        <i class="fa fa-comment"></i> Reviews</button>
                          </p>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-4 text-left"><?php echo $text_total_auctions; ?></div>
                      <div class="col-sm-1 text-left"><?php echo $seller_info['stats']['total_auctions']; ?></div>
                      <div class="col-sm-2 text-right"><?php echo $text_auctions_rank; ?></div>
                      <div class="col-sm-1 text-left"><?php echo $seller_info['stats']['total_auctions_rank']; ?></div>
                    </div>
                    <div class="row">
                      <div class="col-sm-4 text-left"><?php echo $text_total_items_sold; ?></div>
                      <div class="col-sm-1 text-left"><?php echo $seller_info['stats']['successful_auctions']; ?></div>
                      <div class="col-sm-2 text-right"><?php echo $text_auctions_rank; ?></div>
                      <div class="col-sm-1 text-left"><?php echo $seller_info['stats']['successful_auctions_rank']; ?></div>
                    </div>
                    <div class="row">
                      <div class="col-sm-4 text-left"><?php echo $text_total_views; ?></div>
                      <div class="col-sm-1 text-left"><?php echo $seller_info['stats']['total_views']; ?></div>
                      <div class="col-sm-2 text-right"><?php echo $text_auctions_rank; ?></div>
                      <div class="col-sm-1 text-left"><?php echo $seller_info['stats']['total_views_rank']; ?></div>
                    </div>
                    <div class="row">
                      <div class="col-sm-4 text-left"><?php echo $text_highest_winning_bid; ?></div>
                      <div class="col-sm-1 text-left"><?php echo $seller_info['stats']['highest_winning_bid']; ?></div>
                    </div>
                    <div class="row">
                      <div class="col-sm-4 text-left"><?php echo $text_highest_bid_received; ?></div>
                      <div class="col-sm-1 text-left"><?php echo $seller_info['stats']['highest_bid_received']; ?></div>
                    </div>
                    <div class="row">
                      <div class="col-sm-4 text-left"><?php echo $text_most_bids_received; ?></div>
                      <div class="col-sm-1 text-left"><?php echo $seller_info['stats']['most_bids']; ?></div>
                    </div>

                  </div>


                  <div class="col-sm-6">
                    <div class="row">
                      <div class="col-sm-3 text-left"><?php echo $text_membership; ?></div>
                      <div class="col-sm-3"><?php echo $membersince; ?></div>
                    </div>
                  
                  <div class="row">
                      <div class="col-sm-3">
                        <label><?php echo $heading_bidder; ?></label>
                      </div>
                      <div class="col-sm-6">
                        <div class="rating">
                          <p>
                            <?php for ($i = 1; $i <= 5; $i++) { ?>
                              <?php if (($bidder_info['star_rating']['communication'] + $bidder_info['star_rating']['shipping'] + $bidder_info['star_rating']['quality'])/3 < $i) { ?>
                                <span class="fa fa-stack"><i class="far fa-star fa-stack-1x"></i></span>
                              <?php } else { ?>
                                <span class="fa fa-stack"><i class="fas fa-star fa-stack-1x"></i><i class="far fa-star fa-stack-1x"></i></span>
                              <?php } ?>
                            <?php } ?>
                            <button type="button" id="button-view" class="btn btn-info button-view" data-toggle="tooltip" title="Reviews as a Bidder" data-review-type="bidder" data-loading-text="<i class='icon-spinner icon-spin icon-large'></i>">
                        <i class="fa fa-comment"></i> Reviews</button>
                          </p>
                        </div>
                      </div>
                  </div>
                    <div class="row">
                      <div class="col-sm-5 text-left"><?php echo $text_total_bids_placed; ?></div>
                      <div class="col-sm-1 text-left"><?php echo $bidder_info['stats']['numBids']; ?></div>
                    </div>
                    <div class="row">
                      <div class="col-sm-5 text-left"><?php echo $text_total_bids_won; ?></div>
                      <div class="col-sm-1 text-left"><?php echo $bidder_info['stats']['numWinningBids']; ?></div>
                    </div>
                    <div class="row">
                      <div class="col-sm-5 text-left"><?php echo $text_max_bid_placed; ?></div>
                      <div class="col-sm-1 text-left"><?php echo $bidder_info['stats']['maxBid']; ?></div>
                    </div>
                    <div class="row">
                      <div class="col-sm-5 text-left"><?php echo $text_num_auctions_bid; ?></div>
                      <div class="col-sm-1 text-left"><?php echo $bidder_info['stats']['numAuctions']; ?></div>
                    </div>
                  </div>
                </div>
              </div>
              </div>
            </div>
          
            <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title"><?php echo $heading_featured; ?></h3>
              </div>
              <div class="panel-body">
                
                <div class="row">
                  <?php if(isset($auctions['featured'])) { ?>
                    <?php foreach ($auctions['featured'] as $auction) { ?>
                    <div class="product-layout col-lg-2 col-md-3 col-sm-3 col-xs-12" id="auction-module-<?php echo $auction['auction_id']; ?>">
                      <div class="product-thumb transition">
                        <div class="image"><a href="<?php echo $auction['href']; ?>"><img src="<?php echo $auction['thumb']; ?>" alt="<?php echo $auction['title']; ?>" title="<?php echo $auction['title']; ?>" class="img-responsive" /></a></div>
                        <div class="caption">
                          <h3><a href="<?php echo $auction['href']; ?>"><?php echo $auction['title']; ?></a></h3>
                          <h4><a href="<?php echo $auction['href']; ?>"><?php echo $auction['subtitle']; ?></a></h4>
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
                  <?php } else { ?>
                    <div> Nothing Yet! </div>
                  <?php } ?>
                </div>

              </div>
            </div>

            <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title"><?php echo $heading_created; ?></h3>
              </div>
              <div class="panel-body">
                
                <div class="row">
                  <?php if(isset($auctions['created'])) { ?>
                    <?php foreach ($auctions['created'] as $auction) { ?>
                    <div class="product-layout col-lg-2 col-md-3 col-sm-3 col-xs-12" id="auction-module-<?php echo $auction['auction_id']; ?>">
                      <div class="product-thumb transition">
                        <div class="image"><img src="<?php echo $auction['thumb']; ?>" alt="<?php echo $auction['title']; ?>" title="<?php echo $auction['title']; ?>" class="img-responsive" /></div>
                        <div class="caption">
                          <h3><?php echo $auction['title']; ?></h3>
                          <h4><?php echo $auction['subtitle']; ?></h4>
                          <p><?php echo $auction['description']; ?></p>
                        </div>
                        <div class="button-group">
                          <button type="button" data-toggle="tooltip" title="<?php echo $button_wishlist; ?>" onclick="wishlist.add('<?php echo $auction['auction_id']; ?>');"><i class="fa fa-heart"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $button_wishlist; ?></span></button>
                        </div>
                      </div>
                    </div>
                    <?php } ?>
                  <?php } else { ?>
                    <div> Nothing Yet! </div>
                  <?php } ?>
                </div>

              </div>
            </div>

            <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title"><?php echo $heading_open; ?></h3>
              </div>
              <div class="panel-body">
                
                <div class="row">
                  <?php if(isset($auctions['open'])) { ?>
                    <?php foreach ($auctions['open'] as $auction) { ?>
                    <div class="product-layout col-lg-2 col-md-3 col-sm-3 col-xs-12" id="auction-module-<?php echo $auction['auction_id']; ?>">
                      <div class="product-thumb transition">
                        <div class="image"><a href="<?php echo $auction['href']; ?>"><img src="<?php echo $auction['thumb']; ?>" alt="<?php echo $auction['title']; ?>" title="<?php echo $auction['title']; ?>" class="img-responsive" /></a></div>
                        <div class="caption">
                          <h3><a href="<?php echo $auction['href']; ?>"><?php echo $auction['title']; ?></a></h3>
                          <h4><a href="<?php echo $auction['href']; ?>"><?php echo $auction['subtitle']; ?></a></h4>
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
                  <?php } else { ?>
                    <div> Nothing Open Yet! </div>
                  <?php } ?>
                </div>

              </div>
            </div>

            <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title"><?php echo $heading_closed; ?></h3>
              </div>
              <div class="panel-body">
                
                <div class="row">
                  <?php if(isset($auctions['closed'])) { ?>
                    <?php foreach ($auctions['closed'] as $auction) { ?>
                    <div class="product-layout col-lg-2 col-md-3 col-sm-3 col-xs-12">
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
                          <?php if ($auction['price']) { ?>
                          <p class="price">Winning Bid: 
                            <?php echo $auction['price']; ?>
                          </p>
                          <?php } ?>
                        </div>
                      </div>
                    </div>
                    <?php } ?>
                  <?php } else { ?>
                    <div> Nothing Yet! </div>
                  <?php } ?>
                </div>

              </div>
            </div>

          </div>
        </div>
      </div>
      <?php echo $content_bottom; ?>
    </div>
    <?php echo $column_right; ?>
  </div>
</div>
<?php echo $footer; ?> 

<!-- Modal -->
<div class="modal fade" id="reviewModal" tabindex="-1" role="dialog" aria-labelledby="reviewModalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="reviewModalTitle"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
$('.button-view').click(function (e) {
  //console.log(e);
  var button = $(this) // Button that triggered the modal
  var reviewType = button.data('review-type') // Extract info from data-* attributes
  // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
  // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
  $.ajax({
		url: 'index.php?route=account/user/review',
		type: 'get',
		dataType: 'json',
		data: {review_type: reviewType, user_id: <?php echo $userId;?>},
		beforeSend: function() {
			button.button('loading');
		},
		complete: function() {
			button.button('reset');
		},
		success: function(json) {
			$('.alert-success, .alert-danger').remove();

			if (json['error']) {
        alert(json['error']);
				$('#review').after('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
			}

      if (json['reviews']) {
        var modalmessage = '';
        var modal = $("#reviewModal");
        if(json['reviews'].hasOwnProperty('seller')) {
          var reviews = json['reviews']['seller'];
          modal.find('.modal-title').text('Reviews as a Seller');
          for(review in reviews) {
            modalmessage += '<div class="content"><div class="row col-sm-12">What ' + reviews[review]['firstname'] + ' said on ' + reviews[review]['date_added'] + '</div>';
            modalmessage += '<div class="row col-sm-3">Suggestions: </div><div class="col-sm-9">' + reviews[review]['bidder_suggestion'] +'</div>';
            modalmessage += '<div class="row col-sm-3">Communication: </div><div class="col-sm-9">' + countingStars(reviews[review]['ratings']['communications']) + '</div>';
            modalmessage += '<div class="row col-sm-3">Shipping: </div><div class="col-sm-9">' + countingStars(reviews[review]['ratings']['shipping']) + '</div>';
            modalmessage += '<div class="row col-sm-3">Satisfaction: </div><div class="col-sm-9">' + countingStars(reviews[review]['ratings']['quality']) + '</div></div>';
          }
        modal.find('.modal-body').html(modalmessage);
        }
          /*
            var sellerName = review['firstname'];
            //var sellerCommunication = review['seller_question1'];
            //var sellerSuggestion1 = review['question1_suggestion'];
            //var sellerShipping= review['seller_question2'];
            //var sellerSuggestion2 = review['question2_suggestion'];
            //var sellerSatisfaction= review['seller_question3'];
            //var sellerSuggestion3 = review['question3_suggestion'];
            //var sellerfinalSuggestion = review['seller_suggestion'];
            modalmessage += '<div>What ' + sellerName + ' said about ' + bidderName + ' or the site:<br>' + 
            '<p>' + sellerfinalSuggestion + '</p><br>' +
            'Communication: ' + countingStars(sellerCommunication) + '<br>' + 
            'Comment: ' + sellerSuggestion1 + '</br>' +
            'Shipping: ' + countingStars(sellerShipping) + '<br>' + 
            'Comment: ' + sellerSuggestion2 + '</br>' +
            'Satisfaction: ' + countingStars(sellerSatisfaction) + '<br>' + 
            'Comment: ' + sellerSuggestion3 + '</br>' +
            '</div><br><br>';
          });*/

          
        if(json['reviews'].hasOwnProperty('bidder')) {
          var reviews = json['reviews']['bidder'];
          modal.find('.modal-title').text('Reviews as a Bidder');
          for(review in reviews) {
            modalmessage += '<div class="content"><div class="row col-sm-12">What ' + reviews[review]['firstname'] + ' said on ' + reviews[review]['date_added'] + '</div>';
            modalmessage += '<div class="row col-sm-3">Suggestions: </div><div class="col-sm-9">' + reviews[review]['seller_suggestion'] +'</div>';
            modalmessage += '<div class="row col-sm-3">Communication: </div><div class="col-sm-9">' + countingStars(reviews[review]['ratings']['communications']) + '</div>';
            modalmessage += '<div class="row col-sm-3">Shipping: </div><div class="col-sm-9">' + countingStars(reviews[review]['ratings']['shipping']) + '</div>';
            modalmessage += '<div class="row col-sm-3">Satisfaction: </div><div class="col-sm-9">' + countingStars(reviews[review]['ratings']['quality']) + '</div></div>';
          }
          modal.find('.modal-title').text('Bidder Reviews');
          modal.find('.modal-body').html(modalmessage);
        }



          /*
          var bidderName = json['reviews']['bidder']['firstname'];
          var bidderCommunication = json['reviews']['bidder']['bidder_question1'];
          var bidderSuggestion1 = json['reviews']['bidder']['question1_suggestion'];
          var bidderShipping=json['reviews']['bidder']['bidder_question2'];
          var bidderSuggestion2 = json['reviews']['bidder']['question2_suggestion'];
          var bidderSatisfaction=json['reviews']['bidder']['bidder_question3'];
          var bidderSuggestion3 = json['reviews']['bidder']['question3_suggestion'];
          var bidderfinalSuggestion = json['reviews']['bidder']['bidder_suggestion'];
        
        
        modal.find('.modal-title').text('Review');

        }

        
        if(json['reviews'].hasOwnProperty('bidder')) {
          modal.find('.modal-body').html(  
            '<div class="col-sm-12">What ' + bidderName + ' said about ' + sellerName + ' or the site:<br>' + 
            '<p>' + bidderfinalSuggestion + '</p><br>' +
            '<div class="col-sm-3">Communication: </div><div class="col-sm-4">' + countingStars(bidderCommunication) + '</div><br>' + 
            'Comment: ' + bidderSuggestion1 + '</br>' +
            'Shipping: ' + countingStars(bidderShipping) + '<br>' + 
            'Comment: ' + bidderSuggestion2 + '</br>' +
            'Satisfaction: ' + countingStars(bidderSatisfaction) + '<br>' + 
            'Comment: ' + bidderSuggestion3 + '</br>' +
            '</div>');
        }

        */
        modal.modal('show');
      }
			if (json['success']) {
				//$('#review').after('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');
        alert(json['success']);	
        if (json['redirect']) {
          location = json['redirect'];
        }
			}
      
		}
	});
  
});

function testme(value){
  console.log("ok got here");
  console.log(value);
  this.text = 'What ' + value['firstname'] + ' said on ' + value['date_added'];
  
   //modal.find('.modal-title').text('What ' + value['firstname'] + ' said on ' + value['date_added']);
}

function countingStars(rating) {
  var zeroStar = '<span class="fa fa-stack"><i class="far fa-star fa-stack-1x"></i></span>';
  var oneStar = '<span class="fa fa-stack"><i class="fas fa-star fa-stack-1x"></i><i class="far fa-star fa-stack-1x"></i></span>';
  var starRating="";
  for(starCount=1;starCount<=5;starCount++) {
            if(rating < starCount) {
              starRating = starRating.concat(zeroStar);
            } else {
              starRating = starRating.concat(oneStar);
            }
          }
  return starRating;
}
</script>