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
      <?php if ($auction_id) { ?>
        <div class="row">
          <div class="col-sm-6">
            <label class="control-label" for="auction-title"><?php echo $text_auction_title; ?></label>
            <input type="text" id="auction-title" class="form-control" value="<?php echo $auction_title;?>" disabled />
            <?php if($allow_subtitles) { ?>
              <label class="control-label" for="auction-subtitle"><?php echo $text_auction_subtitle; ?></label>
              <input type="text" id="auction-subtitle" class="form-control" value="<?php echo $auction_subtitle;?>" disabled />
            <?php } ?>
            <label class="control-label" for="auction-description"><?php echo $text_auction_description; ?></label>
            <textarea cols="50" rows="10" id="auction-description" class="form-control" readonly>
              <?php echo $auction_description; ?>
            </textarea>
            <label class="control-label" for="auction-tags"><?php echo $text_auction_tags; ?></label>
            <input type="text" id="auction-tags" class="form-control" value="<?php echo $auction_tags;?>" disabled />
            <div class = "col-sm-3">
              <label class="control-label" for="auction-type"><?php echo $text_auction_type; ?></label>
              <input type="text" id="auction-type" class="form-control" value="<?php echo $auction_type;?>" disabled />
            </div>
            <div class = "col-sm-6">
              <label class="control-label" for="auction-duration"><?php echo $text_auction_duration; ?></label>
              <input type="text" id="auction-duration" class="form-control" value="<?php echo $auction_duration;?>" disabled />
            </div>
            <div class = "col-sm-3">
              <label class="control-label" for="auction-views"><?php echo $text_auction_views; ?></label>
              <input type="text" id="auction-views" class="form-control" size = "5" maxlength = "5" value="<?php echo $auction_views;?>" disabled />
            </div>
          </div>
          <div class="col-sm-6">
            <ul class="thumbnails">
              <?php if ($thumb) { ?>
                <li><a class="thumbnail" href="<?php echo $popup; ?>" title="<?php echo $heading_title; ?>"><img src="<?php echo $thumb; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" /></a></li>
              <?php } ?>
              <?php if ($images && $allow_extra_images) { ?>
                <?php foreach ($images as $image) { ?>
                  <li class="image-additional"><a class="thumbnail" href="<?php echo $image['popup']; ?>" title="<?php echo $heading_title; ?>"> <img src="<?php echo $image['thumb']; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" /></a></li>
                <?php } ?>
              <?php } ?>
            </ul>
            <details>
                <summary style="display=block" id="auction-prices"><h4><?php echo $text_auction_top_bid . $top_bid; ?></h4></summary>
                <li><?php echo $text_auction_starting_bid . $starting_bid; ?></li>
                <li><?php echo $text_auction_reserve_bid . $reserve_bid; ?></li>
                <li><?php echo $text_auction_buy_now_price . $buy_now_price; ?></li>
              </details>
            <details>
                <summary style="display=block"><h4><?php echo $text_auction_options_available; ?></h4></summary>
                <?php foreach ($options as $option) { ?>
                  <li><?php echo $option['name'] . ': ' . $option['toggle']; ?></li>
                <?php } ?>
              </details>
          </div>
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
