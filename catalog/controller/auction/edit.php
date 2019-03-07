<?php
class ControllerAuctionEdit extends Controller {
	private $error = array();

	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('auction/edit', 'auction_id=' . $this->request->get['auction_id'], true);

			$this->response->redirect($this->url->link('account/login', '', true));
    }

    $this->load->language('auction/edit');

		$this->document->setTitle($this->language->get('heading_title'));

    $this->load->model('account/auction');

    if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

      
      $auctionId = $this->model_account_auction->editAuction($this->request->post);
      
			$this->session->data['success'] = $this->language->get('text_success');

			// Add to activity log
			if ($this->config->get('config_customer_activity')) {
				$this->load->model('account/activity');

				$activity_data = array(
					'customer_id' => $this->customer->getId(),
          'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName(),
          'auction_id'  => $auctionId
				);

				$this->model_account_activity->addActivity('auction_edit', $activity_data);
			}

			$this->response->redirect($this->url->link('account/account', '', true));
    }
  
    $this->getForm();

    // end of index
  }

  private function getForm(){

    $auctionId = $this->db->escape($this->request->get['auction_id']);
    $this->load->model('account/auction');
    
    $auction_info = $this->model_account_auction->getAuction($auctionId);

    if ($auction_info['status'] <> '1') {
      $this->response->redirect($this->url->link('account/auctions/info', 'auction_id=' . $auctionId, true));
    }
    $this->load->model('account/customer_group');
    $this->load->model('auction/auction');
    $this->load->model('tool/image');
    $this->load->model('extension/module');
    $this->load->model('catalog/information');

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
    $data['action'] = $this->url->link('auction/edit', '', true);
    $data['auction_types'] = $this->model_auction_auction->getAuctionTypes();

    $information_id = '7'; // must add this in to settings or auction settings
    $information_info = $this->model_catalog_information->getInformation($information_id);

    $data['online'] = $customerOnline;
    if(!$customerOnline){
      $data['not_online'] = $this->language->get('not_online_speech');
    } elseif($customer_group_id==1) {
      $data['bidder_text'] = $this->language->get('bidder_speech');
    }
    $this->load->language('auction/edit');
    $data['heading_title'] = $this->language->get('heading_title');
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

    
    $data['placeholder'] = 'image test';
    
    

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);
    $data['breadcrumbs'][] = array(
      'text'  => $this->language->get('text_selling'),
      'href'  => $this->url->link('auction/edit','auction_id=' . $auctionId)
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
    
    // set form data here
    
    // auction Id
    $data['auctionId'] = $auctionId;
    // description
    foreach($auction_info['description'] as $language_id => $values) {
      $data['auction_description'][$language_id] = $values;
    }
    // category
    $this->load->model('catalog/category');
    $data['categories'] = array();
    foreach($auction_info['category'] as $categoryId) {
      $categoryInfo = $this->model_catalog_category->getCategory($categoryId);
      array_push($data['categories'], array(
        'category_id' => $categoryInfo['category_id'],
        'name'        => $categoryInfo['name']));
    }

    // photos
    $data['main_image']  = array(
      'thumb' => $auction_info['main_image']?$this->model_tool_image->resize($auction_info['main_image'], 100, 100):$this->model_tool_image->resize('no_image.png', 100, 100),
      'image' => $auction_info['main_image']
    );
    
    for($image_num = 0; $image_num < $data['max_additional_images']; $image_num++) {
      $data['auction_images'][$image_num] = array(
        'sort_order'  => $image_num,
        'thumb'       => isset($auction_info['photos'][$image_num])?$this->model_tool_image->resize($auction_info['photos'][$image_num], 100, 100):$this->model_tool_image->resize('no_image.png', 100, 100),
        'image'       => isset($auction_info['photos'][$image_num])?$auction_info['photos'][$image_num]:''
      );
    }

    // options
    $data['featured_option'] = $auction_info['featured'];
    $data['carousel_option'] = $auction_info['on_carousel'];
    $data['slideshow_option'] = $auction_info['slideshow'];
    $data['highlighted_option'] = $auction_info['highlighted'];
    $data['bolded_option'] = $auction_info['bolded_item'];
    $data['social_option'] = $auction_info['social_media'];

    // pricing
    $data['buy_now_only'] = $auction_info['buy_now_only'];
    $data['buy_now_price'] = $auction_info['buy_now_price'];
    $data['min_bid'] = $auction_info['min_bid'];
    $data['reserve_bid'] = $auction_info['reserve_price'];

    // relisting
    $data['auto_relist'] = $auction_info['auto_relist'];
    $data['num_relist'] = $auction_info['num_relist'];
    $data['duration'] = $auction_info['duration'];

    // shipping
    $data['shipping'] = $auction_info['shipping'];
    $data['international_shipping'] = $auction_info['international_shipping'];
    $data['shipping_cost'] = $auction_info['shipping_cost'];
    $data['international_shipping_cost'] = $auction_info['additional_shipping'];

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

    $this->response->setOutput($this->load->view('auction/edit', $data));
    
  }

  protected function validate() {
    /*
		if ((utf8_strlen(trim($this->request->post['firstname'])) < 1) || (utf8_strlen(trim($this->request->post['firstname'])) > 32)) {
			$this->error['firstname'] = $this->language->get('error_firstname');
		}

		if ((utf8_strlen(trim($this->request->post['lastname'])) < 1) || (utf8_strlen(trim($this->request->post['lastname'])) > 32)) {
			$this->error['lastname'] = $this->language->get('error_lastname');
		}

		if ((utf8_strlen($this->request->post['email']) > 96) || !filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
			$this->error['email'] = $this->language->get('error_email');
		}

		if (($this->customer->getEmail() != $this->request->post['email']) && $this->model_account_customer->getTotalCustomersByEmail($this->request->post['email'])) {
			$this->error['warning'] = $this->language->get('error_exists');
		}

    */

		return !$this->error;
	}

// end of controller
}