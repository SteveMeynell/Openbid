<?php
class ControllerAccountVerify extends Controller {
	private $error = array();

	public function index() {
		if ($this->customer->isLogged()) {
			$this->response->redirect($this->url->link('account/account', '', true));
		}

		if (isset($this->request->get['code'])) {
			$code = $this->request->get['code'];
		} else {
			$code = '';
		}

		$this->load->model('account/customer');

		$customer_info = $this->model_account_customer->getCustomerByCode($code);

		if ($customer_info) {
			$this->load->language('account/verify');

			$this->document->setTitle($this->language->get('heading_title'));

			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

				$this->load->language('mail/verify');
				if($this->model_account_customer->getCustomerForVerification($code, $this->request->post['password'])) {
					
					$subject = sprintf($this->language->get('text_subject'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));

					$message = sprintf($this->language->get('text_welcome'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8')) . "\n\n";

					$message .= $this->language->get('text_thanks_verified') . "\n";
					if(!$customer_info['approved']) {
						$message .= $this->language->get('text_authorize') . "\n";
					}
					$message .= $this->language->get('text_login') . "\n";
					$message .= $this->url->link('account/login', '', true) . "\n\n";
					$message .= $this->language->get('text_services') . "\n\n";
					$message .= $this->language->get('text_thanks') . "\n";
					$message .= html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8');

					$mail = new Mail();
					$mail->protocol = $this->config->get('config_mail_protocol');
					$mail->parameter = $this->config->get('config_mail_parameter');
					$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
					$mail->smtp_username = $this->config->get('config_mail_smtp_username');
					$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
					$mail->smtp_port = $this->config->get('config_mail_smtp_port');
					$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

					$mail->setTo($customer_info['email']);
					$mail->setFrom($this->config->get('config_email'));
					$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
					$mail->setSubject($subject);
					$mail->setText($message);
					$mail->send();

					$activity = 'verify';

					$this->session->data['success'] = $this->language->get('text_success');

				} else {
					debuglog("Oh oh something went wrong");
					$activity = 'attempted_verify';
				}

				$this->load->model('account/customer_group');

				$customer_group_info = $this->model_account_customer_group->getCustomerGroup($customer_info['customer_group_id']);

				// Send to main admin email if new account email is enabled
				if (in_array('account', (array)$this->config->get('config_mail_alert'))) {
					$message  = $this->language->get('text_verified_email') . "\n\n";
					$message .= $this->language->get('text_website') . ' ' . html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8') . "\n";
					$message .= $this->language->get('text_firstname') . ' ' . $customer_info['firstname'] . "\n";
					$message .= $this->language->get('text_lastname') . ' ' . $customer_info['lastname'] . "\n";
					$message .= $this->language->get('text_customer_group') . ' ' . $customer_group_info['name'] . "\n";
					$message .= $this->language->get('text_email') . ' '  .  $customer_info['email'] . "\n";
					$message .= $this->language->get('text_telephone') . ' ' . $customer_info['telephone'] . "\n";

					$mail = new Mail();
					$mail->protocol = $this->config->get('config_mail_protocol');
					$mail->parameter = $this->config->get('config_mail_parameter');
					$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
					$mail->smtp_username = $this->config->get('config_mail_smtp_username');
					$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
					$mail->smtp_port = $this->config->get('config_mail_smtp_port');
					$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

					$mail->setTo($this->config->get('config_email'));
					$mail->setFrom($this->config->get('config_email'));
					$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
					$mail->setSubject(html_entity_decode($this->language->get('text_customer'), ENT_QUOTES, 'UTF-8'));
					$mail->setText($message);
					$mail->send();

					// Send to additional alert emails if new account email is enabled
					$emails = explode(',', $this->config->get('config_alert_email'));

					foreach ($emails as $email) {
						if (utf8_strlen($email) > 0 && filter_var($email, FILTER_VALIDATE_EMAIL)) {
							$mail->setTo($email);
							$mail->send();
						}
					}
				}

				if ($this->config->get('config_customer_activity')) {
					$this->load->model('account/activity');

					$activity_data = array(
						'customer_id' => $customer_info['customer_id'],
						'name'        => $customer_info['firstname'] . ' ' . $customer_info['lastname']
					);

					$this->model_account_activity->addActivity($activity, $activity_data);
				}

				$this->response->redirect($this->url->link('account/login', '', true));
			}

			$data['heading_title'] = $this->language->get('heading_title');

			$data['text_password'] = $this->language->get('text_password');

			$data['entry_password'] = $this->language->get('entry_password');

			$data['button_continue'] = $this->language->get('button_continue');
			$data['button_back'] = $this->language->get('button_back');

			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/home')
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_account'),
				'href' => $this->url->link('account/account', '', true)
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('account/verify', '', true)
			);

			if (isset($this->error['password'])) {
				$data['error_password'] = $this->error['password'];
			} else {
				$data['error_password'] = '';
			}

			$data['action'] = $this->url->link('account/verify', 'code=' . $code, true);

			$data['back'] = $this->url->link('account/login', '', true);

			if (isset($this->request->post['password'])) {
				$data['password'] = $this->request->post['password'];
			} else {
				$data['password'] = '';
			}

			if (isset($this->request->post['confirm'])) {
				$data['confirm'] = $this->request->post['confirm'];
			} else {
				$data['confirm'] = '';
			}

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('account/verify', $data));
		} else {
			$this->load->language('account/verify');

			$this->session->data['error'] = $this->language->get('error_code');

			return new Action('account/login');
		}
	}

	protected function validate() {
		if ((utf8_strlen($this->request->post['password']) < 4) || (utf8_strlen($this->request->post['password']) > 20)) {
			$this->error['password'] = $this->language->get('error_password');
		}

		return !$this->error;
	}
}
