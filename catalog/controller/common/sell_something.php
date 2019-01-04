<?php
class ControllerCommonSellSomething extends Controller {
	public function index() {
		
		$this->load->language('common/sell_something');

		$data['text_sell'] = $this->language->get('text_sell');

		return $this->load->view('common/sell_something', $data);
	}
}