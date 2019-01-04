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
          <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title"><i class="fa fa-gavel"></i> <?php echo $text_form; ?></h3>
            </div>
            <div class="panel-body">
              <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-customer" class="form-horizontal">
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
                      <td>
                        <div class="col-md-10">
                          <img src="" name="image" id="image" style="width: 26%;height: 100px;" >
                          <input type="file" 
                                name="photo" 
                                value="<?php echo $photo; ?>" 
                                placeholder="<?php echo $entry_photo; ?>" 
                                id="photo" 
                                onChange="PreviewImages();"/>
                          <?php if ($error_photo) { ?>
                          <div class="text-danger"><?php echo $error_photo; ?></div>
                          <?php } ?>
                        </div>
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
            
                  </div>
                </div>
              </form>
          </div>
        </div>
        <?php } else { ?>
        <?php $class = 'col-sm-8'; ?>
        <div class="panel">
        </div>
        <?php } ?>
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
</script>