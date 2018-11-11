<?php
class ControllerExtensionJumbotronJumbotron extends Controller {
	private $error = array();

	public function index() {

		$this->load->language('extension/extension/jumbotron');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            
			$this->model_setting_setting->editSetting('jumbotron', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=jumbotron', true));
		}
		
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
        $data['text_random'] = $this->language->get('text_random');
        $data['text_most_viewed'] = $this->language->get('text_most_viewed');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_description'] = $this->language->get('text_description');
		
		$data['entry_heading'] = $this->language->get('entry_heading');
		$data['entry_status'] = $this->language->get('entry_status');
        $data['entry_option'] = $this->language->get('entry_option');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['heading'])) {
			$data['error_heading'] = $this->error['heading'];
		} else {
			$data['error_heading'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=jumbotron', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/jumbotron/jumbotron', 'token=' . $this->session->data['token'], true)
		);

		$data['action'] = $this->url->link('extension/jumbotron/jumbotron', 'token=' . $this->session->data['token'], true);

		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=jumbotron', true);
		
		$data['token'] = $this->session->data['token'];
				
		if (isset($this->request->post['jumbotron_heading'])) {
			$data['jumbotron_heading'] = $this->request->post['jumbotron_heading'];
		} else {
			$data['jumbotron_heading'] = $this->config->get('jumbotron_heading');
		}
		
        if (isset($this->request->post['jumbotron_option'])) {
			$data['jumbotron_option'] = $this->request->post['jumbotron_option'];
		} else {
			$data['jumbotron_option'] = $this->config->get('jumbotron_option');
		}
        
		if (isset($this->request->post['jumbotron_status'])) {
			$data['jumbotron_status'] = $this->request->post['jumbotron_status'];
		} else {
			$data['jumbotron_status'] = $this->config->get('jumbotron_status');
		}
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/jumbotron/jumbotron', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/jumbotron/jumbotron')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}
