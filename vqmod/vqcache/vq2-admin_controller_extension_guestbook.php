<?php
class ControllerExtensionGuestbook extends Controller {
	private $error = array();

	public function install() {
		$this->debuglog("Not sure when it gets here");
		$this->load->language('extension/guestbook');

		$this->load->model('extension/extension');

		if (!$this->user->hasPermission('modify', 'extension/guestbook')) {
			$this->session->data['error'] = $this->language->get('error_permission');

			$this->response->redirect($this->url->link('extension/guestbook', 'token=' . $this->session->data['token'], true));
		} else {
			$this->model_extension_extension->install('guestbook', $this->request->get['extension']);

			$this->session->data['success'] = $this->language->get('text_install_success');

			$this->load->model('user/user_group');

			$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/guestbook/' . $this->request->get['extension']);
			$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/guestbook/' . $this->request->get['extension']);

			require_once(\VQMod::modCheck(DIR_APPLICATION . 'controller/extension/guestbook/' . $this->request->get['extension'] . '.php'));

			$class = 'ControllerExtensionGuestbookGuestbook' . str_replace('_', '', $this->request->get['extension']);
			$class = new $class($this->registry);

			if (method_exists($class, 'install')) {
				$class->install();
			}
			
			$this->debuglog("is it here?  install");

			$this->response->redirect($this->url->link('extension/guestbook', 'token=' . $this->session->data['token'], true));
		}
	}

	public function uninstall() {
		$this->load->language('extension/guestbook');

		$this->load->model('extension/extension');

		if (!$this->user->hasPermission('modify', 'extension/guestbook')) {
			$this->session->data['error'] = $this->language->get('error_permission');

			$this->response->redirect($this->url->link('extension/guestbook', 'token=' . $this->session->data['token'], true));
		} else {
			$this->session->data['success'] = $this->language->get('text_uninstall_success');

			require_once(\VQMod::modCheck(DIR_APPLICATION . 'controller/extension/guestbook/' . $this->request->get['extension'] . '.php'));

			$this->load->model('extension/extension');
			$this->load->model('setting/setting');

			$this->model_extension_extension->uninstall('guestbook', $this->request->get['extension']);
			
			$this->model_setting_setting->deleteSetting($this->request->get['extension']);

			$class = 'ControllerExtensionGuestbook' . str_replace('_', '', $this->request->get['extension']);
			$class = new $class($this->registry);

			if (method_exists($class, 'uninstall')) {
				$class->uninstall();
			}

			$this->response->redirect($this->url->link('extension/guestbook', 'token=' . $this->session->data['token'], true));
		}
	}

	public function index() {
		$this->load->model('extension/guestbook/guestbook');
		$this->load->model('extension/extension');
		$this->load->model('setting/setting');
		//$this->load->model('extension/openbay/version');
		$this->load->language('extension/guestbook');

		$data = $this->language->all();

		$this->document->setTitle($this->language->get('heading_title'));
		//$this->document->addScript('view/javascript/openbay/js/faq.js');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true),
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/guestbook', 'token=' . $this->session->data['token'], true),
		);

		$data['success'] = '';
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		}

		//$data['error'] = $this->model_extension_openbay_openbay->requirementTest();

		if (isset($this->session->data['error'])) {
			$data['error'][] = $this->session->data['error'];
			unset($this->session->data['error']);
		}

		$data['token'] = $this->session->data['token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->debuglog("is it here?  controller extension guestbook index");

		$this->response->setOutput($this->load->view('extension/guestbook', $data));
	}

	public function eventMenu($route, &$data) {
		// Guestbook Menu
		

		$this->language->load('extension/guestbook/guestbook_menu');

		if ($this->user->hasPermission('access', 'extension/guestbook')) {
			$data['menus'][] = array(
				'id'       => 'menu-guestbook',
				'icon'	   => 'fa-book',
				'name'	   => $this->language->get('text_guestbook_extension'),
				'href'     => $this->url->link('theguestbook/guestbooklist', 'token=' . $this->session->data['token'], true),
				'children' => ''
			);
		}
	}

		
		
		public function debuglog($something, $dump = false){
        
			$debug_log = new Log('debug.txt');
			if(!$dump){
				$debug_log->write($something);
			} else {
				$anything = array_values($something);
				$debug_log->write($anything);
			}
		}
}