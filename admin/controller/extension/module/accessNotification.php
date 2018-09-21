<?php
class ControllerExtensionModuleaccessNotification extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/accessNotification');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			if ((!isset($this->request->post['accessNotification_chat_id']) || $this->request->post['accessNotification_chat_id'] == '') && (isset($this->request->post['accessNotification_telegram_key']) && $this->request->post['accessNotification_telegram_key'] != '')) {
				$this->request->post['accessNotification_chat_id'] = @json_decode(@file_get_contents("https://api.telegram.org/bot{$this->request->post['accessNotification_telegram_key']}/getUpdates"))->result[0]->message->chat->id;
			}
			$this->model_setting_setting->editSetting('accessNotification', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_success'] = $this->language->get('text_success');
		$data['text_extension'] = $this->language->get('text_extension');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_email'] = $this->language->get('entry_email');
		$data['entry_fail'] = $this->language->get('entry_fail');
		$data['entry_success'] = $this->language->get('entry_success');
		$data['entry_telegram'] = $this->language->get('entry_telegram');
		$data['entry_email_place'] = $this->language->get('entry_email_place');
		$data['entry_chat_id'] = $this->language->get('entry_chat_id');
		$data['entry_chat_id_sendmsg'] = $this->language->get('entry_chat_id_sendmsg');
		$data['entry_chat_id_place'] = $this->language->get('entry_chat_id_place');
		$data['entry_telegram_key'] = $this->language->get('entry_telegram_key');
		$data['entry_telegram_key_place'] = $this->language->get('entry_telegram_key_place');
		$data['for_more_information'] = $this->language->get('for_more_information');
		$data['for_any_questions'] = $this->language->get('for_any_questions');
		$data['siteguarding'] = $this->language->get('siteguarding');
		
		$data['link_click'] = $this->language->get('link_click');
		$data['link_siteguarding'] = $this->language->get('link_siteguarding');
		$data['link_contact'] = $this->language->get('link_contact');
		$data['link_get_api'] = $this->language->get('link_get_api');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['accessNotification_email'])) {
			$data['error_accessNotification_email'] = $this->error['accessNotification_email'];
		} else {
			$data['error_accessNotification_email'] = '';
		}

		if (isset($this->error['accessNotification_fail'])) {
			$data['error_accessNotification_fail'] = $this->error['accessNotification_fail'];
		} else {
			$data['error_accessNotification_fail'] = '';
		}

		if (isset($this->error['accessNotification_telegram'])) {
			$data['error_accessNotification_telegram'] = $this->error['accessNotification_telegram'];
		} else {
			$data['error_accessNotification_telegram'] = '';
		}
		

		if (isset($this->error['accessNotification_success'])) {
			$data['error_accessNotification_success'] = $this->error['accessNotification_success'];
		} else {
			$data['error_accessNotification_success'] = '';
		}
		
		if (isset($this->error['accessNotification_telegram_key'])) {
			$data['error_accessNotification_telegram_key'] = $this->error['accessNotification_telegram_key'];
		} else {
			$data['error_accessNotification_telegram_key'] = '';
		}
		
		if (isset($this->error['accessNotification_chat_id'])) {
			$data['error_accessNotification_chat_id'] = $this->error['accessNotification_chat_id'];
		} else {
			$data['error_accessNotification_chat_id'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/accessNotification', 'token=' . $this->session->data['token'], true)
		);

		$data['action'] = $this->url->link('extension/module/accessNotification', 'token=' . $this->session->data['token'], true);

		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true);

		if (isset($this->request->post['accessNotification_status'])) {
			$data['accessNotification_status'] = $this->request->post['accessNotification_status'];
		} else {
			$data['accessNotification_status'] = $this->config->get('accessNotification_status');
		}
		
		if (isset($this->request->post['accessNotification_email'])) {
			$data['accessNotification_email'] = $this->request->post['accessNotification_email'];
		}  else {
			$data['accessNotification_email'] = $this->config->get('accessNotification_email');
		}
		
		
		if (isset($this->request->post['accessNotification_fail'])) {
			$data['accessNotification_fail'] = $this->request->post['accessNotification_fail'];
		}  else {
			$data['accessNotification_fail'] = $this->config->get('accessNotification_fail');
		}
		
		
		if (isset($this->request->post['accessNotification_success'])) {
			$data['accessNotification_success'] = $this->request->post['accessNotification_success'];
		}  else {
			$data['accessNotification_success'] = $this->config->get('accessNotification_success');
		}
				
		
		if (isset($this->request->post['accessNotification_telegram'])) {
			$data['accessNotification_telegram'] = $this->request->post['accessNotification_telegram'];
		}  else {
			$data['accessNotification_telegram'] = $this->config->get('accessNotification_telegram');
		}
		
		if (isset($this->request->post['accessNotification_telegram_key'])) {
			$data['accessNotification_telegram_key'] = $this->request->post['accessNotification_telegram_key'];
		}  else {
			$data['accessNotification_telegram_key'] = $this->config->get('accessNotification_telegram_key');
		}
		
		if (isset($this->request->post['accessNotification_chat_id'])) {
			$data['accessNotification_chat_id'] = $this->request->post['accessNotification_chat_id'];
		} elseif ($this->config->get('accessNotification_chat_id') != '') {
			$data['accessNotification_chat_id'] = $this->config->get('accessNotification_chat_id');
		} elseif ($data['accessNotification_telegram_key'] != '') {
			$data['accessNotification_chat_id'] = @json_decode(@file_get_contents("https://api.telegram.org/bot{$data['accessNotification_telegram_key']}/getUpdates"))->result[0]->message->chat->id;
		} else {
			$data['accessNotification_chat_id'] = '';
		}
		

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/accessNotification', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/accessNotification')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}