<?php
class ControllerCheckoutSuccess extends Controller {
	public function index() {
		$this->load->language('checkout/success');
		if($this->request->post) {
			$paymentInfo = $this->request->post;
			//debuglog($paymentInfo);
			if($paymentInfo['payment_status'] == 'Completed') {
				// this is information coming back from PayPal.
				// one this is the mc_fee variable.  That is how much was removed from the fee paid by PayPal.  This amount should be tracked.

				$this->load->model('checkout/order');
				$this->model_checkout_order->addOrderHistory($paymentInfo['custom'], '5', '', true);
				$this->load->model('auction/auction');
				$auctionIds = $this->model_auction_auction->getAuctionStatusByOrder($paymentInfo['custom']);
				foreach($auctionIds as $auctionId) {
					if($auctionId['status'] == $this->config->get('config_auction_created_status')) {
						$customerInfo = $this->model_auction_auction->openAuction($auctionId['auction_id']);
					}
				}
				if (isset($this->session->data['order_id'])) {
					$this->cart->clear();
		
					// Add to activity log
					if ($this->config->get('config_customer_activity')) {
						$this->load->model('account/activity');
		
						if ($this->customer->isLogged()) {
							$activity_data = array(
								'customer_id' => $this->customer->getId(),
								'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName(),
								'order_id'    => $this->session->data['order_id']
							);
		
							$this->model_account_activity->addActivity('order_account', $activity_data);
						} else {
							$activity_data = array(
								'name'     => $this->session->data['guest']['firstname'] . ' ' . $this->session->data['guest']['lastname'],
								'order_id' => $this->session->data['order_id']
							);
		
							$this->model_account_activity->addActivity('order_guest', $activity_data);
						}
					}
		
					unset($this->session->data['shipping_method']);
					unset($this->session->data['shipping_methods']);
					unset($this->session->data['payment_method']);
					unset($this->session->data['payment_methods']);
					unset($this->session->data['guest']);
					unset($this->session->data['comment']);
					unset($this->session->data['order_id']);
					unset($this->session->data['coupon']);
					unset($this->session->data['reward']);
					unset($this->session->data['voucher']);
					unset($this->session->data['vouchers']);
					unset($this->session->data['totals']);
				}
		
				$this->document->setTitle($this->language->get('heading_title'));
		
				$data['breadcrumbs'] = array();
		
				$data['breadcrumbs'][] = array(
					'text' => $this->language->get('text_home'),
					'href' => $this->url->link('common/home')
				);
		
				$data['breadcrumbs'][] = array(
					'text' => $this->language->get('text_basket'),
					'href' => $this->url->link('checkout/cart')
				);
		
				$data['breadcrumbs'][] = array(
					'text' => $this->language->get('text_checkout'),
					'href' => $this->url->link('checkout/checkout', '', true)
				);
		
				$data['breadcrumbs'][] = array(
					'text' => $this->language->get('text_success'),
					'href' => $this->url->link('checkout/success')
				);
		
				$data['heading_title'] = $this->language->get('heading_title');
		
				if ($this->customer->isLogged()) {
					$data['text_message'] = sprintf($this->language->get('text_customer'), $this->url->link('account/account', '', true), $this->url->link('account/order', '', true), $this->url->link('account/download', '', true), $this->url->link('information/contact'));
				} else {
					$data['text_message'] = sprintf($this->language->get('text_guest'), $this->url->link('information/contact'));
				}
		
				$data['button_continue'] = $this->language->get('button_continue');
		
				$data['continue'] = $this->url->link('common/home');
		
				$data['column_left'] = $this->load->controller('common/column_left');
				$data['column_right'] = $this->load->controller('common/column_right');
				$data['content_top'] = $this->load->controller('common/content_top');
				$data['content_bottom'] = $this->load->controller('common/content_bottom');
				$data['footer'] = $this->load->controller('common/footer');
				$data['header'] = $this->load->controller('common/header');
		
				$this->response->setOutput($this->load->view('common/success', $data));				
			}
			// not a post... what is it then?
			
		} else {
			if (isset($this->session->data['order_id'])) {
				debuglog("its just a success");
				$this->cart->clear();
	
				// Add to activity log
				if ($this->config->get('config_customer_activity')) {
					$this->load->model('account/activity');
	
					if ($this->customer->isLogged()) {
						$activity_data = array(
							'customer_id' => $this->customer->getId(),
							'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName(),
							'order_id'    => $this->session->data['order_id']
						);
	
						$this->model_account_activity->addActivity('order_account', $activity_data);
					} else {
						$activity_data = array(
							'name'     => $this->session->data['guest']['firstname'] . ' ' . $this->session->data['guest']['lastname'],
							'order_id' => $this->session->data['order_id']
						);
	
						$this->model_account_activity->addActivity('order_guest', $activity_data);
					}
				}
	
				unset($this->session->data['shipping_method']);
				unset($this->session->data['shipping_methods']);
				unset($this->session->data['payment_method']);
				unset($this->session->data['payment_methods']);
				unset($this->session->data['guest']);
				unset($this->session->data['comment']);
				unset($this->session->data['order_id']);
				unset($this->session->data['coupon']);
				unset($this->session->data['reward']);
				unset($this->session->data['voucher']);
				unset($this->session->data['vouchers']);
				unset($this->session->data['totals']);
			}
			$this->document->setTitle($this->language->get('heading_title'));
		
				$data['breadcrumbs'] = array();
		
				$data['breadcrumbs'][] = array(
					'text' => $this->language->get('text_home'),
					'href' => $this->url->link('common/home')
				);
		
				$data['breadcrumbs'][] = array(
					'text' => $this->language->get('text_basket'),
					'href' => $this->url->link('checkout/cart')
				);
		
				$data['breadcrumbs'][] = array(
					'text' => $this->language->get('text_checkout'),
					'href' => $this->url->link('checkout/checkout', '', true)
				);
		
				$data['breadcrumbs'][] = array(
					'text' => $this->language->get('text_success'),
					'href' => $this->url->link('checkout/success')
				);
		
				$data['heading_title'] = $this->language->get('heading_title');
		
				if ($this->customer->isLogged()) {
					$data['text_message'] = sprintf($this->language->get('text_customer'), $this->url->link('account/account', '', true), $this->url->link('account/order', '', true), $this->url->link('account/download', '', true), $this->url->link('information/contact'));
				} else {
					$data['text_message'] = sprintf($this->language->get('text_guest'), $this->url->link('information/contact'));
				}
		
				$data['button_continue'] = $this->language->get('button_continue');
		
				$data['continue'] = $this->url->link('common/home');
		
				$data['column_left'] = $this->load->controller('common/column_left');
				$data['column_right'] = $this->load->controller('common/column_right');
				$data['content_top'] = $this->load->controller('common/content_top');
				$data['content_bottom'] = $this->load->controller('common/content_bottom');
				$data['footer'] = $this->load->controller('common/footer');
				$data['header'] = $this->load->controller('common/header');
		
				$this->response->setOutput($this->load->view('common/success', $data));
			}
		// end of index
	}
	// end of Controller
}