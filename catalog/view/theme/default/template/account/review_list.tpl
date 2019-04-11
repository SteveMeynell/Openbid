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
      <h1><?php echo $heading_title; ?></h1>
      <?php if ($reviews) { ?>
        <?php foreach ($reviews as $group => $review) { ?>
          <legend class="text-center">Reviews made as a <?php echo $group; ?></legend>
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <td class="text-right"><?php echo $column_auction_title; ?></td>
                    <td class="text-left"><?php echo $column_seller; ?></td>
                    <td class="text-left"><?php echo $column_bidder; ?></td>
                    <td class="text-right"><?php echo $column_date_added; ?></td>
                    <td></td>
                  </tr>
                </thead>
                <tbody>
                <?php foreach ($review as $items) { 
                  if($items['state'] == 'review') {
                    $btnClass = 'danger';
                    $faClass = 'comment';
                  } elseif($items['state'] == 'view') {
                    $btnClass = 'success';
                    $faClass = 'eye';
                  } else {
                    $btnClass = 'info';
                    $faClass = 'envelope';
                  } ?>
                  <tr>
                    <td class="text-right">#<?php echo $items['auction_title']; ?></td>
                    <td class="text-left"><?php echo $items['sellername']; ?></td>
                    <td class="text-left"><?php echo $items['biddername']; ?></td>
                    <td class="text-right"><?php echo $items['date_added']; ?></td>
                    <?php if($items['state'] == 'view') { ?>
                    <td class="text-right">
                      <button type="button" id="button-view" class="btn btn-<?php echo $btnClass; ?> button-view" data-toggle="tooltip" title="<?php echo $items['view']; ?>" data-review-id="<?php echo $items['review_id']; ?>" data-loading-text="<i class='icon-spinner icon-spin icon-large'></i>">
                      <i class="fa fa-<?php echo $faClass; ?>"></i></button>
                    </td>
                    <?php } else { ?>
                      <td class="text-right">
                      <button type="button" id="button-remind" class="btn btn-<?php echo $btnClass; ?> button-remind" data-toggle="tooltip" title="<?php echo $items['view']; ?>" data-review-id="<?php echo $items['review_id']; ?>" data-loading-text="<i class='icon-spinner icon-spin icon-large'></i>">
                      <i class="fa fa-<?php echo $faClass; ?>"></i></button>
                      </td>
                    <?php } ?>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        <?php } ?>
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>
      <?php } else { ?>
      <p><?php echo $text_empty; ?></p>
      <?php } ?>
      <div class="buttons clearfix">
        <div class="pull-right"><a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a></div>
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
  var reviewId = button.data('review-id') // Extract info from data-* attributes
  // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
  // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
  $.ajax({
		url: 'index.php?route=account/review/view',
		type: 'get',
		dataType: 'json',
		data: {review_id: reviewId},
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

      if (json['review']) {
        var modal = $("#reviewModal");
        var sellerName = json['review']['seller']['firstname'];
        var bidderName = json['review']['bidder']['firstname'];
        var sellerCommunication = json['review']['seller']['seller_question1'];
        var sellerSuggestion1 = json['review']['seller']['question1_suggestion'];
        var sellerShipping=json['review']['seller']['seller_question2'];
        var sellerSuggestion2 = json['review']['seller']['question2_suggestion'];
        var sellerSatisfaction=json['review']['seller']['seller_question3'];
        var sellerSuggestion3 = json['review']['seller']['question3_suggestion'];
        var sellerfinalSuggestion = json['review']['seller']['seller_suggestion'];

        var bidderCommunication = json['review']['bidder']['bidder_question1'];
        var bidderSuggestion1 = json['review']['bidder']['question1_suggestion'];
        var bidderShipping=json['review']['bidder']['bidder_question2'];
        var bidderSuggestion2 = json['review']['bidder']['question2_suggestion'];
        var bidderSatisfaction=json['review']['bidder']['bidder_question3'];
        var bidderSuggestion3 = json['review']['bidder']['question3_suggestion'];
        var bidderfinalSuggestion = json['review']['bidder']['bidder_suggestion'];
        
        //console.log(modal);
        modal.find('.modal-title').text('Auction Reviewed On - ' + json['review']['auction_title']);
        modal.find('.modal-body').html(
          '<div>What ' + sellerName + ' said about ' + bidderName + ' or the site:<br>' + 
          '<p>' + sellerfinalSuggestion + '</p><br>' +
          'Communication: ' + countingStars(sellerCommunication) + '<br>' + 
          'Comment: ' + sellerSuggestion1 + '</br>' +
          'Shipping: ' + countingStars(sellerShipping) + '<br>' + 
          'Comment: ' + sellerSuggestion2 + '</br>' +
          'Satisfaction: ' + countingStars(sellerSatisfaction) + '<br>' + 
          'Comment: ' + sellerSuggestion3 + '</br>' +
          '</div><br><br>' + 
          '<div>What ' + bidderName + ' said about ' + sellerName + ' or the site:<br>' + 
          '<p>' + bidderfinalSuggestion + '</p><br>' +
          'Communication: ' + countingStars(bidderCommunication) + '<br>' + 
          'Comment: ' + bidderSuggestion1 + '</br>' +
          'Shipping: ' + countingStars(bidderShipping) + '<br>' + 
          'Comment: ' + bidderSuggestion2 + '</br>' +
          'Satisfaction: ' + countingStars(bidderSatisfaction) + '<br>' + 
          'Comment: ' + bidderSuggestion3 + '</br>' +
          '</div>');
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

$('.button-remind').click(function (e) {
  //console.log(e);
  var button = $(this) // Button that triggered the modal
  var reviewId = button.data('review-id') // Extract info from data-* attributes
  $.ajax({
		url: 'index.php?route=account/review/sendReminder',
		type: 'get',
		dataType: 'json',
		data: {review_id: reviewId},
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

function countingStars(rating) {
  var zeroStar = '<span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>';
  var oneStar = '<span class="fa fa-stack"><i class="fa fa-star fa-stack-1x"></i><i class="fa fa-star-o fa-stack-1x"></i></span>';
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