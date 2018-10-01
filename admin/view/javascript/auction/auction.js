$(document).ready(function() {
});
  
var auction = {
  'addFeeSetting': function(store_id, code, key, value){
    $.ajax({
      url: 'index.php?route=extension/fees/auctionsetup/addSetting',
      type: 'post',
      data: $('.product-info input[type=\'text\'], .product-info input[type=\'hidden\'], .product-info input[type=\'radio\']:checked, .product-info input[type=\'checkbox\']:checked, .product-info select, .product-info textarea'),
      dataType: 'json',
      success: function(json) {
        $('.success, .warning, .attention, information, .error').remove();
    
        if (json['error']) {
          if (json['error']['option']) {
            for (i in json['error']['option']) {
              $('#option-' + i).after('<span class="error">' + json['error']['option'][i] + '</span>');
            }
          }
    
                  if (json['error']['profile']) {
                      $('select[name="profile_id"]').after('<span class="error">' + json['error']['profile'] + '</span>');
                  }
        } 
    
        if (json['success']) {
          $('#notification').html('<div class="success" style="display: none;">' + json['success'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');
    
          $('.success').fadeIn('slow');
    
          $('#cart-total').html(json['total']);
    
          $('html, body').animate({ scrollTop: 0 }, 'slow'); 
        }
      }
    });
  }
};
  