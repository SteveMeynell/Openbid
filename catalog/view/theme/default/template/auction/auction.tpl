<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <div class="row">
        <?php if ($column_left || $column_right) { ?>
        <?php $class = 'col-sm-6'; ?>
        <?php } else { ?>
        <?php $class = 'col-sm-8'; ?>
        <?php } ?>
        <div class="<?php echo $class; ?> auction-main" id="auction-main" value="<?php echo $auction_id; ?>">
          <?php if ($thumb || $images) { ?>
          <ul class="thumbnails">
            <?php if ($thumb) { ?>
            <li>
              <a class="thumbnail" href="<?php echo $popup; ?>" title="<?php echo $heading_title; ?>">
                <img src="<?php echo $thumb; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" />
                <img id="closed_auction" class="overlayImage" src="<?php echo $closed_image; ?>"/>
              </a>
            </li>
            <?php } ?>
            <?php if ($images) { ?>
            <?php foreach ($images as $image) { ?>
            <li class="image-additional"><a class="thumbnail" href="<?php echo $image['popup']; ?>" title="<?php echo $heading_title; ?>"> <img src="<?php echo $image['thumb']; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" /></a></li>
            <?php } ?>
            <?php } ?>
          </ul>
          <?php } ?>
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-description" data-toggle="tab"><?php echo $tab_description; ?></a></li>
            <?php if ($review_status) { ?>
            <li><a href="#tab-review" data-toggle="tab"><?php echo $tab_review; ?></a></li>
            <?php } ?>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab-description"><?php echo $description; ?></div>
            <?php if ($review_status) { ?>
            <div class="tab-pane" id="tab-review">
              <form class="form-horizontal" id="form-review">
                <div id="review"></div>
                <h2><?php echo $text_write; ?></h2>
                <?php if ($review_guest) { ?>
                <div class="form-group required">
                  <div class="col-sm-12">
                    <label class="control-label" for="input-name"><?php echo $entry_name; ?></label>
                    <input type="text" name="name" value="<?php echo $customer_name; ?>" id="input-name" class="form-control" />
                  </div>
                </div>
                <div class="form-group required">
                  <div class="col-sm-12">
                    <label class="control-label" for="input-review"><?php echo $entry_review; ?></label>
                    <textarea name="text" rows="5" id="input-review" class="form-control"></textarea>
                    <div class="help-block"><?php echo $text_note; ?></div>
                  </div>
                </div>
                <div class="form-group required">
                  <div class="col-sm-12">
                    <label class="control-label"><?php echo $entry_rating; ?></label>
                    &nbsp;&nbsp;&nbsp; <?php echo $entry_bad; ?>&nbsp;
                    <input type="radio" name="rating" value="1" />
                    &nbsp;
                    <input type="radio" name="rating" value="2" />
                    &nbsp;
                    <input type="radio" name="rating" value="3" />
                    &nbsp;
                    <input type="radio" name="rating" value="4" />
                    &nbsp;
                    <input type="radio" name="rating" value="5" />
                    &nbsp;<?php echo $entry_good; ?></div>
                </div>
                <?php echo $captcha; ?>
                <div class="buttons clearfix">
                  <div class="pull-right">
                    <button type="button" id="button-review" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary"><?php echo $button_continue; ?></button>
                  </div>
                </div>
                <?php } else { ?>
                <?php echo $text_login; ?>
                <?php } ?>
              </form>
            </div>
            <?php } ?>
          </div>
        </div>
        <?php if ($column_left || $column_right) { ?>
        <?php $class = 'col-sm-6'; ?>
        <?php } else { ?>
        <?php $class = 'col-sm-4'; ?>
        <?php } ?>
        <div class="<?php echo $class; ?>">
          <h1><?php echo $heading_title; ?></h1>
          <p><?php echo $text_viewed . ' ' . $views; ?></p>
          <p><?php echo $text_watching . ' ' . $watches; ?></p>
          <h3><?php echo $text_ending_in; ?></h3>
            <h4>
              <div id="starting_in_time" class="starting_in_time" hidden="<?php echo $end_date; ?>"></div>
              <div class="startingTime" id="time_remaining"></div>
            </h4>
          <?php if (!$current_bid && !$buy_now) { ?>
          <p class="price">
            <h2><span class="price-new"><?php echo $text_please_login; ?></span></h2>
          <?php } else { ?>
            <?php if (!$buy_now_only) { ?>
              <?php if ($want_buy_now) { ?>
                <h2><span class="price-new"><?php echo $text_buy_now; ?> <?php echo $buy_now; ?></span></h2>
              <?php } ?>
              <?php if ($reserve_bid) {
                if ($reserve_bid_amount <= $current_bid_amount) { 
                  if ($reserve_bid_amount == '0') { ?>
                    <h2><span class="price-new"><?php echo $text_reserved_bid; ?> <?php echo $text_no_reserved_bid; ?></span></h2>
                  <?php } else { ?>
                    <h2><span class="price-new"><?php echo $text_reserved_bid; ?> <?php echo $text_reserved_bid_met; ?></span></h2>
                  <?php } ?>
                <?php } else { ?>
                  <h2><span class="price-new"><?php echo $text_reserved_bid; ?> <?php echo $reserve_bid; ?></span></h2>
                <?php }} ?>
                <h2><span class="price-new"><?php echo $text_current_bid; ?></span> <span class="price-new" id="current_bid"><?php echo $current_bid; ?></span></h2>
                <h2><span class="price-new" id="winningBidder"></span></h2>
                <?php if ($can_bid == 'yes'){ ?>
                  <div class="btn-group">
                    <button type="button" data-toggle="tooltip" class="btn btn-default" title="<?php echo $button_wishlist; ?>" onclick="wishlist.add('<?php echo $auction_id; ?>');"><i class="fa fa-heart"></i></button>
                    <?php if ($want_buy_now) { ?>
                      <button type="button" id="BuyNowButton" data-toggle="tooltip" class="btn btn-success" title="<?php echo $button_buynow; ?>" ><i class="fa fa-gavel"></i></button>
                    <?php } ?>
                    <button type="button" id="PlaceBidButton" data-toggle="tooltip" class="btn btn-primary"><i class="fa fa-gavel"></i></button>
                    <label>Next Minimum Bid</label>
                    <input type="text"class="auction price-new" id="proxy_amount" name="proxy_amount" placeholder="<?php echo $next_bid_text; ?>" data-toggle="tooltip" title="Anything higher than the minimum bid will be placed in proxy."></>
                  </div>
                <?php } 
             } else { ?>
              <h2><span class="price-new"><?php echo $text_buy_now_only; ?></span> <span class="price-new"><?php echo $buy_now; ?></span></h2>
              <?php if ($can_bid == 'yes'){ ?>
                <div class="btn-group">
                  <button type="button" data-toggle="tooltip" class="btn btn-default" title="<?php echo $button_wishlist; ?>" onclick="wishlist.add('<?php echo $auction_id; ?>');"><i class="fa fa-heart"></i></button>
                  <button type="button" id="BuyNowButton" data-toggle="tooltip" class="btn btn-success" title="<?php echo $button_buynow; ?>"><i class="fa fa-gavel"></i></button>
                </div>
            <?php }} ?>
          </p>
          <div id="serverData">
            <div class="table-responsive">
              <table class="table table-bordered table-hover" id="bidHistory">
              <thead class="thead-dark">
                <tr>
                  <td class="text-center" colspan="2">Latest Bids</td>
                </tr>
                <tr>
                  <td class="text-center">Bid#</td>
                  <td class="text-center">Bid Amount</td>
                </tr>
              </thead>
            </table>
            </div>
          </div>
        <?php } ?>
          <?php if ($review_status) { ?>
          <div class="rating">
            <p>
              <?php for ($i = 1; $i <= 5; $i++) { ?>
              <?php if ($rating < $i) { ?>
              <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
              <?php } else { ?>
              <span class="fa fa-stack"><i class="fa fa-star fa-stack-1x"></i><i class="fa fa-star-o fa-stack-1x"></i></span>
              <?php } ?>
              <?php } ?>
              <a href="" onclick="$('a[href=\'#tab-review\']').trigger('click'); return false;"><?php echo $reviews; ?></a> / <a href="" onclick="$('a[href=\'#tab-review\']').trigger('click'); return false;"><?php echo $text_write; ?></a></p>
            <hr>
            <!-- AddThis Button BEGIN -->
            <!-- <div class="addthis_toolbox addthis_default_style" data-url="<?php echo $share; ?>"><a class="addthis_button_facebook_like" fb:like:layout="button_count"></a> <a class="addthis_button_tweet"></a> <a class="addthis_button_pinterest_pinit"></a> <a class="addthis_counter addthis_pill_style"></a></div> -->
            <!-- <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-515eeaf54693130e"></script> -->
            <!-- AddThis Button END -->
          </div>
          <?php } ?>
        </div>
      </div>
      <?php if ($tags) { ?>
      <p><?php echo $text_tags; ?>
        <?php for ($i = 0; $i < count($tags); $i++) { ?>
        <?php if ($i < (count($tags) - 1)) { ?>
        <a href="<?php echo $tags[$i]['href']; ?>"><?php echo $tags[$i]['tag']; ?></a>,
        <?php } else { ?>
        <a href="<?php echo $tags[$i]['href']; ?>"><?php echo $tags[$i]['tag']; ?></a>
        <?php } ?>
        <?php } ?>
      </p>
      <?php } ?>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>

<script type="text/javascript"><!--
$('#review').delegate('.pagination a', 'click', function(e) {
    e.preventDefault();

    $('#review').fadeOut('slow');

    $('#review').load(this.href);

    $('#review').fadeIn('slow');
});

$('#review').load('index.php?route=auction/auction/review&auction_id=<?php echo $auction_id; ?>');

$('#button-review').on('click', function() {
	$.ajax({
		url: 'index.php?route=auction/auction/write&auction_id=<?php echo $auction_id; ?>',
		type: 'post',
		dataType: 'json',
		data: $("#form-review").serialize(),
		beforeSend: function() {
			$('#button-review').button('loading');
		},
		complete: function() {
			$('#button-review').button('reset');
		},
		success: function(json) {
			$('.alert-success, .alert-danger').remove();

			if (json['error']) {
				$('#review').after('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
			}

			if (json['success']) {
				$('#review').after('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');

				$('input[name=\'name\']').val('');
				$('textarea[name=\'text\']').val('');
				$('input[name=\'rating\']:checked').prop('checked', false);
			}
		}
	});
});


$(document).ready(function() {
	$('.thumbnails').magnificPopup({
		type:'image',
		delegate: 'a',
		gallery: {
			enabled:true
		}
	});
  getHistory();
  $("#closed_auction").hide();
  });

  function checkNewBids() {
    if($("#bidRow").length) {
      $.ajax({
      url: 'index.php?route=auction/auction/checkForNewBids',
      type: 'get',
      dataType: 'json',
      data: {auction_id: '<?php echo $auction_id; ?>', num_bids: $("#bidRow").length},
      success: function(json){
        if(json['newBids']) {
          getHistory();
        } 
      },
      error: function(textStatus){
        console.log("error");
        console.log(textStatus)
      }
      });
    }
  }

  function getHistory(){
  $.ajax({
    url: 'index.php?route=auction/auction/getBidHistory',
		type: 'get',
		dataType: 'json',
    data: {auction_id: '<?php echo $auction_id; ?>', min_bid: '<?php echo $min_bid; ?>'},
    success: function(json){
      if(json['bids'][0]){
        $("#proxy_amount").val("");
        for(i=json['bids'].length-1;i>=0;i--) {
          $("#bidHistory").append('<tr id="bidRow" class="bidRow"><td class="text-center" id="bidInfo">' + (i+1) +'</td><td class="text-center" id="bidAmount">' + json['bids'][i] + '</td></tr>');  
        }
        if(json['isUsersBid'][json['isUsersBid'].length-1] === '1'){
          $("#winningBidder").text("You have the highest bid.");
        }
      } else {
        $("#bidHistory").append('<tr class="bidRow"><td class="text-center" id="bidInfo" colspan="2">No Bids Yet!</td></tr>');
      }
      $("#PlaceBidButton").attr("title","Next Minimum Bid: " + json['nextBid']);
      $("#proxy_amount").attr("placeholder",json['nextBid']);
      if(json['currentBid']){
        $("#current_bid").text(json['currentBid']);
      } else {
        $("#current_bid").text("nothing");
      }
      },
    error: function(textStatus){
      console.log("error");
      console.log(textStatus)
    }
  });
  }
  function closeThisAuction(){
    $.ajax({
        url: 'index.php?route=auction/auction/Limbo',
        type: 'post',
        data: 'auction_id=<?php echo $auction_id; ?>',
        dataType: 'json',
        beforeSend: function() {
            $("#closed_auction").show();
            $(':button[type="button"]').prop('disabled', true);
            $("#starting_in_time").remove();
            $("#time_remaining").text("Closed!");
        },
        complete: function() {
            console.log("completed");
        },
        success: function(json) {
            if (json['redirect']) {
                location = json['redirect'];
            }

            if (json['success']) {
                console.log("success");
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
  }
$("#BuyNowButton").click(function(){
  $.ajax({
    url: 'index.php?route=auction/auction/BuyRightNow',
    type: 'post',
    dataType: 'json',
    data: {auction_id: '<?php echo $auction_id; ?>'},
    success: function(json){
      window.location.replace(json['url']);
      $(".bidRow").empty();
      getHistory();
    },
    error: function(statusText){
      console.log("error");
      console.log(statusText);
    }
  });
});
$("#PlaceBidButton").click(function(){
  var proxyBid = $("#proxy_amount").val();
  
  $.ajax({
    url: 'index.php?route=auction/auction/PlaceBid',
    type: 'post',
    dataType: 'json',
    data: {auction_id: '<?php echo $auction_id; ?>', bid_amount: proxyBid, min_bid: '<?php echo $min_bid; ?>'},
    success: function(json){
      console.log("success");
      console.log("should extend auction: " + json['extend']);
      $('#starting_in_time').attr("hidden", json['extend']);
      $(".bidRow").empty();
      getHistory();
    },
    error: function(statusText){
      console.log("error");
      console.log(statusText);
    }
  });
});

//--></script>
<?php echo $footer; ?>
