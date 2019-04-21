<?php
class ModelFeesFees extends Model {

  public function addFee($fee_data) {
    // Ok here we add a fee record in
    // all the fees can be added to the fees_charged table
    // except auto-relist  That will have to be added to a reoccuring list because that only happens at each relisting
    
    $sql = "INSERT INTO " . DB_PREFIX . "fees_charged 
    SET 
    auction_id = '" . $this->db->escape($fee_data['auction_id']) . "', 
    customer_id = '" . $this->db->escape($fee_data['customer_id']) . "', 
    fee_code = '" . $this->db->escape($fee_data['fee_code']) . "', 
    amount = '" . $this->db->escape($fee_data['amount']) . "', 
    date_added = NOW()";

    $this->db->query($sql);

    return $this->db->getLastId();  // this will return the fee id for adding to the cart and to add a transaction in
  }

  public function addFeeToCart($feeInfo){
    $this->db->query("INSERT " . DB_PREFIX . "cart 
			SET 
			api_id = '0', 
			customer_id = '" . (int)$feeInfo['customer_id'] . "', 
			session_id = '0', 
			auction_id = '" . (int)$feeInfo['auction_id'] . "', 
			recurring_id = '0', 
			amount = '" . (float)$feeInfo['amount'] . "', 
			date_added = NOW()");
  }

  public function getAllFees($fees) {
    $total_fees = array();
    $total_fees['auction_setup']['amount'] = $this->getAuctionSetupFee();
    if(isset($fees['bolded'])) {
      $total_fees['bolded']['amount'] = $this->getBoldedFee();
    }
    if(isset($fees['carousel']) && $fees['carousel']) {
      $total_fees['carousel']['amount'] = $this->getCarouselFee();
    }
    if(isset($fees['featured']) && $fees['featured']) {
      $total_fees['featured']['amount'] = $this->getFeaturedFee();
    }
    if(isset($fees['highlighted'])) {
      $total_fees['highlighted']['amount'] = $this->getHighlightedFee();
    }
    if(isset($fees['slideshow'])) {
      $total_fees['slideshow']['amount'] = $this->getSlideshowFee();
    }
    if(isset($fees['social'])) {
      $total_fees['social']['amount'] = $this->getSocialFee();
    }
    if(isset($fees['subtitle'])) {
      $total_fees['subtitle']['amount'] = $this->getSubtitleFee($fees['subtitle']);
    }
    if(isset($fees['photo_count'])) {
      $total_fees['extra_photos']['amount'] = $this->getPhotoFee($fees['photo_count']);
    }
    if(isset($fees['category_count'])) {
      $total_fees['extra_category']['amount'] = $this->getCategoryFee($fees['category_count']);
    }
    if(isset($fees['buy_now_only'])) {
      $total_fees['buy_now_only']['amount'] = $this->getBuyNowOnlyFee($fees['buy_now_only']);
    }
    if(isset($fees['reserve'])) {
      $total_fees['reserve']['amount'] = $this->getReserveFee($fees['reserve']);
    }

    //$total_fees['total_fees'] = $this->getTotalFees($total_fees);

    if(isset($fees['auto_relist'])) {
      $total_fees['auto_relist'] = $this->getRelistFee($fees['num_relist']);
    }

    return $total_fees;
  }

  public function getReoccuringFees($fees){
    if(isset($fees['auto_relist'])) {
      $total_fees['auto_relist']['amount'] = $this->getRelistFee($fees['num_relist']);
    }

    return $total_fees;
  }

  public function getAuctionSetupFee() {
    if($this->config->get('fees_auction_setup_status')) {
      return $this->config->get('fees_auction_setup_fee');
    } else {
      return NULL;
    }
  }

  public function getBoldedFee() {
    if($this->config->get('fees_bold_item_status')) {
      return $this->config->get('fees_bold_item_fee');
    } else {
      return NULL;
    }
  }

  public function getCarouselFee() {
    if($this->config->get('fees_carousel_status')) {
      return $this->config->get('fees_carousel_fee');
    } else {
      return NULL;
    }
  }

  public function getFeaturedFee() {
    if($this->config->get('fees_featured_status')) {
      return $this->config->get('fees_featured_fee');
    } else {
      return NULL;
    }
  }

  public function getHighlightedFee() {
    if($this->config->get('fees_highlighted_status')) {
      return $this->config->get('fees_highlighted_fee');
    } else {
      return NULL;
    }
  }

  public function getSlideshowFee() {
    if($this->config->get('fees_slideshow_status')) {
      return $this->config->get('fees_slideshow_fee');
    } else {
      return NULL;
    }
  }

  public function getSocialFee() {
    if($this->config->get('fees_social_media_status')) {
      return $this->config->get('fees_social_media_fee');
    } else {
      return NULL;
    }
  }

  public function getSubtitleFee($subtitle) {
    if($this->config->get('fees_subtitle_status') && !empty($subtitle)) {
      return $this->config->get('fees_subtitle_fee');
    } else {
      return NULL;
    }
  }

  public function getRelistFee($num_relist) {
    $fee = array();
    if($this->config->get('fees_relist_status')) {
      $fee['each_relist']   = strval($this->config->get('fees_relist_fee'));
      $fee['relist_total']  = strval($this->config->get('fees_relist_fee') * $num_relist);
      return $fee;
    } else {
      return NULL;
    }
  }

  public function getPhotoFee($photoCount) {
    if($this->config->get('fees_image_upload_status') && $photoCount > '1') {
      return strval($this->config->get('fees_image_upload_fee') * ($photoCount - 1));
    } else {
      return NULL;
    }
  }

  public function getCategoryFee($categoryCount) {
    if($this->config->get('fees_extra_catagory_status') && $categoryCount > '1') {
      return strval($this->config->get('fees_extra_catagory_fee') * ($categoryCount - 1));
    } else {
      return NULL;
    }
  }

  public function getBuyNowOnlyFee($buyNowPrice) {
    $buy_now_fee = array();
    $query = "SELECT * FROM " . DB_PREFIX . "setting WHERE code = 'fees_buy_now'";
    $fees_list = $this->db->query($query)->rows;
    if($this->config->get('fees_buy_now_status')) {
      while($fees_list) {
        if($fees_list[0]['key'] == 'fees_buy_now_status') {
          $status = array_shift($fees_list);
        } else {
          $from = array_shift($fees_list);
          $to = array_shift($fees_list);
          $fee = array_shift($fees_list);
          $type = array_shift($fees_list);
          $status = array_shift($fees_list);
          array_push($buy_now_fee, array(
            'from'    => $from['value'],
            'to'      => $to['value'],
            'fee'     => $fee['value'],
            'type'    => $type['value'],
            'status'  => $status['value']
          ));
        }
      }

      foreach($buy_now_fee as $currentFee) {
        if ($buyNowPrice >= $currentFee['from'] && $buyNowPrice <= $currentFee['to'] && $currentFee['status']) {
          if($currentFee['type'] == 'flat') {
            $serviceCharge = $currentFee['fee'];
          } else {
            $serviceCharge = $buyNowPrice * ($currentFee['fee']/100);
          }
        }
      }
      return strval($serviceCharge);
    } else {
      return NULL;
    }
    
  }

  public function getReserveFee($reserve_bid){
    $reserve_fee = array();
    $query = "SELECT * FROM " . DB_PREFIX . "setting WHERE code = 'fees_reserve_price'";
    $fees_list = $this->db->query($query)->rows;
    
    if($this->config->get('fees_reserve_price_status')) {
      while($fees_list) {
        if($fees_list[0]['key'] == 'fees_reserve_price_status') {
          $status = array_shift($fees_list);
        } else {
          $from = array_shift($fees_list);
          $to = array_shift($fees_list);
          $fee = array_shift($fees_list);
          $type = array_shift($fees_list);
          $status = array_shift($fees_list);
          array_push($reserve_fee, array(
            'from'    => $from['value'],
            'to'      => $to['value'],
            'fee'     => $fee['value'],
            'type'    => $type['value'],
            'status'  => $status['value']
          ));
        }
      }
    
      foreach($reserve_fee as $currentFee) {
        if ($reserve_bid >= $currentFee['from'] && $reserve_bid <= $currentFee['to'] && $currentFee['status']) {
          if($currentFee['type'] == 'flat') {
            $serviceCharge = $currentFee['fee'];
          } else {
            $serviceCharge = $reserve_bid * ($currentFee['fee']/100);
          }
        }
      }
      return strval($serviceCharge); //$reserve_fee;
    } else {
      return NULL;
    }
  }

  public function getTotalFees($fees) {
    $fee_total = array();
    $fees_new = array_values($fees);
    foreach($fees_new as $new_fee) {
      array_push($fee_total, filter_var($new_fee, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION));
      
    }

    return strval(array_sum($fee_total));
  }

  public function getAccountingTotalFees($fees) {
    //debuglog($fees);
    $fee_total = array();
    //$fees_new = array_values($fees);
    foreach($fees as $new_fee) {
      array_push($fee_total, filter_var($new_fee['amount'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION));
      
    }

    return strval(array_sum($fee_total));
  }

  // End of Model
}

