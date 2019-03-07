<?php
class ControllerCommonSellSomething extends Controller {
	public function index() {
		
		$this->load->language('common/sell_something');

		if (!$this->customer->isLogged()) {
			$data['text_sell'] = $this->language->get('text_login');
			$data['text_div'] = $this->language->get('div_login_please');
		} elseif ($this->customer->getGroupId() > '1') {
			$data['text_div'] = $this->language->get('div_sell');
			$data['text_sell'] = $this->language->get('text_sell');
		} else {
			$data['text_div'] = $this->language->get('div_become_seller');
			$data['text_sell'] = $this->language->get('text_become_seller');
		}
		

		return $this->load->view('common/sell_something', $data);
	}
}