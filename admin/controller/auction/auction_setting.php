<?php
class ControllerAuctionAuctionSetting extends Controller {
    
	private $error = array();

	public function index() {
		debuglog("controler loaded");
		$this->load->language('auction/auction_setting');
		debuglog($this->language->get('debugging'));

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('auction/auction_setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_auctionsetting->editSetting('config', $this->request->post);

			if ($this->config->get('config_currency_auto')) {
				$this->load->model('localisation/currency');

				$this->model_localisation_currency->refresh();
			}

			$this->session->data['success'] = $this->language->get('text_success');

			//$this->response->redirect($this->url->link('auction_setting/store', 'token=' . $this->session->data['token'], true));
		}

		$data['heading_title'] = $this->language->get('heading_title');
        $data['text_edit'] = $this->language->get('text_edit');
		
		
		
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		
		$data['tab_general'] = $this->language->get('tab_general');
		$data['tab_auction'] = $this->language->get('tab_auction');
		$data['tab_display'] = $this->language->get('tab_display');
		$data['tab_option'] = $this->language->get('tab_option');
        
        
        
        
        if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
        
        
        
        $data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('setting/auction_setting', 'token=' . $this->session->data['token'], true)
		);

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$data['action'] = $this->url->link('setting/auction_setting', 'token=' . $this->session->data['token'], true);

		$data['cancel'] = $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true);

		$data['token'] = $this->session->data['token'];

        
        
        $data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('setting/auction_setting', $data));
	}
    
    
    protected function validate() {
		if (!$this->user->hasPermission('modify', 'setting/setting')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
        
        
        	return !$this->error;
	}
    
    
	
}