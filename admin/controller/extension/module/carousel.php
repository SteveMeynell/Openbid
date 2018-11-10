<?php
class ControllerExtensionModuleCarousel extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/carousel');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/module');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			if (!isset($this->request->get['module_id'])) {
				$this->model_extension_module->addModule('carousel', $this->request->post);
			} else {
				$this->model_extension_module->editModule($this->request->get['module_id'], $this->request->post);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_options'] = $this->language->get('text_options');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_heading_text'] = $this->language->get('entry_heading_text');
		$data['entry_footer_text'] = $this->language->get('entry_footer_text');
		$data['entry_type'] = $this->language->get('entry_type');
		$data['entry_transition'] = $this->language->get('entry_transition');
		$data['entry_num_auctions'] = $this->language->get('entry_num_auctions');
		$data['entry_width'] = $this->language->get('entry_width');
		$data['entry_height'] = $this->language->get('entry_height');
		$data['entry_status'] = $this->language->get('entry_status');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = '';
		}
		
		if (isset($this->error['heading'])) {
			$data['error_heading_text'] = $this->error['heading'];
		} else {
			$data['error_heading_text'] = '';
		}
		
		if (isset($this->error['footer'])) {
			$data['error_footer_text'] = $this->error['footer'];
		} else {
			$data['error_footer_text'] = '';
		}

		if (isset($this->error['width'])) {
			$data['error_width'] = $this->error['width'];
		} else {
			$data['error_width'] = '';
		}

		if (isset($this->error['height'])) {
			$data['error_height'] = $this->error['height'];
		} else {
			$data['error_height'] = '';
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

		if (!isset($this->request->get['module_id'])) {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/carousel', 'token=' . $this->session->data['token'], true)
			);
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/carousel', 'token=' . $this->session->data['token'] . '&module_id=' . $this->request->get['module_id'], true)
			);
		}

		if (!isset($this->request->get['module_id'])) {
			$data['action'] = $this->url->link('extension/module/carousel', 'token=' . $this->session->data['token'], true);
		} else {
			$data['action'] = $this->url->link('extension/module/carousel', 'token=' . $this->session->data['token'] . '&module_id=' . $this->request->get['module_id'], true);
		}

		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true);

		if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$module_info = $this->model_extension_module->getModule($this->request->get['module_id']);
		}

		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($module_info)) {
			$data['name'] = $module_info['name'];
		} else {
			$data['name'] = '';
		}
		
		if (isset($this->request->post['heading_text'])) {
			$data['heading_text'] = $this->request->post['heading_text'];
		} elseif (!empty($module_info)) {
			$data['heading_text'] = $module_info['heading_text'];
		} else {
			$data['heading_text'] = '';
		}
		
		if (isset($this->request->post['footer_text'])) {
			$data['footer_text'] = $this->request->post['footer_text'];
		} elseif (!empty($module_info)) {
			$data['footer_text'] = $module_info['footer_text'];
		} else {
			$data['footer_text'] = '';
		}

		if (isset($this->request->post['num_auctions'])) {
			$data['num_auctions'] = $this->request->post['num_auctions'];
		} elseif (!empty($module_info)) {
			$data['num_auctions'] = $module_info['num_auctions'];
		} else {
			$data['num_auctions'] = '0';
		}
		
		if (isset($this->request->post['type'])) {
			$data['type'] = $this->request->post['type'];
		} elseif (!empty($module_info)) {
			$data['type'] = $module_info['type'];
		} else {
			$data['type'] = '0';
		}
		
		if (isset($this->request->post['transition_id'])) {
			$data['transition_id'] = $this->request->post['transition_id'];
		} elseif (!empty($module_info)) {
			$data['transition_id'] = $module_info['transition_id'];
		} else {
			$data['transition_id'] = '0';
		}

		$data['transitions'] = array();
		array_push($data['transitions'], 'Slide In/Slide Out', 'Fade In/Fade Out');

		$data['type_options'] = array();
		array_push($data['type_options'], 'Anywhere', 'Starting Soon', 'Ending Soon', 'Category Relevant', 'Auction Page');
		

		if (isset($this->request->post['width'])) {
			$data['width'] = $this->request->post['width'];
		} elseif (!empty($module_info)) {
			$data['width'] = $module_info['width'];
		} else {
			$data['width'] = 150;
		}

		if (isset($this->request->post['height'])) {
			$data['height'] = $this->request->post['height'];
		} elseif (!empty($module_info)) {
			$data['height'] = $module_info['height'];
		} else {
			$data['height'] = 100;
		}

		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($module_info)) {
			$data['status'] = $module_info['status'];
		} else {
			$data['status'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/carousel', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/carousel')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}
		
		if ((utf8_strlen($this->request->post['heading_text']) < 3) || (utf8_strlen($this->request->post['heading_text']) > 64)) {
			$this->error['heading'] = $this->language->get('error_heading_text');
		}
		
		if ((utf8_strlen($this->request->post['footer_text']) < 3) || (utf8_strlen($this->request->post['footer_text']) > 64)) {
			$this->error['footer'] = $this->language->get('error_footer_text');
		}

		if (!$this->request->post['width']) {
			$this->error['width'] = $this->language->get('error_width');
		}

		if (!$this->request->post['height']) {
			$this->error['height'] = $this->language->get('error_height');
		}

		return !$this->error;
	}
}