<?php
class ControllerExtensionAccountingSetupAccounts extends Controller {
	private $error = array();
    
	public function index() {
		
		$this->load->language('extension/accounting/setup_accounts');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$statusUpdate = array(
				'accounting_setup_accounts_status'	=>	'1'
				);
			
			$this->model_setting_setting->editSetting('accounting_setup_accounts_status', $statusUpdate);
			
            debuglog($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=accounting', true));
		}
		
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_description'] = $this->language->get('text_description');
		
		$data['entry_asset_accounts'] = $this->language->get('entry_asset_accounts');
        $data['entry_liability_accounts'] = $this->language->get('entry_liability_accounts');
        $data['entry_capital_accounts'] = $this->language->get('entry_capital_accounts');
        $data['entry_revenue_accounts'] = $this->language->get('entry_revenue_accounts');
        $data['entry_expense_accounts'] = $this->language->get('entry_expense_accounts');
		$data['entry_status'] = $this->language->get('entry_status');

		$data['suggest_asset_accounts'] = $this->language->get('suggest_asset_accounts');
		$data['suggest_liability_accounts'] = $this->language->get('suggest_liability_accounts');
		$data['suggest_capital_accounts'] = $this->language->get('suggest_capital_accounts');
		$data['suggest_revenue_accounts'] = $this->language->get('suggest_revenue_accounts');
		$data['suggest_expense_accounts'] = $this->language->get('suggest_expense_accounts');
		
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		if (isset($this->error['asset'])) {
			$data['error_asset'] = $this->error['asset'];
		} else {
			$data['error_asset'] = '';
		}
		
		if (isset($this->error['liability'])) {
			$data['error_liability'] = $this->error['liability'];
		} else {
			$data['error_liability'] = '';
		}
		
		if (isset($this->error['capital'])) {
			$data['error_capital'] = $this->error['capital'];
		} else {
			$data['error_capital'] = '';
		}
		
		if (isset($this->error['revenue'])) {
			$data['error_revenue'] = $this->error['revenue'];
		} else {
			$data['error_revenue'] = '';
		}
		
		if (isset($this->error['expense'])) {
			$data['error_expense'] = $this->error['expense'];
		} else {
			$data['error_expense'] = '';
		}

		if (isset($this->error['accounting'])) {
			$data['error_accounting'] = $this->error['accounting'];
		} else {
			$data['error_accounting'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=accounting', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/accounting/setup_accounts', 'token=' . $this->session->data['token'], true)
		);

		$data['action'] = $this->url->link('extension/accounting/setup_accounts', 'token=' . $this->session->data['token'], true);

		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=accounting', true);
		
		$data['token'] = $this->session->data['token'];
				
		if (isset($this->request->post['accounting_asset_account'])) {
			$data['asset_account_start'] = $this->request->post['accounting_asset_account'];
		} else {
			$data['asset_account_start'] = ''; //$this->config->get('asset_account_start');
		}
		
		if (isset($this->request->post['accounting_liability_account'])) {
			$data['liability_account_start'] = $this->request->post['accounting_liability_account'];
		} else {
			$data['liability_account_start'] = ''; //$this->config->get('liability_account_start');
		}
		
		if (isset($this->request->post['accounting_capital_account'])) {
			$data['capital_account_start'] = $this->request->post['accounting_capital_account'];
		} else {
			$data['capital_account_start'] = ''; //$this->config->get('capital_account_start');
		}
		
		if (isset($this->request->post['accounting_revenue_account'])) {
			$data['revenue_account_start'] = $this->request->post['accounting_revenue_account'];
		} else {
			$data['revenue_account_start'] = ''; //$this->config->get('revenue_account_start');
		}
		
		if (isset($this->request->post['accounting_expense_account'])) {
			$data['expense_account_start'] = $this->request->post['accounting_expense_account'];
		} else {
			$data['expense_account_start'] = ''; //$this->config->get('expense_account_start');
		}
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/accounting/setup_accounts', $data));
	}

	public function uninstall(){
		$this->load->model('setting/setting');
		$statusUpdate = array(
				'accounting_setup_accounts_status'	=>	'0'
				);
			
			$this->model_setting_setting->editSetting('accounting_setup_accounts_status', $statusUpdate);
			return true;
	}
	
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/accounting/setup_accounts')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!isset($this->request->post['accounting_asset_account'])  || $this->request->post['accounting_asset_account']<=0) {
			$this->error['asset'] = $this->language->get('error_asset');
		} 
		
		if (!isset($this->request->post['accounting_liability_account']) || $this->request->post['accounting_liability_account'] <= 0) {
			$this->error['liability'] = $this->language->get('error_liability');
		}
		
		if (!isset($this->request->post['accounting_capital_account']) || $this->request->post['accounting_capital_account'] <=0) {
			$this->error['capital'] = $this->language->get('error_capital');
		}
		
		if (!isset($this->request->post['accounting_revenue_account']) || $this->request->post['accounting_revenue_account'] <=0) {
			$this->error['revenue'] = $this->language->get('error_revenue');
		}
		
		if (!isset($this->request->post['accounting_expense_account']) || $this->request->post['accounting_expense_account'] <=0) {
			$this->error['expense'] = $this->language->get('error_expense');
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}
		
		return !$this->error;
	}
}

