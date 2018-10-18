<?php
class ControllerExtensionFeesBuyNow extends Controller {
	private $error = array();

	public function index() {

		$this->load->language('extension/fees/buy_now');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');
	
		$setting_code = $this->language->get('setting_code');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			
			if (!isset($setting_code)){
				$setting_code = $this->language->get('setting_code');
			}
			$existing_fees = $this->model_setting_setting->getSetting($setting_code);
			$existing_fees_count = round(count($existing_fees) / 5);
			
			$new_fees = $this->request->post;
			
			foreach($new_fees as $key => $value){
				$new_key = $setting_code . '_' . $key . '_' . strval($existing_fees_count + 1);
				$feePost[$new_key] = $value;
			}
			
			$new_key = $setting_code . '_status_' . strval($existing_fees_count + 1);
			$feePost[$new_key] = '1';
			$feePost[$setting_code . '_status']	=	'1';
			$fee2Post = array_merge($existing_fees,$feePost);
			
			$this->model_setting_setting->editSetting($setting_code, $fee2Post);

			$this->session->data['success'] = $this->language->get('text_success');

		}
		
		
		$existing_fees = $this->model_setting_setting->getSetting($setting_code);
		$existing_fees_count = count($existing_fees) / 5;
		
		$data['fee_datas'] = array();
		
			if (count($existing_fees)){
				for($row_counter=1; $row_counter<$existing_fees_count;$row_counter++){
					$data['fee_datas'][$row_counter] = array(
						'feeRow'		=> $row_counter,
						'fromAmount'	=> $existing_fees[$setting_code . '_fromAmount_' . $row_counter],
						'toAmount'		=> $existing_fees[$setting_code . '_toAmount_' . $row_counter],
						'feeAmount'		=> $existing_fees[$setting_code . '_feeAmount_' . $row_counter],
						'feeType'		=> $existing_fees[$setting_code . '_feeType_' . $row_counter],
						'feeStatus'		=> $existing_fees[$setting_code . '_status_' . $row_counter],
						'feeAction'		=> 'button here'
					);
				};
			} else {
				$data['fee_datas'][0] = array(
					'feeRow'		=> '0',
					'fromAmount'	=> 'Nothing Yet',
					'toAmount'		=> 'Nothing Yet',
					'feeAmount'		=> 'Nothing Yet',
					'feeType'		=> 'Nothing Yet',
					'feeStatus'		=> 'Nothing Yet',
					'feeAction'		=> 'Nothing Yet'
				);
			};

				
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
			'href' => $this->url->link('extension/fees/buy_now', 'token=' . $this->session->data['token'], true)
		);

		$data['action'] = $this->url->link('extension/fees/buy_now', 'token=' . $this->session->data['token'], true);

		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=fees', true);
		
		$data['token'] = $this->session->data['token'];
				
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/fees/buy_now', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/fees/buy_now')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
	
}
