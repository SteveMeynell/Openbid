<?php
class ControllerAuctionSelling extends Controller {
	private $error = array();

	public function index() {

    if (!$this->customer->isLogged()) {
			$this->response->redirect($this->url->link('account/login', '', true));
		}
    

    if (($this->request->server['REQUEST_METHOD'] == 'POST')  && $this->validate()) {
      $this->listAuction();
    }

    $this->load->model('account/customer_group');
    $this->load->model('auction/auction');
    $this->load->model('tool/image');
    $this->load->model('extension/module');
    $this->load->model('catalog/information');
    $this->load->model('fees/fees');

    $this->getForm();

    
  }

  private function getForm(){

    
    $customer_group_id = $this->customer->getGroupId();
    $customerOnline = $this->customer->isLogged();
    $customer_group_info = $this->model_account_customer_group->getCustomerGroup($customer_group_id);
    $customerGroups = $this->model_account_customer_group->getCustomerGroups();


    $data['allow_subtitles'] = $this->config->get('config_auction_subtitles');
    $data['allow_extra_images'] = $this->config->get('config_auction_picture_gallery');
    $data['max_additional_images'] = $this->config->get('config_auction_max_gallery_pictures');
    $data['max_image_size'] = $this->config->get('config_auction_max_picture_size');
    $data['max_image_height'] = $this->config->get('theme_default_image_auction_height');
    $data['max_image_width'] = $this->config->get('theme_default_image_auction_width');
    $data['featured_used'] = $this->model_extension_module->isModuleUsed('featured');
    $data['carousel_used'] = $this->model_extension_module->isModuleUsed('carousel');
    $data['slideshow_used'] = $this->model_extension_module->isModuleUsed('slideshow');
    $data['auto_relist_used'] = $this->config->get('config_auction_auto_relist');
    $data['max_relists'] = $this->config->get('config_auction_max_relists');
    $data['auction_durations'] = $this->model_auction_auction->getAuctionDurations();
    $data['action'] = $this->url->link('auction/selling', '', true);
    $data['auction_types'] = $this->model_auction_auction->getAuctionTypes();

    $information_id = '7'; // must add this in to settings or auction settings
    $information_info = $this->model_catalog_information->getInformation($information_id);

    $data['online'] = $customerOnline;
    if(!$customerOnline){
      $data['not_online'] = $this->language->get('not_online_speech');
    } elseif($customer_group_id==1) {
      $data['bidder_text'] = $this->language->get('bidder_speech');
    }
    $this->load->language('auction/selling');
    $data['text_form'] = $this->language->get('text_form');
    $data['text_options'] = $this->language->get('text_options');
    $data['text_listing_options'] = $this->language->get('text_listing_options');
    $data['text_pricing'] = $this->language->get('text_pricing');
    $data['text_shipping'] = $this->language->get('text_shipping');
    $data['text_confirmation'] = $information_info['title'];
    $data['text_sellers_agreement'] = html_entity_decode($information_info['description'], ENT_QUOTES, 'UTF-8');
    $data['text_yes'] = $this->language->get('text_yes');
    $data['text_no'] = $this->language->get('text_no');
    $data['text_loading'] = $this->language->get('text_loading');

    $data['tab_description'] = $this->language->get('tab_description');
    $data['tab_photos'] = $this->language->get('tab_photos');
    $data['tab_options'] = $this->language->get('tab_options');
    $data['tab_pricing'] = $this->language->get('tab_pricing');
    $data['tab_shipping'] = $this->language->get('tab_shipping');
    $data['tab_confirm'] = $this->language->get('tab_confirm');

    $data['entry_auction_type'] = $this->language->get('entry_auction_type');
    $data['entry_name'] = $this->language->get('entry_name');
    $data['entry_subname'] = $this->language->get('entry_subname');
    $data['entry_description'] = $this->language->get('entry_description');
    $data['entry_tag'] = $this->language->get('entry_tag');
    $data['entry_category'] = $this->language->get('entry_category');
    $data['entry_image'] = $this->language->get('entry_image');
    $data['entry_additional_image'] = $this->language->get('entry_additional_image');
    $data['entry_sort_order'] = $this->language->get('entry_sort_order');
    $data['entry_bolded_option'] = $this->language->get('entry_bolded_option');
    $data['entry_highlighted_option'] = $this->language->get('entry_highlighted_option');
    $data['entry_social_option'] = $this->language->get('entry_social_option');
    $data['entry_featured_option'] = $this->language->get('entry_featured_option');
    $data['entry_carousel_option'] = $this->language->get('entry_carousel_option');
    $data['entry_slideshow_option'] = $this->language->get('entry_slideshow_option');
    $data['entry_auto_relist'] = $this->language->get('entry_auto_relist');
    $data['entry_num_relist'] = $this->language->get('entry_num_relist');
    $data['entry_min_bid'] = $this->language->get('entry_min_bid');
    $data['entry_reserve_bid'] = $this->language->get('entry_reserve_bid');
    $data['entry_buy_now_only'] = $this->language->get('entry_buy_now_only');
    $data['entry_buy_now_price'] = $this->language->get('entry_buy_now_price');
    $data['entry_duration'] = $this->language->get('entry_duration');
    $data['entry_shipping'] = $this->language->get('entry_shipping');
    $data['entry_international_shipping'] = $this->language->get('entry_international_shipping');
    $data['entry_shipping_cost'] = $this->language->get('entry_shipping_cost');
    $data['entry_international_shipping_cost'] = $this->language->get('entry_international_shipping_cost');

    $data['help_tag'] = $this->language->get('help_tag');
    $data['help_category'] = $this->language->get('help_category');
    $data['help_featured_option'] = $this->language->get('help_featured_option');
    $data['help_carousel_option'] = $this->language->get('help_carousel_option');
    $data['help_bolded_option'] = $this->language->get('help_bolded_option');
    $data['help_slideshow_option'] = $this->language->get('help_slideshow_option');
    $data['help_highlighted_option'] = $this->language->get('help_highlighted_option');
    $data['help_social_option'] = $this->language->get('help_social_option');

    $data['button_save'] = $this->language->get('button_save');
    $data['button_upload'] = $this->language->get('button_upload');
    $data['button_remove'] = $this->language->get('button_remove');
    $data['breadcrumbs'] = array();

    $data['photo']  = $this->model_tool_image->resize('no_image.png', 100, 100);
    $data['placeholder'] = 'image test';
    
    for($image_num = 0; $image_num < $data['max_additional_images']; $image_num++) {
      $data['auction_images'][$image_num] = array(
        'sort_order'  => $image_num,
        'thumb'       => $this->model_tool_image->resize('no_image.png', 100, 100),
        'image'       => ''
      );
    }

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);
    $data['breadcrumbs'][] = array(
      'text'  => $this->language->get('text_selling'),
      'href'  => $this->url->link('auction/selling')
    );

    $this->load->model('localisation/language');

		$data['languages'] = array();

		$results = $this->model_localisation_language->getLanguages();

		foreach ($results as $result) {
			if ($result['status']) {
				$data['languages'][] = array(
          'language_id' => $result['language_id'],
					'name' => $result['name'],
					'code' => $result['code']
				);
			}
		}

    // Captcha
		if ($this->config->get($this->config->get('config_captcha') . '_status') && in_array('selling', (array)$this->config->get('config_captcha_page'))) {
			$data['captcha'] = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha'), $this->error);
		} else {
			$data['captcha'] = '';
    }
    if ($this->config->get('config_account_id')) {
			$this->load->model('catalog/information');

			$information_info = $this->model_catalog_information->getInformation($this->config->get('config_account_id'));

			if ($information_info) {
				$data['text_agree'] = sprintf($this->language->get('text_agree'), $this->url->link('information/information/agree', 'information_id=' . $this->config->get('config_account_id'), true), $information_info['title'], $information_info['title']);
			} else {
				$data['text_agree'] = '';
			}
		} else {
			$data['text_agree'] = '';
    }
    $data['agree'] = false;

    $this->load->model('catalog/category');
    



    $data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

    $this->response->setOutput($this->load->view('auction/selling_form', $data));
    
  }

  private function listAuction() {
    $sellersAuction = $this->request->post;
      $grace_period = '1 hours'; // needs to be a setting
      $fees = array();
      

      // auctions table
      $auction2BAdded['seller_id'] = $this->customer->getId();
      $auction2BAdded['auction_status'] = '1';
      $auction2BAdded['auto_relist'] = $sellersAuction['auto_relist'];
      if ($sellersAuction['auto_relist']) {
        $auction2BAdded['num_relist'] = $sellersAuction['num_relist'];
        $fees['reoccuring']['auto_relist'] = $sellersAuction['auto_relist'];
        $fees['reoccuring']['num_relist'] = $sellersAuction['num_relist'];
      } else {
        $auction2BAdded['num_relist'] = '0';
        $fees['reoccuring']['auto_relist'] = NULL;
      }

      // auction images
      $auction2BAdded['main_image'] = $sellersAuction['uploaded_images']['main_image'];
      $fees['fee']['photo_count'] = 1;

      $auction2BAdded['auction_image'] = array();
      for($x=0; $x < $this->config->get('config_auction_max_gallery_pictures'); $x++) {
        if ($sellersAuction['uploaded_images'][$x] <> '') {
          array_push($auction2BAdded['auction_image'], ['image' => $sellersAuction['uploaded_images'][$x],
          'sort_order' => $x]);
          $fees['fee']['photo_count'] += 1;
        }
      }

      // auction description
      $seader = $sellersAuction['auction_description'][1]['name'] . ' ' . (null !== $sellersAuction['auction_description'][1]['subname'] ? $sellersAuction['auction_description'][1]['subname'] .' ': '') . $sellersAuction['auction_description'][1]['description'];
			$keywords = make_keywords($seader);
			$fees['fee']['subtitle'] = $sellersAuction['auction_description'][1]['subname'];
			$addon_keywords = 'For sale ' . $sellersAuction['auction_description'][1]['name'] . ', Auctioning ' . $sellersAuction['auction_description'][1]['name'] .', ';
			$tag_limit = array('limit_keywords_to' => 5);
						
      $auction2BAdded['auction_description'] =	$sellersAuction['auction_description'];
      if ($sellersAuction['auction_description'][1]['tag'] == '') {
        $auction2BAdded['auction_description'][1]['tag'] = make_keywords($seader,$tag_limit);
      }
			$auction2BAdded['auction_description'][1]['meta_title'] = 'Auctioning ' . $sellersAuction['auction_description'][1]['name'];
			$auction2BAdded['auction_description'][1]['meta_description'] = strip_tags($sellersAuction['auction_description'][1]['description']);
      $auction2BAdded['auction_description'][1]['meta_keyword'] = $addon_keywords . $keywords . ', ' . $auction2BAdded['auction_description'][1]['tag'];

      // auction details
      $query = "SELECT NOW() as currenttime";
      $current_datetime = $this->db->query($query)->row['currenttime'];
      $startDate = date_add(date_create($current_datetime),date_interval_create_from_date_string($grace_period));
      $endDuration = strval((float)$sellersAuction['auction_duration'] * 24 + (float)$grace_period); // in hours
      $endDate = date_add(date_create($current_datetime),date_interval_create_from_date_string("$endDuration" . ' hours'));
            
      $auction2BAdded['custom_start_date'] = $startDate->format('Y-m-d H:i:s');
      $auction2BAdded['custom_end_date'] = $endDate->format('Y-m-d H:i:s');

      $auction2BAdded['min_bid'] = $sellersAuction['min_bid'];
      $auction2BAdded['shipping_cost'] = $sellersAuction['shipping_cost'];
      $auction2BAdded['additional_shipping'] = $sellersAuction['international_shipping_cost'];
      $auction2BAdded['reserve_price'] = $sellersAuction['reserve_bid'];
      $fees['fee']['reserve'] = $sellersAuction['reserve_bid'];
      $auction2BAdded['duration'] = $sellersAuction['auction_duration'];
      $auction2BAdded['increment'] = '1';
      $auction2BAdded['shipping'] = $sellersAuction['shipping'];
      $auction2BAdded['international_shipping'] = $sellersAuction['international_shipping'];
      $auction2BAdded['initial_quantity'] = '1';
      $auction2BAdded['buy_now_price'] = $sellersAuction['buy_now_price'];

      // auction options
      $auction2BAdded['buy_now_only'] = $sellersAuction['buy_now_only'];
      $fees['fee']['buy_now_only'] = $sellersAuction['buy_now_only']?$sellersAuction['buy_now_price']:NULL;
      
      $auction2BAdded['bolded_item'] = $sellersAuction['bolded_option'];
      $fees['fee']['bolded'] = $sellersAuction['bolded_option']?$sellersAuction['bolded_option']:NULL;
      $auction2BAdded['highlighted'] = $sellersAuction['highlighted_option'];
      $fees['fee']['highlighted'] = $sellersAuction['highlighted_option']?$sellersAuction['highlighted_option']:NULL;
      $auction2BAdded['social_media'] = $sellersAuction['social_option'];
      $fees['fee']['social'] = $sellersAuction['social_option']?$sellersAuction['social_option']:NULL;
      
      $auction2BAdded['featured'] = isset($sellersAuction['featured_option'])?$sellersAuction['featured_option']:'0';
      $fees['fee']['featured'] = (isset($sellersAuction['featured_option']) && $sellersAuction['featured_option'])?$sellersAuction['featured_option']:NULL;

      $auction2BAdded['on_carousel'] = isset($sellersAuction['carousel_option'])?$sellersAuction['carousel_option']:'0';
      $fees['fee']['carousel'] = (isset($sellersAuction['carousel_option']) && $sellersAuction['carousel_option'])?$sellersAuction['carousel_option']:NULL;
      
      $auction2BAdded['slideshow'] = isset($sellersAuction['slideshow_option'])?$sellersAuction['slideshow_option']:'0';
      $fees['fee']['slideshow'] = (isset($sellersAuction['slideshow_option']) && $sellersAuction['slideshow_option'])?$sellersAuction['slideshow_option']:NULL;
      
      //debuglog($sellersAuction);

      // auction store
      $auction2BAdded['auction_store'][] = '0';

      // auction catagories
      $auction2BAdded['auction_category'] = $sellersAuction['auction_category'];
      $fees['fee']['category_count'] = count($sellersAuction['auction_category']);

      // auction type  Dutch auctions not implimented yet
      $auction2BAdded['auction_type'] = '0';

      
      $this->load->model('account/auction');
      //debuglog($sellersAuction);
      $yourNewAuctionId = $this->model_account_auction->addAuction($auction2BAdded);
      
      // charge the account the fees
      $this->load->model('fees/fees');
      //debuglog($fees['fee']);
      $fee_charge['fee'] = $this->model_fees_fees->getAllFees($fees['fee']);
      
      $fee_charge['current_total'] = $this->model_fees_fees->getAccountingTotalFees($fee_charge['fee']);
      //debuglog("current total" . $fee_charge['current_total']);
      $fee_charge['reoccuring'] = $this->model_fees_fees->getReoccuringFees($fees['reoccuring']);

      // accounting
      $this->load->model("bookkeeping/accounting");
      foreach($fee_charge['fee'] as $short_code => $fee_amount) {
        $fee_charge['fee'][$short_code]['code'] = $this->model_bookkeeping_accounting->getAccountCodeByShortCode('A-' . $short_code);
      }
      $fee_charge['auction_id'] = $yourNewAuctionId;
      $fee_charge['customer_id'] = $auction2BAdded['seller_id'];
      $fee_charge['description'] = $sellersAuction['auction_description'][1]['name'];
      
      // this is where you add the fees to the cart.  Also need to keep a separate record of the fees for later drill down.
      // First lets add the fees to the fees_charged table and a transaction record
      $fee_charge['gl_code'] = $this->model_bookkeeping_accounting->getAccountCodeByShortCode('AR-fees');
      //$fee_charge['transaction_id'] = $this->model_bookkeeping_accounting->addTransaction($fee_charge);
      $this->cart->add($fee_charge['auction_id'], $fee_charge['current_total']);
      $fee_id = array();
      foreach($fee_charge['fee'] as $shortcode => $fee) {
        $fee2add = array(
          'auction_id'    => $fee_charge['auction_id'], 
          'customer_id'   => $fee_charge['customer_id'], 
          'fee_code'        => $fee['code'], 
          'amount'          => $fee['amount']);
        array_push($fee_id, $this->model_fees_fees->addFee($fee2add));
      }
      
      //debuglog($fee_id);

      // Send Emails
      $this->load->language('mail/selling');
      $subject = sprintf($this->language->get('text_subject'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
      $message = sprintf($this->language->get('text_message'), html_entity_decode($this->customer->getFullName(), ENT_QUOTES, 'UTF-8'), html_entity_decode($auction2BAdded['auction_description'][1]['name'], ENT_QUOTES, 'UTF-8'), html_entity_decode($startDate->format('Y-m-d H:i:s'), ENT_QUOTES, 'UTF-8'), htmlspecialchars_decode($this->url->link('auction/edit', 'auction_id=' . $yourNewAuctionId, true)));

      $mail = new Mail();
					$mail->protocol = $this->config->get('config_mail_protocol');
					$mail->parameter = $this->config->get('config_mail_parameter');
					$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
					$mail->smtp_username = $this->config->get('config_mail_smtp_username');
					$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
					$mail->smtp_port = $this->config->get('config_mail_smtp_port');
					$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

					$mail->setTo($this->customer->getEmail($auction2BAdded['seller_id']));
					$mail->setFrom($this->config->get('config_email'));
					$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
					$mail->setSubject($subject);
					$mail->setText($message);
          $mail->send();
          

      // Add to activity log
			if ($this->config->get('config_customer_activity')) {
				$this->load->model('account/activity');

				$activity_data = array(
					'customer_id' => $auction2BAdded['seller_id'],
          'name'        => $this->customer->getFirstname($auction2BAdded['seller_id']) . ' ' . $this->customer->getLastname($auction2BAdded['seller_id']),
          'auction_id'  => $yourNewAuctionId
				);

				$this->model_account_activity->addActivity('new_auction', $activity_data);
      }
      
      $this->response->redirect($this->url->link('auction/success'));
  }

  // Validation
  private function validate() {

    // Captcha
		if ($this->config->get($this->config->get('config_captcha') . '_status') && in_array('selling', (array)$this->config->get('config_captcha_page'))) {
			$captcha = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha') . '/validate');

			if ($captcha) {
				$this->error['captcha'] = $captcha;
			}
    }
    
    // Agree to terms
		if ($this->config->get('config_account_id')) {
			$this->load->model('catalog/information');

      $information_id = '7';  // this must be a setting
			$information_info = $this->model_catalog_information->getInformation($information_id);

			if ($information_info && !isset($this->request->post['agree'])) {
				$this->error['warning'] = sprintf($this->language->get('error_agree'), $information_info['title']);
			}
		}

		return !$this->error;
  }

  public function getFees() {
    $this->load->model('fees/fees');

    $json = array();

    $data = $this->request->post;
    $options_used = explode(',', $data['options_used']);
    $json['auction_setup_fee'] = $this->currency->format($this->model_fees_fees->getAuctionSetupFee(),$this->session->data['currency']);
    $json['subtitle_fee'] = empty($data['subtitle'])?NULL:$this->currency->format($this->model_fees_fees->getSubtitleFee($data['subtitle']),$this->session->data['currency']);

    
    if($data['reserve_bid']) {
      $json['reserve_fee'] = $this->currency->format($this->model_fees_fees->getReserveFee($data['reserve_bid']),$this->session->data['currency']);
    }

    // photo counter
    $json['photo_fee'] = empty($data['photo_counter'])?NULL:$this->currency->format($this->model_fees_fees->getPhotoFee($data['photo_counter']),$this->session->data['currency']);

    // category counter
    $json['category_fee'] = empty($data['category_counter'])?NULL:$this->currency->format($this->model_fees_fees->getCategoryFee($data['category_counter']),$this->session->data['currency']);
    

    foreach($options_used as $option) {
      switch ($option) {
        case 'featured-option':
        $json['featured_fee'] = $this->currency->format($this->model_fees_fees->getFeaturedFee(),$this->session->data['currency']);
        break;
        case 'carousel-option':
        $json['carousel_fee'] = $this->currency->format($this->model_fees_fees->getCarouselFee(),$this->session->data['currency']);
        break;
        case 'bolded-option':
        $json['bolded_fee'] = $this->currency->format($this->model_fees_fees->getBoldedFee(),$this->session->data['currency']);
        break;
        case 'highlighted-option':
        $json['highlighted_fee'] = $this->currency->format($this->model_fees_fees->getHighlightedFee(),$this->session->data['currency']);
        break;
        case 'social-option':
        $json['social_fee'] = $this->currency->format($this->model_fees_fees->getSocialFee(),$this->session->data['currency']);
        break;
        case 'auto-relist':
        $relist_amounts = $this->model_fees_fees->getRelistFee($data['num_relist']);
        if (!is_null($relist_amounts)) {
          $json['auto_relist_fee']['total_relist_fee']  = $this->currency->format($relist_amounts['relist_total'],$this->session->data['currency']);
          $json['auto_relist_fee']['each_relisting']    = $this->currency->format($relist_amounts['each_relist'],$this->session->data['currency']);
        } else {
          $json['auto_relist_fee']['total_relist_fee']  = NULL;
          $json['auto_relist_fee']['each_relisting']    = NULL;
        }
        break;
        case 'slideshow-option':
        $json['slideshow_fee'] = $this->currency->format($this->model_fees_fees->getSlideshowFee(),$this->session->data['currency']);
        break;
        case 'buy-now-only':
        $json['buy_now_only_fee'] = $this->currency->format($this->model_fees_fees->getBuyNowOnlyFee($data['buy_now_price']),$this->session->data['currency']);
        break;
        default:
        debuglog("shouldn't get here");
        debuglog($option);
      }
    }
    $json['total_fee'] = $this->currency->format($this->model_fees_fees->getTotalFees($json),$this->session->data['currency']);
    $json['success'] = true;


    $this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
  }
  // end of controller
}
?>