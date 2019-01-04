<?php
class ControllerAuctionSelling extends Controller {
	private $error = array();

	public function index() {
    $this->load->model('account/customer_group');
    $customer_group_id = $this->customer->getGroupId();
    $customerOnline = $this->customer->isLogged();
    $customer_group_info = $this->model_account_customer_group->getCustomerGroup($customer_group_id);
    $customerGroups = $this->model_account_customer_group->getCustomerGroups();
    $data['allow_subtitles'] = $this->config->get('config_auction_subtitles');
    $data['allow_extra_images'] = $this->config->get('config_auction_picture_gallery');
    $data['max_additional_images'] = $this->config->get('config_auction_max_gallery_pictures');

    
    //debuglog($customer_group_info);
//debuglog($customer_group_id);
    $data['online'] = $customerOnline;
    if(!$customerOnline){
      $data['not_online'] = $this->language->get('not_online_speech');
    } elseif($customer_group_id==1) {
      $data['bidder_text'] = $this->language->get('bidder_speech');
    }
    $this->load->language('auction/selling');
    $data['text_form'] = $this->language->get('text_form');
    $data['tab_description'] = $this->language->get('tab_description');
    $data['tab_photos'] = $this->language->get('tab_photos');
    $data['tab_options'] = $this->language->get('tab_options');
    $data['tab_pricing'] = $this->language->get('tab_pricing');
    $data['tab_shipping'] = $this->language->get('tab_shipping');
    $data['tab_confirm'] = $this->language->get('tab_confirm');
    $data['entry_name'] = $this->language->get('entry_name');
    $data['entry_subname'] = $this->language->get('entry_subname');
    $data['entry_description'] = $this->language->get('entry_description');
    $data['entry_tag'] = $this->language->get('entry_tag');
    $data['entry_category'] = $this->language->get('entry_category');
    $data['entry_image'] = $this->language->get('entry_image');
    $data['entry_additional_image'] = $this->language->get('entry_additional_image');
    $data['entry_sort_order'] = $this->language->get('entry_sort_order');
    $data['breadcrumbs'] = array();

    $data['auction_images'] = array();
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

    $this->load->model('catalog/category');
    



    $data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('auction/selling_form', $data));
  }

  public function getSellerForm($data){

  }

  public function getNonSellerForm(){

  }

  // end of controller
}
?>