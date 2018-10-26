<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-product" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-auction" class="form-horizontal">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
            <li><a href="#tab-data" data-toggle="tab"><?php echo $tab_data; ?></a></li>
            <li><a href="#tab-options" data-toggle="tab"><?php echo $tab_options; ?></a></li>
            <li><a href="#tab-links" data-toggle="tab"><?php echo $tab_links; ?></a></li>
            <li><a href="#tab-image" data-toggle="tab"><?php echo $tab_image; ?></a></li>
            <li><a href="#tab-seller" data-toggle="tab"><?php echo $tab_seller; ?></a></li>
            <li><a href="#tab-bid-history" data-toggle="tab"><?php echo $tab_bid_history; ?></a></li>
            <li><a href="#tab-reviews" data-toggle="tab"><?php echo $tab_reviews; ?></a></li>
            <li><a href="#tab-design" data-toggle="tab"><?php echo $tab_design; ?></a></li>
            <li><a href="#tab-fees" data-toggle="tab"><?php echo $tab_fees; ?></a></li>
          </ul>
          
          <div class="tab-content">
            
            
            <div class="tab-pane active" id="tab-general">
              <ul class="nav nav-tabs" id="language">
                <?php foreach ($languages as $language) { ?>
                <li><a href="#language<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
                <?php } ?>
              </ul>
              <div class="tab-content">
                <?php foreach ($languages as $language) { ?>
                <div class="tab-pane" id="language<?php echo $language['language_id']; ?>">
                  <div class="form-group required">
                    <label class="col-sm-2 control-label" for="input-name<?php echo $language['language_id']; ?>"><?php echo $entry_name; ?></label>
                    <div class="col-sm-10">
                      <input type="text" name="auction_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($auction_description[$language['language_id']]) ? $auction_description[$language['language_id']]['name'] : ''; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name<?php echo $language['language_id']; ?>" class="form-control" />
                      <?php if (isset($error_name[$language['language_id']])) { ?>
                      <div class="text-danger"><?php echo $error_name[$language['language_id']]; ?></div>
                      <?php } ?>
                    </div>
                  </div>
                  <?php if ($allow_subtitles) { ?>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-subname<?php echo $language['language_id']; ?>"><?php echo $entry_subname; ?></label>
                    <div class="col-sm-10">
                      <input type="text" name="auction_description[<?php echo $language['language_id']; ?>][subname]" value="<?php echo isset($auction_description[$language['language_id']]) ? $auction_description[$language['language_id']]['subname'] : ''; ?>" placeholder="<?php echo $entry_subname; ?>" id="input-subname<?php echo $language['language_id']; ?>" class="form-control" />
                      <?php if (isset($error_subname[$language['language_id']])) { ?>
                      <div class="text-danger"><?php echo $error_subname[$language['language_id']]; ?></div>
                      <?php } ?>
                    </div>
                  </div>
                  <?php } ?>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-description<?php echo $language['language_id']; ?>"><?php echo $entry_description; ?></label>
                    <div class="col-sm-10">
                      <textarea name="auction_description[<?php echo $language['language_id']; ?>][description]" placeholder="<?php echo $entry_description; ?>" id="input-description<?php echo $language['language_id']; ?>" class="form-control summernote"><?php echo isset($auction_description[$language['language_id']]) ? $auction_description[$language['language_id']]['description'] : ''; ?></textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-meta-title<?php echo $language['language_id']; ?>"><?php echo $entry_meta_title; ?></label>
                    <div class="col-sm-10">
                      <input type="text" name="auction_description[<?php echo $language['language_id']; ?>][meta_title]" value="<?php echo isset($auction_description[$language['language_id']]) ? $auction_description[$language['language_id']]['meta_title'] : ''; ?>" placeholder="<?php echo $entry_meta_title; ?>" id="input-meta-title<?php echo $language['language_id']; ?>" class="form-control" />
                      <?php if (isset($error_meta_title[$language['language_id']])) { ?>
                      <div class="text-danger"><?php echo $error_meta_title[$language['language_id']]; ?></div>
                      <?php } ?>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-meta-description<?php echo $language['language_id']; ?>"><?php echo $entry_meta_description; ?></label>
                    <div class="col-sm-10">
                      <textarea name="auction_description[<?php echo $language['language_id']; ?>][meta_description]" rows="5" placeholder="<?php echo $entry_meta_description; ?>" id="input-meta-description<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($auction_description[$language['language_id']]) ? $auction_description[$language['language_id']]['meta_description'] : ''; ?></textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-meta-keyword<?php echo $language['language_id']; ?>"><?php echo $entry_meta_keyword; ?></label>
                    <div class="col-sm-10">
                      <textarea name="auction_description[<?php echo $language['language_id']; ?>][meta_keyword]" rows="5" placeholder="<?php echo $entry_meta_keyword; ?>" id="input-meta-keyword<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($auction_description[$language['language_id']]) ? $auction_description[$language['language_id']]['meta_keyword'] : ''; ?></textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-tag<?php echo $language['language_id']; ?>"><span data-toggle="tooltip" title="<?php echo $help_tag; ?>"><?php echo $entry_tag; ?></span></label>
                    <div class="col-sm-10">
                      <input type="text" name="auction_description[<?php echo $language['language_id']; ?>][tag]" value="<?php echo isset($auction_description[$language['language_id']]) ? $auction_description[$language['language_id']]['tag'] : ''; ?>" placeholder="<?php echo $entry_tag; ?>" id="input-tag<?php echo $language['language_id']; ?>" class="form-control" />
                    </div>
                  </div>
                </div>
                <?php } ?>
                <input type="hidden" name="date_created" value="<?php echo $date_created; ?>">
              </div>
            </div>
            
            
            <div class="tab-pane" id="tab-data">
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-auction-type"><?php echo $entry_type; ?></label>
                <div class="col-sm-2">
                  <select name="auction_type" id="input-auction-type" class="form-control">
                    <?php foreach ($types as $type) { ?>
                    <?php if ($type['type'] == $auction_type) { ?>
                    <option value="<?php echo $type['type']; ?>" selected="selected"><?php echo $type['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $type['type']; ?>"><?php echo $type['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
                <label class="col-sm-2 control-label" for="input-auction-status"><?php echo $entry_auction_status; ?></label>
                <div class="col-sm-2">
                  <select name="auction_status" id="input-auction-status" class="form-control">
                    <?php foreach ($statuses as $status) { ?>
                    <?php if ($status['auction_status_id'] == $auction_status) { ?>
                    <option value="<?php echo $status['auction_status_id']; ?>" selected="selected"><?php echo $status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $status['auction_status_id']; ?>"><?php echo $status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <?php if ($auction_type == '1') { ?>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-initial-quantity"><span data-toggle="tooltip" title="<?php echo $help_initial_quantity; ?>"><?php echo $entry_initial_quantity; ?></span></label>
                <div class="col-sm-2">
                  <input type="text" name="initial_quantity" value="<?php echo $initial_quantity; ?>" placeholder="<?php echo $entry_initial_quantity; ?>" id="input-initial-quantity" class="form-control" />
                </div>
              </div>
              <?php } ?>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-min-bid"><span data-toggle="tooltip" title="<?php echo $help_min_bid; ?>"><?php echo $entry_min_bid; ?></span></label>
                <div class="col-sm-2">
                  <input type="text" name="min_bid" value="<?php echo $min_bid; ?>" placeholder="<?php echo $entry_min_bid; ?>" id="input-min-bid" class="form-control" />
                </div>
                <label class="col-sm-2 control-label" for="input-reserve-price"><span data-toggle="tooltip" title="<?php echo $help_reserve_price; ?>"><?php echo $entry_reserve_price; ?></span></label>
                <div class="col-sm-2">
                  <input type="text" name="reserve_price" value="<?php echo $reserve_price; ?>" placeholder="<?php echo $entry_reserve_price; ?>" id="input-reserve-price" class="form-control" />
                </div>
              </div>
              <?php if($allow_custom_start_date) {?>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-date-start"><?php echo $entry_start_date; ?></label>
                <div class="col-sm-3">
                  <div class="input-group datetime">
                    <input type="text" name="custom_start_date" value="<?php echo $custom_start_date; ?>" placeholder="<?php echo $entry_start_date; ?>" data-date-format="YYYY-MM-DD H:m" id="input-date-start" class="form-control" />
                    <span class="input-group-btn">
                      <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                    </span>
                  </div>
                </div>
              </div>
              <?php } else { ?>
              <div><input type="hidden" name="custom_start_date" value="<?php echo $custom_start_date; ?>"></div>
              <?php };
               if($allow_custom_end_date) {?> 
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-date-end"><?php echo $entry_end_date; ?></label>
                <div class="col-sm-3">
                  <div class="input-group datetime">
                    <input type="text" name="custom_end_date" value="<?php echo $custom_end_date; ?>" placeholder="<?php echo $entry_end_date; ?>" data-date-format="YYYY-MM-DD H:m" id="input-date-end" class="form-control" />
                    <span class="input-group-btn">
                      <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                    </span>
                  </div>
                </div>
              </div>
              <?php } else { ?>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-duration"><span data-toggle="tooltip" title="<?php echo $help_duration; ?>"><?php echo $entry_duration; ?></span></label>
                  <div class="col-sm-3">
                    <select name="duration" id="input-duration" class="form-control">
                      <option value="0"><?php echo $text_none; ?></option>
                      <?php
                        foreach($durations as $duration_list) { 
                          if ($duration_list['duration'] == $duration) { ?>
                            <option value="<?php echo $duration_list['duration']; ?>" selected="selected"><?php echo $duration_list['description']; ?></option>
                            <?php } else { ?>
                              <option value="<?php echo $duration_list['duration']; ?>"><?php echo $duration_list['description']; ?></option>
                            <?php } 
                           } ?>
                    </select>
                  </div>
              </div>
              <?php };?>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-bid-increments"><span data-toggle="tooltip" title="<?php echo $help_bid_increments; ?>"><?php echo $entry_increments; ?></span></label>
                <div class="col-sm-6">
                  <table class="table table-sm table-dark table-bordered table-hover">
                    <caption><?php echo $text_bid_increments; ?></caption>
                    <thead class="thead-dark">
                      <tr>
                        <th class="text-right"><?php echo $text_bid_low; ?></th>
                        <th class="text-right"><?php echo $text_bid_high; ?></th>
                        <th class="text-right"><?php echo $text_bid_increment; ?></th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php foreach($bid_increments as $bid_increment) { ?>
                      <tr>
                        <td class="text-right"><?php echo $bid_increment['bid_low'];?></td>
                        <td class="text-right"><?php echo $bid_increment['bid_high'];?></td>
                        <td class="text-right"><?php echo $bid_increment['increment'];?></td>
                      </tr>
                    <?php }; ?>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_shipping; ?></label>
                <div class="col-sm-2">
                  <label class="radio-inline">
                    <?php if ($shipping) { ?>
                    <input type="radio" name="shipping" value="1" checked="checked" />
                    <?php echo $text_yes; ?>
                    <?php } else { ?>
                    <input type="radio" name="shipping" value="1" />
                    <?php echo $text_yes; ?>
                    <?php } ?>
                  </label>
                  <label class="radio-inline">
                    <?php if (!$shipping) { ?>
                    <input type="radio" name="shipping" value="0" checked="checked" />
                    <?php echo $text_no; ?>
                    <?php } else { ?>
                    <input type="radio" name="shipping" value="0" />
                    <?php echo $text_no; ?>
                    <?php } ?>
                  </label>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-shipping-cost"><span data-toggle="tooltip" title="<?php echo $help_shipping_cost; ?>"><?php echo $entry_shipping_cost; ?></span></label>
                  <div class="col-sm-2">
                    <input type="text" name="shipping_cost" value="<?php echo $shipping_cost; ?>" placeholder="<?php echo $entry_shipping_cost; ?>" id="input-shipping-cost" class="form-control" />
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_international_shipping; ?></label>
                <div class="col-sm-2">
                  <label class="radio-inline">
                    <?php if ($international_shipping) { ?>
                    <input type="radio" name="international_shipping" value="1" checked="checked" />
                    <?php echo $text_yes; ?>
                    <?php } else { ?>
                    <input type="radio" name="international_shipping" value="1" />
                    <?php echo $text_yes; ?>
                    <?php } ?>
                  </label>
                  <label class="radio-inline">
                    <?php if (!$international_shipping) { ?>
                    <input type="radio" name="international_shipping" value="0" checked="checked" />
                    <?php echo $text_no; ?>
                    <?php } else { ?>
                    <input type="radio" name="international_shipping" value="0" />
                    <?php echo $text_no; ?>
                    <?php } ?>
                  </label>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-additional-shipping"><span data-toggle="tooltip" title="<?php echo $help_additional_shipping; ?>"><?php echo $entry_additional_shipping; ?></span></label>
                  <div class="col-sm-2">
                    <input type="text" name="additional_shipping" value="<?php echo $additional_shipping; ?>" placeholder="<?php echo $entry_additional_shipping; ?>" id="input-additional-shipping" class="form-control" />
                  </div>
                </div>
              </div>
            </div>
            
            <div class="tab-pane" id="tab-options">
              <div class="form-group">
                <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_buy_now_only; ?>"><?php echo $entry_buy_now_only; ?></span></label>
                <div class="col-sm-2">
                  <label class="radio-inline">
                    <?php if ($buy_now_only) { ?>
                    <input type="radio" name="buy_now_only" value="1" checked="checked" />
                    <?php echo $text_yes; ?>
                    <?php } else { ?>
                    <input type="radio" name="buy_now_only" value="1" />
                    <?php echo $text_yes; ?>
                    <?php } ?>
                  </label>
                  <label class="radio-inline">
                    <?php if (!$buy_now_only) { ?>
                    <input type="radio" name="buy_now_only" value="0" checked="checked" />
                    <?php echo $text_no; ?>
                    <?php } else { ?>
                    <input type="radio" name="buy_now_only" value="0" />
                    <?php echo $text_no; ?>
                    <?php } ?>
                  </label>
                </div>
                <label class="col-sm-2 control-label" for="input-buy-now-price"><span data-toggle="tooltip" title="<?php echo $help_buy_now_price; ?>"><?php echo $entry_buy_now_price; ?></span></label>
                <div class="col-sm-2">
                  <input type="text" name="buy_now_price" value="<?php echo $buy_now_price; ?>" placeholder="<?php echo $entry_buy_now_price; ?>" id="input-buy-now-price" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-auto-relist"><span data-toggle="tooltip" title="<?php echo $help_auto_relist; ?>"><?php echo $entry_auto_relist; ?></span></label>
                <div class="col-sm-2">
                  <label class="radio-inline">
                    <?php if ($auto_relist) { ?>
                    <input type="radio" name="auto_relist" value="1" checked="checked" <?php if(!$allow_auto_relist) {?> disabled <?php };?>/>
                    <?php echo $text_yes; ?>
                    <?php } else { ?>
                    <input type="radio" name="auto_relist" value="1" <?php if(!$allow_auto_relist) {?> disabled <?php };?>/>
                    <?php echo $text_yes; ?>
                    <?php } ?>
                    </label>
                  <label class="radio-inline">
                    <?php if (!$auto_relist) { ?>
                    <input type="radio" name="auto_relist" value="0" checked="checked" <?php if(!$allow_auto_relist) {?> disabled <?php };?>/>
                    <?php echo $text_no; ?>
                    <?php } else { ?>
                    <input type="radio" name="auto_relist" value="0" <?php if(!$allow_auto_relist) {?> disabled <?php };?>/>
                    <?php echo $text_no; ?>
                    <?php } ?>
                  </label>
                </div>
                <label class="col-sm-2 control-label" for="input-num-relist"><span data-toggle="tooltip" title="<?php echo $help_auto_relist_times; ?>"><?php echo $entry_auto_relist_times; ?></span></label>
                      <div class="col-sm-2">
                        <input type="text" name="num_relist" value="<?php echo $num_relist; ?>" placeholder="<?php echo $entry_auto_relist_times; ?>" id="input-num-relist" class="form-control" <?php if(!$allow_auto_relist) {?> disabled <?php };?>/>
                      </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_bolded_item; ?>"><?php echo $entry_bolded; ?></span></label>
                <div class="col-sm-2">
                  <label class="radio-inline">
                    <?php if ($bolded_item) { ?>
                    <input type="radio" name="bolded_item" value="1" checked="checked" />
                    <?php echo $text_yes; ?>
                    <?php } else { ?>
                    <input type="radio" name="bolded_item" value="1" />
                    <?php echo $text_yes; ?>
                    <?php } ?>
                  </label>
                  <label class="radio-inline">
                    <?php if (!$bolded_item) { ?>
                    <input type="radio" name="bolded_item" value="0" checked="checked" />
                    <?php echo $text_no; ?>
                    <?php } else { ?>
                    <input type="radio" name="bolded_item" value="0" />
                    <?php echo $text_no; ?>
                    <?php } ?>
                  </label>
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_on_carousel; ?>"><?php echo $entry_on_carousel; ?></span></label>
                <div class="col-sm-2">
                  <label class="radio-inline">
                    <?php if ($on_carousel) { ?>
                    <input type="radio" name="on_carousel" value="1" checked="checked" />
                    <?php echo $text_yes; ?>
                    <?php } else { ?>
                    <input type="radio" name="on_carousel" value="1" />
                    <?php echo $text_yes; ?>
                    <?php } ?>
                  </label>
                  <label class="radio-inline">
                    <?php if (!$on_carousel) { ?>
                    <input type="radio" name="on_carousel" value="0" checked="checked" />
                    <?php echo $text_no; ?>
                    <?php } else { ?>
                    <input type="radio" name="on_carousel" value="0" />
                    <?php echo $text_no; ?>
                    <?php } ?>
                  </label>
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_featured; ?>"><?php echo $entry_featured; ?></span></label>
                <div class="col-sm-2">
                  <label class="radio-inline">
                    <?php if ($featured) { ?>
                    <input type="radio" name="featured" value="1" checked="checked" />
                    <?php echo $text_yes; ?>
                    <?php } else { ?>
                    <input type="radio" name="featured" value="1" />
                    <?php echo $text_yes; ?>
                    <?php } ?>
                  </label>
                  <label class="radio-inline">
                    <?php if (!$featured) { ?>
                    <input type="radio" name="featured" value="0" checked="checked" />
                    <?php echo $text_no; ?>
                    <?php } else { ?>
                    <input type="radio" name="featured" value="0" />
                    <?php echo $text_no; ?>
                    <?php } ?>
                  </label>
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_highlighted; ?>"><?php echo $entry_highlighted; ?></span></label>
                <div class="col-sm-2">
                  <label class="radio-inline">
                    <?php if ($highlighted) { ?>
                    <input type="radio" name="highlighted" value="1" checked="checked" />
                    <?php echo $text_yes; ?>
                    <?php } else { ?>
                    <input type="radio" name="highlighted" value="1" />
                    <?php echo $text_yes; ?>
                    <?php } ?>
                  </label>
                  <label class="radio-inline">
                    <?php if (!$highlighted) { ?>
                    <input type="radio" name="highlighted" value="0" checked="checked" />
                    <?php echo $text_no; ?>
                    <?php } else { ?>
                    <input type="radio" name="highlighted" value="0" />
                    <?php echo $text_no; ?>
                    <?php } ?>
                  </label>
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_slideshow; ?>"><?php echo $entry_slideshow; ?></span></label>
                <div class="col-sm-2">
                  <label class="radio-inline">
                    <?php if ($slideshow) { ?>
                    <input type="radio" name="slideshow" value="1" checked="checked" />
                    <?php echo $text_yes; ?>
                    <?php } else { ?>
                    <input type="radio" name="slideshow" value="1" />
                    <?php echo $text_yes; ?>
                    <?php } ?>
                  </label>
                  <label class="radio-inline">
                    <?php if (!$slideshow) { ?>
                    <input type="radio" name="slideshow" value="0" checked="checked" />
                    <?php echo $text_no; ?>
                    <?php } else { ?>
                    <input type="radio" name="slideshow" value="0" />
                    <?php echo $text_no; ?>
                    <?php } ?>
                  </label>
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_social_media; ?>"><?php echo $entry_social_media; ?></span></label>
                <div class="col-sm-2">
                  <label class="radio-inline">
                    <?php if ($social_media) { ?>
                    <input type="radio" name="social_media" value="1" checked="checked" />
                    <?php echo $text_yes; ?>
                    <?php } else { ?>
                    <input type="radio" name="social_media" value="1" />
                    <?php echo $text_yes; ?>
                    <?php } ?>
                  </label>
                  <label class="radio-inline">
                    <?php if (!$social_media) { ?>
                    <input type="radio" name="social_media" value="0" checked="checked" />
                    <?php echo $text_no; ?>
                    <?php } else { ?>
                    <input type="radio" name="social_media" value="0" />
                    <?php echo $text_no; ?>
                    <?php } ?>
                  </label>
                </div>
              </div>
              
            </div>
            
            <div class="tab-pane" id="tab-links">
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-keyword"><span data-toggle="tooltip" title="<?php echo $help_keyword; ?>"><?php echo $entry_keyword; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="keyword" value="<?php echo $keyword; ?>" placeholder="<?php echo $entry_keyword; ?>" id="input-keyword" class="form-control" />
                  <?php if ($error_keyword) { ?>
                  <div class="text-danger"><?php echo $error_keyword; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_store; ?></label>
                <div class="col-sm-10">
                  <div class="well well-sm" style="height: 150px; overflow: auto;">
                    <div class="checkbox">
                      <label>
                        <?php if (in_array(0, $auction_store)) { ?>
                        <input type="checkbox" name="auction_store[]" value="0" checked="checked" />
                        <?php echo $text_default; ?>
                        <?php } else { ?>
                        <input type="checkbox" name="auction_store[]" value="0" />
                        <?php echo $text_default; ?>
                        <?php } ?>
                      </label>
                    </div>
                    <?php foreach ($stores as $store) { ?>
                    <div class="checkbox">
                      <label>
                        <?php if (in_array($store['store_id'], $auction_store)) { ?>
                        <input type="checkbox" name="auction_store[]" value="<?php echo $store['store_id']; ?>" checked="checked" />
                        <?php echo $store['name']; ?>
                        <?php } else { ?>
                        <input type="checkbox" name="auction_store[]" value="<?php echo $store['store_id']; ?>" />
                        <?php echo $store['name']; ?>
                        <?php } ?>
                      </label>
                    </div>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-category"><span data-toggle="tooltip" title="<?php echo $help_category; ?>"><?php echo $entry_category; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="category" value="" placeholder="<?php echo $entry_category; ?>" id="input-category" class="form-control" />
                  <div id="auction-category" class="well well-sm" style="height: 150px; overflow: auto;">
                    <?php foreach ($auction_categories as $auction_category) { ?>
                    <div id="auction-category<?php echo $auction_category['category_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $auction_category['name']; ?>
                      <input type="hidden" name="auction_category[]" value="<?php echo $auction_category['category_id']; ?>" />
                    </div>
                    <?php } ?>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="tab-pane" id="tab-image">
              <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover">
                  <thead>
                    <tr>
                      <td class="text-left"><?php echo $entry_image; ?></td>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td class="text-left"><a href="" id="thumb-image" data-toggle="image" class="img-thumbnail"><img src="<?php echo $thumb; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a><input type="hidden" name="image" value="<?php echo $image; ?>" id="input-image" /></td>
                  </tr>
                  </tbody>
                </table>
              </div>
              <?php if($allow_extra_images) { ?>
              <div class="table-responsive">
                <table id="images" class="table table-striped table-bordered table-hover">
                  <thead>
                    <tr>
                      <td class="text-left"><?php echo $entry_additional_image; ?></td>
                      <td class="text-right"><?php echo $entry_sort_order; ?></td>
                      <td></td>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $image_row = 0; ?>
                    <?php foreach ($auction_images as $auction_image) { ?>
                    <tr id="image-row<?php echo $image_row; ?>">
                      <td class="text-left"><a href="" id="thumb-image<?php echo $image_row; ?>" data-toggle="image" class="img-thumbnail"><img src="<?php echo $auction_image['thumb']; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a><input type="hidden" name="auction_image[<?php echo $image_row; ?>][image]" value="<?php echo $auction_image['image']; ?>" id="input-image<?php echo $image_row; ?>" /></td>
                      <td class="text-right"><input type="text" name="auction_image[<?php echo $image_row; ?>][sort_order]" value="<?php echo $auction_image['sort_order']; ?>" placeholder="<?php echo $entry_sort_order; ?>" class="form-control" /></td>
                      <td class="text-left"><button type="button" onclick="removeImage(<?php echo $image_row; ?>);" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
                    </tr>
                    <?php $image_row++; ?>
                    <?php } ?>
                  </tbody>
                  
                  <tfoot>
                    <tr>
                      <td colspan="2"></td>
                      <td class="text-left"><button type="button" onclick="addImage();" data-toggle="tooltip" title="<?php echo $button_image_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
                    </tr>
                  </tfoot>
                </table>
              </div>
              <?php } ?>
            </div>
            
            
            
            <div class="tab-pane" id="tab-seller">
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-seller-name"><span data-toggle="tooltip" title="<?php echo $help_seller; ?>"><?php echo $entry_sellers_name; ?></span></label>
                <div class="col-sm-4">
                  <input type="text" name="seller_name" value="<?php echo ucwords($seller_info['firstname'] . ' ' . $seller_info['lastname']); ?>" placeholder="<?php echo $entry_sellers_name; ?>" id="input-seller-name" class="form-control" /><input type="hidden" name="seller_id" value="<?php echo $seller_info['customer_id']; ?>" id="hidden-seller-id">
                </div>
                <label class="col-sm-2 control-label" for="input-seller-email"><span data-toggle="tooltip" title="<?php echo $help_email; ?>"><?php echo $entry_sellers_email; ?></span></label>
                <div class="col-sm-4">
                  <input type="text" name="seller_email" value="<?php echo $seller_info['email']; ?>" placeholder="<?php echo $entry_sellers_email; ?>" id="input-seller-email" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-seller-address1"><span data-toggle="tooltip" title="<?php echo $help_address1; ?>"><?php echo $entry_sellers_address1; ?></span></label>
                <div class="col-sm-4">
                  <input type="text" name="seller_address1" value="<?php echo ucwords($seller_info['address_1']); ?>" placeholder="<?php echo $entry_sellers_address1; ?>" id="input-seller-address1" class="form-control" />
                </div>
                <label class="col-sm-2 control-label" for="input-customer-group"><span data-toggle="tooltip" title="<?php echo $help_customer_group; ?>"><?php echo $entry_customer_group; ?></span></label>
                <div class="col-sm-4">
                  <input type="text" name="seller_customer_group" value="<?php echo ucwords($seller_info['customer_group']['name']); ?>" placeholder="<?php echo $entry_customer_group; ?>" id="input-customer-group" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-seller-address2"><span data-toggle="tooltip" title="<?php echo $help_address2; ?>"><?php echo $entry_sellers_address2; ?></span></label>
                <div class="col-sm-4">
                  <input type="text" name="seller_address2" value="<?php echo ucwords($seller_info['address_2']); ?>" placeholder="<?php echo $entry_sellers_address2; ?>" id="input-seller-address2" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-seller-city"><span data-toggle="tooltip" title="<?php echo $help_city; ?>"><?php echo $entry_sellers_city; ?></span></label>
                <div class="col-sm-4">
                  <input type="text" name="seller_city" value="<?php echo ucwords($seller_info['city']); ?>" placeholder="<?php echo $entry_sellers_city; ?>" id="input-seller-city" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-seller-zone"><span data-toggle="tooltip" title="<?php echo $help_zone; ?>"><?php echo $entry_sellers_zone; ?></span></label>
                <div class="col-sm-4">
                  <input type="text" name="seller_zone" value="<?php echo ucwords($seller_info['zone']); ?>" placeholder="<?php echo $entry_sellers_zone; ?>" id="input-seller-zone" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-seller-country"><span data-toggle="tooltip" title="<?php echo $help_country; ?>"><?php echo $entry_sellers_country; ?></span></label>
                <div class="col-sm-4">
                  <input type="text" name="seller_country" value="<?php echo ucwords($seller_info['country']); ?>" placeholder="<?php echo $entry_sellers_country; ?>" id="input-seller-country" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-seller-postcode"><span data-toggle="tooltip" title="<?php echo $help_postcode; ?>"><?php echo $entry_sellers_postcode; ?></span></label>
                <div class="col-sm-4">
                  <input type="text" name="seller_postcode" value="<?php echo strtoupper($seller_info['postcode']); ?>" placeholder="<?php echo $entry_sellers_postcode; ?>" id="input-seller-postcode" class="form-control" />
                </div>
                <div class="col-sm-2">
                  
                  <a href="<?php echo $seller_file; ?>" data-toggle="tooltip" title="<?php echo $button_seller_file; ?>" class="btn btn-primary"><i class="fa fa-vcard-o"></i></a>
                </div>
              </div>
            </div>
            
            <div class="tab-pane" id="tab-bid-history">
              <div class="table-responsive">
                <table class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <td class="text-left"><?php echo $column_bid_name; ?></td>
                      <td class="text-left"><?php echo $column_bid_date; ?></td>
                      <td class="text-left"><?php echo $column_bid_amount; ?></td>
                      <td class="text-left"><?php echo $column_bid_proxy; ?></td>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>
            
            <div class="tab-pane" id="tab-design">
              <div class="table-responsive">
                <table class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <td class="text-left"><?php echo $entry_store; ?></td>
                      <td class="text-left"><?php echo $entry_layout; ?></td>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td class="text-left"><?php echo $text_default; ?></td>
                      <td class="text-left"><select name="auction_layout[0]" class="form-control">
                          <option value=""></option>
                          <?php foreach ($layouts as $layout) { ?>
                          <?php if (isset($auction_layout[0]) && $auction_layout[0] == $layout['layout_id']) { ?>
                          <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                          <?php } else { ?>
                          <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                          <?php } ?>
                          <?php } ?>
                        </select></td>
                    </tr>
                    <?php foreach ($stores as $store) { ?>
                    <tr>
                      <td class="text-left"><?php echo $store['name']; ?></td>
                      <td class="text-left"><select name="auction_layout[<?php echo $store['store_id']; ?>]" class="form-control">
                          <option value=""></option>
                          <?php foreach ($layouts as $layout) { ?>
                          <?php if (isset($auction_layout[$store['store_id']]) && $auction_layout[$store['store_id']] == $layout['layout_id']) { ?>
                          <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                          <?php } else { ?>
                          <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                          <?php } ?>
                          <?php } ?>
                        </select></td>
                    </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            </div>
            
            <div class="tab-pane" id="tab-fees">
              <div class="table-responsive">
                <table class="table table-bordered table-hover">
                  <caption>List of Fees</caption>
                  <thead>
                    <tr>
                      <td class="text-left col-sm-2"><?php echo $column_fee_name; ?></td>
                      <td class="text-left col-sm-2"><?php echo $column_fee_amount; ?></td>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if($all_fees) { 
                        foreach($all_fees as $fee) { ?>
                        <tr>
                          <td class="col-sm-2">
                            <?php echo $fee['fee_name']; ?>
                          </td>
                          <td class="col-sm-2">
                            <?php echo $fee['fee_charge']; ?>
                          </td>
                        </tr>
                       <?php }
                       } ?>
                  </tbody>
                </table>
              </div>
          </div>
          
        </form>
      </div>
    </div>
  </div>
  <script type="text/javascript" src="view/javascript/summernote/summernote.js"></script>
  <link href="view/javascript/summernote/summernote.css" rel="stylesheet" />
  <script type="text/javascript" src="view/javascript/summernote/opencart.js"></script>
  <script type="text/javascript"><!--

// Category
$('input[name=\'category\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/category/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['category_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'category\']').val('');

		$('#auction-category' + item['value']).remove();

		$('#auction-category').append('<div id="auction-category' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="auction_category[]" value="' + item['value'] + '" /></div>');
	}
});

$('#auction-category').delegate('.fa-minus-circle', 'click', function() {
	$(this).parent().remove();
});

  </script>
  
  <script type="text/javascript"><!--

// Sellers
$('input[name=\'seller_name\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/auction/autocomplete&token=<?php echo $token; ?>&filter_sellers=' +  encodeURIComponent(request) + '&filter_customer_group_id=2',
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
            id: item['customer_id'],
						seller_name: item['seller'],
            email: item['email'],
						group: item['customer_group'],
            address1: item['address1'],
            address2: item['address2'],
            city: item['city'],
            zone: item['zone'],
            country: item['country'],
            postcode: item['postcode']
					}
				}));
			}
		});
	},
	'select': function(item) {
    $('#hidden-seller-id').val(item['id']);
		$('#input-seller-name').val(item['seller_name']);
    $('#input-seller-email').val(item['email']);
    $('#input-customer-group').val(item['group']);
    $('#input-seller-address1').val(item['address1']);
    $('#input-seller-address2').val(item['address2']);
    $('#input-seller-city').val(item['city']);
    $('#input-seller-zone').val(item['zone']);
    $('#input-seller-country').val(item['country']);
    $('#input-seller-postcode').val(item['postcode']);
	}
});

  </script>
  
  <script type="text/javascript"><!--
var image_row = <?php echo $image_row; ?>;
var max_images = <?php echo $max_additional_images; ?>;
var counter = <?php echo $image_row; ?>;

function addImage() {
  
  if (counter < max_images) {
    html  = '<tr id="image-row' + image_row + '">';
    html += '  <td class="text-left"><a href="" id="thumb-image' + image_row + '"data-toggle="image" class="img-thumbnail"><img src="<?php echo $placeholder; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a><input type="hidden" name="auction_image[' + image_row + '][image]" value="" id="input-image' + image_row + '" /></td>';
    html += '  <td class="text-right"><input type="text" name="auction_image[' + image_row + '][sort_order]" value="" placeholder="<?php echo $entry_sort_order; ?>" class="form-control" /></td>';
    html += '  <td class="text-left"><button type="button" onclick="removeImage(' + image_row + ');" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
    html += '</tr>';
  
    
    $('#images tbody').append(html);
  
    image_row++;
    counter++;
  }
}

function removeImage(image_row) {
  $('#image-row' + image_row).remove();
  counter--;

}
//--></script>
  
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});

$('.time').datetimepicker({
	pickDate: false
});

$('.datetime').datetimepicker({
	pickDate: true,
	pickTime: true
});
//--></script>
  <script type="text/javascript"><!--
$('#language a:first').tab('show');
//--></script></div>
<?php echo $footer; ?>
