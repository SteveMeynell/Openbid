<?php if ($reviews) { ?>
<?php foreach ($reviews as $review) { ?>
<table class="table table-striped table-bordered">
  <tr>
    <td style="width: 50%;"><strong>Reviewed By: </strong><?php echo $review['bidder']; ?></td>
    <td class="text-right">Reviewed On: <?php echo $review['date_added']; ?></td>
  </tr>
  <tr>
    <td colspan="2"><p><strong>Suggestions: </strong><?php echo ($review['text'] == '')?'No comment':$review['text']; ?></p>
      <?php foreach($review['ratings'] as $name => $rating) { ?>
        <p><?php echo ucfirst($name); ?></p>
        <?php for ($i = 1; $i <= 5; $i++) { ?>
          <?php if ($rating < $i) { ?>
            <span class="fa fa-stack"><i class="far fa-star fa-stack-2x"></i></span>
          <?php } else { ?>
            <span class="fa fa-stack"><i class="fas fa-star fa-stack-2x"></i><i class="far fa-star fa-stack-2x"></i></span>
          <?php } ?>
        <?php } ?>
        <br>
      <?php } ?>
    </td>
  </tr>
</table>
<?php } ?>
<div class="text-right"><?php echo $pagination; ?></div>
<?php } else { ?>
<p><?php echo $text_no_reviews; ?></p>
<?php } ?>
