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
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-gavel"></i> <?php echo $text_form; ?></h3>
          </div>
          <div class="panel-body">
            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-selling" class="form-horizontal">
              <ul class="nav nav-tabs">
                <li class="active"><a href="#tab-description" data-toggle="tab"><?php echo $tab_description; ?></a></li>
                <li><a href="#tab-image" data-toggle="tab"><?php echo $tab_photos; ?></a></li>
                <li><a href="#tab-options" data-toggle="tab"><?php echo $tab_options; ?></a></li>
                <li><a href="#tab-pricing" data-toggle="tab"><?php echo $tab_pricing; ?></a></li>
                <li><a href="#tab-shipping" data-toggle="tab"><?php echo $tab_shipping; ?></a></li>
                <li><a href="#tab-confirm" data-toggle="tab"><?php echo $tab_confirm; ?></a></li>
              </ul>

              <div class="tab-content">
                <div class="tab-pane active" id="tab-description">
                  <div class="form-group">
                    <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_auction_type; ?>"><?php echo $entry_auction_type; ?></span></label>
                          <div class="col-sm-4">
                            <select name="auction_type" id="input-auction-type" class="form-control" disabled>
                            <?php foreach ($auction_types as $auction_type) { ?>
                              <?php if ($auction_type['type'] == '0') { ?>
                                <option value="<?php echo $auction_type['type']; ?>" selected="selected"><?php echo $auction_type['name']; ?></option>
                              <?php } else { ?>
                                <option value="<?php echo $auction_type['type']; ?>"><?php echo $auction_type['name']; ?></option>
                              <?php } ?>
                            <?php } ?>
                            </select>
                          </div>
                  </div>
                  <ul class="nav nav-tabs" id="language">
                  <?php foreach ($languages as $language) { ?>
                    <li><a href="#language<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="catalog/language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
                  <?php } ?>
                  </ul>
                  <div class="tab-content">
                    <?php foreach ($languages as $language) { ?>
                      <div class="tab-pane active" id="language<?php echo $language['language_id']; ?>">
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
                        <div class="form-group required">
                          <label class="col-sm-2 control-label" for="input-description<?php echo $language['language_id']; ?>"><?php echo $entry_description; ?></label>
                          <div class="col-sm-10">
                            <textarea name="auction_description[<?php echo $language['language_id']; ?>][description]" placeholder="<?php echo $entry_description; ?>" id="input-description<?php echo $language['language_id']; ?>" class="form-control summernote"><?php echo isset($auction_description[$language['language_id']]) ? $auction_description[$language['language_id']]['description'] : ''; ?></textarea>
                          </div>
                        </div>
                        
                        <div class="form-group required">
                          <label class="col-sm-2 control-label" for="input-tag<?php echo $language['language_id']; ?>"><span data-toggle="tooltip" title="<?php echo $help_tag; ?>"><?php echo $entry_tag; ?></span></label>
                          <div class="col-sm-10">
                            <input type="text" name="auction_description[<?php echo $language['language_id']; ?>][tag]" value="<?php echo isset($auction_description[$language['language_id']]) ? $auction_description[$language['language_id']]['tag'] : ''; ?>" placeholder="<?php echo $entry_tag; ?>" id="input-tag<?php echo $language['language_id']; ?>" class="form-control" />
                          </div>
                        </div>
                      </div>
                    <?php } ?>
                    <div class="form-group required">
                      <label class="col-sm-2 control-label" for="input-category"><span data-toggle="tooltip" title="<?php echo $help_category; ?>"><?php echo $entry_category; ?></span></label>
                      <div class="col-sm-10">
                        <input type="text" name="category" value="" placeholder="<?php echo $entry_category; ?>" id="input-category" class="form-control" />
                        <div id="auction-category" class="well well-sm" style="height: 150px; overflow: auto;">

                        </div>
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
                          <td class="col-sm-3">
                            <div class="preview">
                              <p><img src="<?php echo $photo; ?>" name="image" id="image" style="width: 100px; height: 100px;" ></p>
                            </div>
                            <button type="button" id="button-upload" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-default btn-block"><i class="fa fa-upload"></i> <?php echo $button_upload; ?></button>
                            <input type="hidden" name="uploaded_images[main_image]; ?>" value="" id="input-images; ?>" />
                          </td>
                          <td>
                          </td>
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
                            <td></td>
                          </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($auction_images as $auction_image) { ?>
                          <tr id="image-num<?php echo $auction_image['sort_order']; ?>">
                            <td class="text-left">
                              <a href="" id="thumb-image<?php echo $auction_image['sort_order']; ?>" data-toggle="image" class="img-thumbnail"><img src="<?php echo $auction_image['thumb']; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
                              <button type="button" id="button-upload" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-default btn-block"><i class="fa fa-upload"></i> <?php echo $button_upload; ?></button>
                              <input type="hidden" name="uploaded_images[<?php echo $auction_image['sort_order']; ?>]" value="" id="input-images; ?>" />
                            </td>
                            <td class="text-left">
                              <button type="button" onclick="removeImage(<?php echo $auction_image['sort_order']; ?>);" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button>
                            </td>
                          </tr>
                        <?php } ?>
                        </tbody>
                      </table>
                    </div>
                  <?php } ?>
                </div>
                <div class="tab-pane" id="tab-options">
                  <fieldset>
                    <legend><?php echo $text_options; ?></legend>
                    <?php if ($featured_used) { ?>
                      <div class="form-group">
                        <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_featured_option; ?>"><?php echo $entry_featured_option; ?></span></label>
                        <div class="col-sm-10">
                          <label class="radio-inline">
                            <input type="radio" name="featured_option" value="1" checked="checked" />
                            <?php echo $text_yes; ?>
                          </label>
                          <label class="radio-inline">
                            <input type="radio" name="featured_option" value="0" />
                            <?php echo $text_no; ?>
                          </label>
                        </div>
                      </div>
                    <?php } ?>
                    <?php if ($carousel_used) { ?>
                      <div class="form-group">
                        <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_carousel_option; ?>"><?php echo $entry_carousel_option; ?></span></label>
                        <div class="col-sm-10">
                          <label class="radio-inline">
                            <input type="radio" name="carousel_option" value="1" checked="checked" />
                            <?php echo $text_yes; ?>
                          </label>
                          <label class="radio-inline">
                            <input type="radio" name="carousel_option" value="0" />
                            <?php echo $text_no; ?>
                          </label>
                        </div>
                      </div>
                    <?php } ?>
                    <?php if ($slideshow_used) { ?>
                      <div class="form-group">
                        <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_slideshow_option; ?>"><?php echo $entry_slideshow_option; ?></span></label>
                        <div class="col-sm-10">
                          <label class="radio-inline">
                            <input type="radio" name="slideshow_option" value="1" checked="checked" />
                            <?php echo $text_yes; ?>
                          </label>
                          <label class="radio-inline">
                            <input type="radio" name="slideshow_option" value="0" />
                            <?php echo $text_no; ?>
                          </label>
                        </div>
                      </div>
                    <?php } ?>
                    <div class="form-group">
                      <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_bolded_option; ?>"><?php echo $entry_bolded_option; ?></span></label>
                      <div class="col-sm-10">
                        <label class="radio-inline">
                          <input type="radio" name="bolded_option" value="1" checked="checked" />
                          <?php echo $text_yes; ?>
                        </label>
                        <label class="radio-inline">
                          <input type="radio" name="bolded_option" value="0" />
                          <?php echo $text_no; ?>
                        </label>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_highlighted_option; ?>"><?php echo $entry_highlighted_option; ?></span></label>
                      <div class="col-sm-10">
                        <label class="radio-inline">
                          <input type="radio" name="highlighted_option" value="1" checked="checked" />
                          <?php echo $text_yes; ?>
                        </label>
                        <label class="radio-inline">
                          <input type="radio" name="highlighted_option" value="0" />
                          <?php echo $text_no; ?>
                        </label>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_social_option; ?>"><?php echo $entry_social_option; ?></span></label>
                      <div class="col-sm-10">
                        <label class="radio-inline">
                          <input type="radio" name="social_option" value="1" checked="checked" />
                          <?php echo $text_yes; ?>
                        </label>
                        <label class="radio-inline">
                          <input type="radio" name="social_option" value="0" />
                          <?php echo $text_no; ?>
                        </label>
                      </div>
                    </div>
                    
                  </fieldset>
                </div>


                <div class="tab-pane" id="tab-pricing">
                  <fieldset>
                    <legend><?php echo $text_pricing; ?></legend>
                      <div class="form-group">
                        <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_buy_now_only; ?>"><?php echo $entry_buy_now_only; ?></span></label>
                          <div class="col-sm-2">
                            <label class="radio-inline">
                              <input type="radio" name="buy_now_only" value="1"/>
                              <?php echo $text_yes; ?>
                            </label>
                            <label class="radio-inline">
                              <input type="radio" name="buy_now_only" value="0" checked="checked"/>
                              <?php echo $text_no; ?>
                            </label>
                          </div>
                          <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_buy_now_price; ?>"><?php echo $entry_buy_now_price; ?></span></label>
                            <div class="col-sm-6">
                              <label class="text-inline">
                                <input type="number" name="buy_now_price" min="0" value="0"/>
                              </label>
                            </div>
                        </div>
                      <div class="form-group">
                        <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_min_bid; ?>"><?php echo $entry_min_bid; ?></span></label>
                        <div class="col-sm-4">
                          <label class="text-inline">
                            <input type="number" name="min_bid" min="1" value="1"/>
                          </label>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_reserve_bid; ?>"><?php echo $entry_reserve_bid; ?></span></label>
                        <div class="col-sm-4">
                          <label class="text-inline">
                            <input type="number" name="reserve_bid" min="0" value="0"/>
                          </label>
                        </div>
                      </div>
                  </fieldset>

                  <fieldset>
                    <legend><?php echo $text_listing_options; ?></legend>
                      <div class="form-group">                    
                        <?php if ($auto_relist_used) { ?>
                          <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_auto_relist; ?>"><?php echo $entry_auto_relist; ?></span></label>
                          <div class="col-sm-2">
                            <label class="radio-inline">
                              <input type="radio" name="auto_relist" value="1" checked="checked" />
                              <?php echo $text_yes; ?>
                            </label>
                            <label class="radio-inline">
                              <input type="radio" name="auto_relist" value="0" />
                              <?php echo $text_no; ?>
                            </label>
                          </div>
                          <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_num_relist; ?>"><?php echo $entry_num_relist; ?></span></label>
                            <div class="col-sm-6">
                              <label class="text-inline">
                                <input type="number" name="num_relist" min="0" max="<?php echo $max_relists; ?>" value="1"/>
                              </label>
                            </div>
                        <?php } ?>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_duration; ?>"><?php echo $entry_duration; ?></span></label>
                          <div class="col-sm-4">
                            <select name="auction_duration" id="input-auction-duration" class="form-control">
                            <?php foreach ($auction_durations as $auction_duration) { ?>
                              <?php if ($auction_duration['duration'] == '1') { ?>
                                <option value="<?php echo $auction_duration['duration']; ?>" selected="selected"><?php echo $auction_duration['description']; ?></option>
                              <?php } else { ?>
                                <option value="<?php echo $auction_duration['duration']; ?>"><?php echo $auction_duration['description']; ?></option>
                              <?php } ?>
                            <?php } ?>
                            </select>
                          </div>
                      </div>
                  </fieldset>

                </div>


                <div class="tab-pane" id="tab-shipping">
                  <fieldset>
                    <legend><?php echo $text_shipping; ?></legend>
                      <div class="form-group">
                        <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_shipping; ?>"><?php echo $entry_shipping; ?></span></label>
                          <div class="col-sm-2">
                            <label class="radio-inline">
                              <input type="radio" name="shipping" value="1"/>
                              <?php echo $text_yes; ?>
                            </label>
                            <label class="radio-inline">
                              <input type="radio" name="shipping" value="0" checked="checked"/>
                              <?php echo $text_no; ?>
                            </label>
                          </div>
                          <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_shipping_cost; ?>"><?php echo $entry_shipping_cost; ?></span></label>
                            <div class="col-sm-6">
                              <label class="text-inline">
                                <input type="number" name="shipping_cost" min="0" value="0"/>
                              </label>
                            </div>
                        </div>
                      <div class="form-group">
                        <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_international_shipping; ?>"><?php echo $entry_international_shipping; ?></span></label>
                          <div class="col-sm-2">
                            <label class="radio-inline">
                              <input type="radio" name="international_shipping" value="1"/>
                              <?php echo $text_yes; ?>
                            </label>
                            <label class="radio-inline">
                              <input type="radio" name="international_shipping" value="0" checked="checked"/>
                              <?php echo $text_no; ?>
                            </label>
                          </div>
                        <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_international_shipping_cost; ?>"><?php echo $entry_international_shipping_cost; ?></span></label>
                          <div class="col-sm-4">
                            <label class="text-inline">
                              <input type="number" name="international_shipping_cost" min="0" value="0"/>
                            </label>
                          </div>
                      </div>
                  </fieldset>
                </div>
                <div class="tab-pane" id="tab-confirm">
                  <div>
                    <legend>Preview Of Your Auction</legend>
                  </div>
                  <div class="container">
                    <p>This is what your auction will look like to bidders.</p>
                    <details>
                      <summary style="display=block">Plans for this area:</summary>
                      <li>Display what your auction will look like when a bidder can bid on your auction.</li>
                      <li>Display what your auction will look like on the home page with Latest Auctions module in use.</li>
                      <li>Display what your auction will look like with the features you have selected.</li>
                    </details>
                  </div>
                  <br>
                  <br>

                  <fieldset>
                    <legend><?php echo $text_confirmation; ?></legend>
                      <div class="container">
                        <?php echo $text_sellers_agreement; ?>
                      </div>
                      <?php echo $captcha; ?>
                      <?php if ($text_agree) { ?>
                      <div class="buttons">
                        <div class="pull-right"><?php echo $text_agree; ?>
                          <?php if ($agree) { ?>
                          <input type="checkbox" name="agree" value="1" checked="checked" />
                          <?php } else { ?>
                          <input type="checkbox" name="agree" value="1" />
                          <?php } ?>
                          &nbsp;
                          <input type="submit" value="<?php echo $button_save; ?>" class="btn btn-primary" />
                        </div>
                      </div>
                      <?php } else { ?>
                      <div class="buttons">
                        <div class="pull-right">
                          <input type="submit" value="<?php echo $button_save; ?>" class="btn btn-primary" />
                        </div>
                      </div>
                      <?php } ?>
                  </fieldset>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
      <?php echo $content_bottom; ?>
    </div>
    <?php echo $column_right; ?>
  </div>
</div>
<?php echo $footer; ?>

<script type="text/javascript"><!--
// Category
$('input[name=\'category\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=auction/category/autocomplete&filter_name=' +  encodeURIComponent(request),
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

$('button[id^=\'button-upload\']').on('click', function() {
	var node = this;

	$('#form-upload').remove();

	$('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" /></form>');

	$('#form-upload input[name=\'file\']').trigger('click');

	if (typeof timer != 'undefined') {
    	clearInterval(timer);
	}

	timer = setInterval(function() {
		if ($('#form-upload input[name=\'file\']').val() != '') {
			clearInterval(timer);

			$.ajax({
				url: 'index.php?route=tool/upload',
				type: 'post',
				dataType: 'json',
				data: new FormData($('#form-upload')[0]),
				cache: false,
				contentType: false,
				processData: false,
				beforeSend: function() {
					$(node).button('loading');
				},
				complete: function() {
					$(node).button('reset');
				},
				success: function(json) {
					$('.text-danger').remove();

					if (json['error']) {
						$(node).parent().find('input').after('<div class="text-danger">' + json['error'] + '</div>');
					}

					if (json['success']) {
						alert(json['success']);
            PreviewImages(json['code']);

						$(node).parent().find('input').val(json['code']);
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		}
	}, 500);
});
</script>
<script type="text/javascript"><!--


function removeImage(image_row) {
console.log("clear image info");
console.log(image_row);
}

function PreviewImages(code){
  console.log("image upload");
  console.log(code);
}
</script>
 <script>
        var photoMain = document.querySelector('#photo-main');
        var preview = document.querySelector('.preview');
        var maxImageSize = <?php echo $max_image_size; ?> * 1024;
        var imageWidth = <?php echo $max_image_width; ?>;
        var imageHeight = <?php echo $max_image_height; ?>;
        photoMain.style.visibility = 'hidden';
        photoMain.addEventListener('change', updateImageDisplay);
        function updateImageDisplay() {
          while(preview.firstChild) {
            preview.removeChild(preview.firstChild);
          }
          var curFiles = photoMain.files;
          if(curFiles.length === 0) {
            var para = document.createElement('p');
            para.textContent = 'No files currently selected for upload';
            preview.appendChild(para);
          } else {
            var list = document.createElement('ol');
            preview.appendChild(list);
            for(var i = 0; i < curFiles.length; i++) {
              var listItem = document.createElement('li');
              var para = document.createElement('p');
              if(validFileType(curFiles[i])) {
                if(validFileSize(curFiles[i].size)) {
                  para.textContent = 'File name ' + curFiles[i].name + ', file size ' + returnFileSize(curFiles[i].size) + '.';
                  var image = new Image(imageWidth, imageHeight); //document.createElement('img');
                  image.src = window.URL.createObjectURL(curFiles[i]);
                  listItem.appendChild(image);
                  listItem.appendChild(para);
                } else {
                  para.textContent = 'File name ' + curFiles[i].name + ': File size is too large. Update your selection.';
                  listItem.appendChild(para);
                }
              } else {
                para.textContent = 'File name ' + curFiles[i].name + ': Not a valid file type. Update your selection.';
                listItem.appendChild(para);
              }
              list.appendChild(listItem);
            }
          }
        }
        var fileTypes = [
          'image/jpeg',
          'image/pjpeg',
          'image/png'
        ]
        function validFileType(file) {
          for(var i = 0; i < fileTypes.length; i++) {
            if(file.type === fileTypes[i]) {
              return true;
            }
          }
          return false;
        }
        function validFileSize(number) {
          if(number > maxImageSize) {
            return false;
          } else {
            return true;
          }
        }
        function returnFileSize(number) {
          if(number < 1024) {
            return number + 'bytes';
          } else if(number > 1024 && number < 1048576) {
            return (number/1024).toFixed(1) + 'KB';
          } else if(number > 1048576) {
            return (number/1048576).toFixed(1) + 'MB';
          }
        }


     </script>