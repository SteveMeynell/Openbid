<?php
class ControllerAccountReview extends Controller {
  private $error = array();

	public function index() {

    // Customer must be logged in to write a review
    if (!$this->customer->isLogged()) {
      // might need some variables to come back with
			$this->session->data['redirect'] = $this->url->link('account/review', 'review_id=' . $this->request->get['review_id'], true);
			$this->response->redirect($this->url->link('account/login', '', true));
		}
    
    $this->load->language('account/review');
    $this->load->model('account/review');

		$this->document->setTitle($this->language->get('heading_title'));

    $url = '';

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
    }
    
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
			'text' => $this->language->get('text_review'),
			'href' => $this->url->link('account/review', '', true)
		);

    $data['heading_title'] = $this->language->get('heading_title');
    $data['button_continue'] = $this->language->get('button_continue');

    $customer_id = $this->customer->getId();
    $data['reviews'] = $this->model_account_review->getReviewsByCustomerId($customer_id);
    $review_total = $this->model_account_review->getTotalReviewsBySellerId($customer_id) + $this->model_account_review->getTotalReviewsByBidderId($customer_id);
    $data['column_auction_title'] = $this->language->get('column_auction_title');
    $data['column_seller'] = $this->language->get('column_seller');
    $data['column_bidder'] = $this->language->get('column_bidder');
    $data['column_date_added'] = $this->language->get('column_date_added');
    
    if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

    $pagination = new Pagination();
		$pagination->total = $review_total;
		$pagination->page = $page;
		$pagination->limit = 10;
		$pagination->url = $this->url->link('account/review', 'page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($review_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($review_total - 10)) ? $review_total : ((($page - 1) * 10) + 10), $review_total, ceil($review_total / 10));

    $data['continue'] = $this->url->link('account/account', '', true);
    
    $data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('account/review_list', $data));
    
    // end of index
  }

  public function write() {
    // Customer must be logged in to write a review
    if (!$this->customer->isLogged()) {
      // might need some variables to come back with
			$this->session->data['redirect'] = $this->url->link('account/review', 'review_id=' . $this->request->get['review_id'], true);
			$this->response->redirect($this->url->link('account/login', '', true));
    }

    $this->load->language('account/review');
    $this->document->setTitle($this->language->get('heading_title'));

    $this->load->model('account/review');

    if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

      $this->model_account_review->addReview($this->request->post);
      $this->session->data['success'] = $this->language->get('text_add');

			// Add to activity log
			if ($this->config->get('config_customer_activity')) {
				$this->load->model('account/activity');

				$activity_data = array(
					'customer_id' => $this->customer->getId(),
					'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName()
				);

				$this->model_account_activity->addActivity('reviewed', $activity_data);
			}

			$this->response->redirect($this->url->link('account/review', '', true));
    }
    
    $this->getForm();
  }

  public function add() {
    
		$this->load->language('account/review');

		$json = array();

		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 25)) {
				$json['error'] = $this->language->get('error_name');
			}

			/*if ((utf8_strlen($this->request->post['text']) < 25) || (utf8_strlen($this->request->post['text']) > 1000)) {
				$json['error'] = $this->language->get('error_text');
			}*/

			if (empty($this->request->post['question1']) || $this->request->post['question1'] < 0 || $this->request->post['question1'] > 5) {
				$json['error'] = $this->language->get('error_rating1');
      } elseif (empty($this->request->post['question2']) || $this->request->post['question2'] < 0 || $this->request->post['question2'] > 5) {
				$json['error'] = $this->language->get('error_rating2');
      } elseif (empty($this->request->post['question3']) || $this->request->post['question3'] < 0 || $this->request->post['question3'] > 5) {
				$json['error'] = $this->language->get('error_rating3');
      } elseif (empty($this->request->post['question4']) || $this->request->post['question4'] < 0 || $this->request->post['question4'] > 5) {
				$json['error'] = $this->language->get('error_rating4');
      } elseif (empty($this->request->post['question5']) || $this->request->post['question5'] < 0 || $this->request->post['question5'] > 5) {
				$json['error'] = $this->language->get('error_rating5');
      } elseif (empty($this->request->post['question6']) || $this->request->post['question6'] < 0 || $this->request->post['question6'] > 5) {
				$json['error'] = $this->language->get('error_rating6');
			}

			// Captcha
			if ($this->config->get($this->config->get('config_captcha') . '_status') && in_array('review', (array)$this->config->get('config_captcha_page'))) {
				$captcha = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha') . '/validate');

				if ($captcha) {
					$json['error'] = $captcha;
				}
			}

			if (!isset($json['error'])) {
				$this->load->model('account/review');

				$this->model_account_review->addReview($this->request->post);

        $json['redirect'] = $this->url->link('account/review', '', true);
				$json['success'] = $this->language->get('text_success');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
    $this->response->setOutput(json_encode($json));
    // end of add
  }
  
  public function view() {
    $review_id = $this->request->get['review_id'];
    
    $json = array();
    $this->load->model('account/review');

    $json['review'] = $this->model_account_review->getReview($review_id);

    $this->response->addHeader('Content-Type: application/json');
    $this->response->setOutput(json_encode($json));
  }

  public function sendReminder() {
    $review_id = $this->request->get['review_id'];
    $reviewId = $this->db->escape($review_id);
    
    $this->load->model('account/review');
    $this->load->language('mail/review');
    $reminders = $this->model_account_review->getWhoToRemind($reviewId);
    foreach($reminders as $group => $info) {
      $mailInfo['email'] = $info['email'];
      $mailInfo['subject'] = $this->language->get($group . '_reminder_subject');
      $mailInfo['message'] = $this->language->get($group . '_reminder_message');
      $this->mailReminder($mailInfo);
    }
    $json = array();

    $json['success'] = 'yes testing';

    $this->response->addHeader('Content-Type: application/json');
    $this->response->setOutput(json_encode($json));
  }

  protected function mailReminder($mailInfo) {
    $mail = new Mail();
		$mail->protocol = $this->config->get('config_mail_protocol');
		$mail->parameter = $this->config->get('config_mail_parameter');
		$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
		$mail->smtp_username = $this->config->get('config_mail_smtp_username');
		$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
		$mail->smtp_port = $this->config->get('config_mail_smtp_port');
		$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

		$mail->setTo($mailInfo['email']);
		$mail->setFrom($this->config->get('config_email'));
		$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
		$mail->setSubject($mailInfo['subject']);
		$mail->setText($mailInfo['message']);
		$mail->send();
  }
  protected function getList() {

    
	
    // end of getList
  }

  protected function getForm(){

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
			'href' => $this->url->link('account/review', '', true)
		);

    $data['heading_title'] = $this->language->get('heading_title');
    $data['text_write'] = $this->language->get('text_write');
    $data['text_note'] = $this->language->get('text_note');
    $data['text_loading'] = $this->language->get('text_loading');
    $data['customer_id'] = $this->customer->getId();
    $data['welcome_message'] = $this->language->get('welcome_message');
    $data['entry_bad'] = $this->language->get('entry_bad');
    $data['entry_good'] = $this->language->get('entry_good');
    $data['entry_name'] = $this->language->get('entry_name');
    $data['entry_final_suggestion'] = $this->language->get('entry_review');
    $data['entry_suggestion4'] = $this->language->get('entry_suggestion4');
    $data['entry_suggestion5'] = $this->language->get('entry_suggestion5');
    $data['entry_suggestion6'] = $this->language->get('entry_suggestion6');


    if($this->request->get['group'] == 'seller') {
      $data['entry_question1'] = $this->language->get('seller_question1');
      $data['entry_question2'] = $this->language->get('seller_question2');
      $data['entry_question3'] = $this->language->get('seller_question3');
      $data['entry_suggestion1'] = $this->language->get('seller_suggestion1');
      $data['entry_suggestion2'] = $this->language->get('seller_suggestion2');
      $data['entry_suggestion3'] = $this->language->get('seller_suggestion3');
    } elseif($this->request->get['group'] == 'bidder') {
      $data['entry_question1'] = $this->language->get('bidder_question1');
      $data['entry_question2'] = $this->language->get('bidder_question2');
      $data['entry_question3'] = $this->language->get('bidder_question3');
      $data['entry_suggestion1'] = $this->language->get('bidder_suggestion1');
      $data['entry_suggestion2'] = $this->language->get('bidder_suggestion2');
      $data['entry_suggestion3'] = $this->language->get('bidder_suggestion3');
    } else {
      $data['entry_question1'] = $this->language->get('guest_question1');
      $data['entry_question2'] = $this->language->get('guest_question2');
      $data['entry_question3'] = $this->language->get('guest_question3');
      $data['entry_suggestion1'] = $this->language->get('guest_suggestion1');
      $data['entry_suggestion2'] = $this->language->get('guest_suggestion2');
      $data['entry_suggestion3'] = $this->language->get('guest_suggestion3');
    }

    $data['entry_question4'] = $this->language->get('site_question1');
    $data['entry_question5'] = $this->language->get('site_question2');
    $data['entry_question6'] = $this->language->get('site_question3');

    if ($this->config->get($this->config->get('config_captcha') . '_status') && in_array('review', (array)$this->config->get('config_captcha_page'))) {
      $data['captcha'] = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha'));
    } else {
      $data['captcha'] = '';
    }

    $data['customer_name'] = $this->model_account_review->getReviewersName($this->request->get['review_id'], $this->request->get['group']);
    $data['review_id'] = $this->request->get['review_id'];
    $data['group'] = $this->request->get['group'];

    $data['button_save'] = $this->language->get('button_save');
    $data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

    $this->response->setOutput($this->load->view('account/review_form', $data));
    // end of getForm
  }

  // end of Controller
}