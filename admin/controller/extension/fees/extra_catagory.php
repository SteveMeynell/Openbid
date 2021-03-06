<?php
class ControllerExtensionFeesExtraCatagory extends Controller {
	private $error = array();

	public function index() {

		$this->load->language('extension/fees/extra_catagory');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('fees_extra_catagory', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=fees', true));
		}
		
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_description'] = $this->language->get('text_description');
		
		$data['entry_fee'] = $this->language->get('entry_fee');
		$data['entry_status'] = $this->language->get('entry_status');

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
			'href' => $this->url->link('extension/fees/extra_catagory', 'token=' . $this->session->data['token'], true)
		);

		$data['action'] = $this->url->link('extension/fees/extra_catagory', 'token=' . $this->session->data['token'], true);

		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=fees', true);
		
		$data['token'] = $this->session->data['token'];
				
		if (isset($this->request->post['fees_extra_catagory_fee'])) {
			$data['fees_extra_catagory_fee'] = $this->request->post['fees_extra_catagory_fee'];
		} else {
			$data['fees_extra_catagory_fee'] = $this->config->get('fees_extra_catagory_fee');
		}
		
		if (isset($this->request->post['fees_extra_catagory_status'])) {
			$data['fees_extra_catagory_status'] = $this->request->post['fees_extra_catagory_status'];
		} else {
			$data['fees_extra_catagory_status'] = $this->config->get('fees_extra_catagory_status');
		}
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/fees/extra_catagory', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/fees/extra_catagory')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}
