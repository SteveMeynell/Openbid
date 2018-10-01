<?php
class ControllerExtensionFeesAuctionSetup extends Controller {
	private $error = array();

	public function index() {

		$this->load->language('extension/fees/auction_setup');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');
		
		$data['setting_code'] = $this->language->get('setting_code');
		$existing_fees = $this->model_setting_setting->getSetting($data['setting_code']);
		$existing_fees_count = count($existing_fees) / 5;
		
		
		/*	debuglog("got something");
			debuglog($this->request->post);
			exit;
			$new_fees = $this->request->post;
			
			foreach($new_fees as $key => $value){
				$new_key = $data['setting_code'] . '_' . $key . '_' . strval($existing_fees_count + 1);
				$feePost[$new_key] = $value;
			}
			
			$new_key = $data['setting_code'] . '_status_' . strval($existing_fees_count + 1);
			$feePost[$new_key] = '1';
			$feePost[$data['setting_code'] . '_status']	=	'1';
			$fee2Post = array_merge($existing_fees,$feePost);
			$this->model_setting_setting->editSetting($data['setting_code'], $fee2Post);

			$this->session->data['success'] = $this->language->get('text_success');

			//$this->response->redirect($this->url->link('extension/fees/auction_setup', 'token=' . $this->session->data['token'] . '&type=fees', true));
*/			
				
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_description'] = $this->language->get('text_description');
		
		$data['column_fee']		=	$this->language->get('column_fee');
		$data['column_from']		=	$this->language->get('column_from');
		$data['column_to']		=	$this->language->get('column_to');
		$data['column_amount']		=	$this->language->get('column_amount');
		$data['column_type']		=	$this->language->get('column_type');
		$data['column_status']		=	$this->language->get('column_status');
		$data['column_action']		=	$this->language->get('column_action');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['fee'])) {
			$data['error_fees'] = $this->error['fee'];
		} else {
			$data['error_fees'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=fees', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/fees/auction_setup', 'token=' . $this->session->data['token'], true)
		);

		$data['action'] = $this->url->link('extension/fees/auction_setup/addSetting', 'token=' . $this->session->data['token'], true);

		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=fees', true);
		
		$data['token'] = $this->session->data['token'];
				
		/*if (isset($this->request->post['fees_auction_setup_fee'])) {
			$data['fees_auction_setup_fee'] = $this->request->post['fees_auction_setup_fee'];
		} else {
			$data['fees_auction_setup_fee'] = $this->config->get('fees_auction_setup_fee');
		}
		
		if ($existing_fees_count > 0) {
			$data['fees_auction_setup_status'] = '1';
		} else {
			$data['fees_auction_setup_status'] = '0';
		}
		*/
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/fees/auction_setup', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/fees/auction_setup')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
	
	public function addSetting(){
		debuglog("got something");
		$this->load->language('extension/fees/auction_setup');

		$json = array();
		
		$this->load->model('setting/setting');
		
		$data['setting_code'] = $this->language->get('setting_code');
		$existing_fees = $this->model_setting_setting->getSetting($data['setting_code']);
		$existing_fees_count = count($existing_fees) / 5;
		
		
		debuglog("got something");
		debuglog($this->request->post);
		$json['success'] = sprintf($this->language->get('text_success'));
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
