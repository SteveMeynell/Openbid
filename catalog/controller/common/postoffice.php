<?php
class ControllerCommonPostoffice extends Controller {

  public function index() {
    $this->response->redirect($this->url->link('account/login', '', true));
  }

  public function sendBuyNow($buynowinfo) {
    // must get mailInfo stuff sent in
    // must validate there is proper information
    // could set the type here and then call sendMail

    $isSent = false;

    if ($this->validate()) {
      $isSent = $this->sendMail($buynowinfo);
    }

    return $isSent;
  }




  private function sendMail($mailInfo) {

  debuglog($mailInfo);
  if (!isset($mailInfo['type'])) {
    $sendmsg = 'admin';
  } else {
    $this->load->language('mail/auction');
    switch ($mailInfo['type']) {
      case 'buynow':
      // mail to them
      $sellerSubject = html_entity_decode($this->language->get('text_subject_bn_seller'), ENT_QUOTES, 'UTF-8');
      $bidderSubject = html_entity_decode($this->language->get('text_subject_bn_bidder'), ENT_QUOTES, 'UTF-8');
      $sellerMessage = html_entity_decode(sprintf($this->language->get('text_message_bn_seller'),$mailInfo['seller_name'],$mailInfo['title'],$mailInfo['bidder_email']), ENT_QUOTES, 'UTF-8');
      $bidderMessage = html_entity_decode(sprintf($this->language->get('text_message_bn_bidder'),$mailInfo['bidder_name'],$mailInfo['title'],$mailInfo['seller_email']), ENT_QUOTES, 'UTF-8');
      $sendmsg = 'all';

      break;
      default:
      $sendmsg = 'admin';
      break;
    }
  }

  if ($sendmsg == 'all') {
    // Send to Seller
    $mail = new Mail();
          $mail->protocol = $this->config->get('config_mail_protocol');
          $mail->parameter = $this->config->get('config_mail_parameter');
          $mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
          $mail->smtp_username = $this->config->get('config_mail_smtp_username');
          $mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
          $mail->smtp_port = $this->config->get('config_mail_smtp_port');
          $mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
                    
          $mail->setTo($mailInfo['seller_email']);
          $mail->setFrom($this->config->get('config_email'));
          $mail->setSender($this->language->get('text_sender'));
          $mail->setSubject($sellerSubject);
          $mail->setText($sellerMessage);
          $mail->send();
    // Send to Bidder
    $mail = new Mail();
          $mail->protocol = $this->config->get('config_mail_protocol');
          $mail->parameter = $this->config->get('config_mail_parameter');
          $mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
          $mail->smtp_username = $this->config->get('config_mail_smtp_username');
          $mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
          $mail->smtp_port = $this->config->get('config_mail_smtp_port');
          $mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
                    
          $mail->setTo($mailInfo['bidder_email']);
          $mail->setFrom($this->config->get('config_email'));
          $mail->setSender($this->language->get('text_sender'));
          $mail->setSubject($bidderSubject);
          $mail->setText($bidderMessage);
          $mail->send();
          $sent = true;
  } else {
    // send to admin
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
          $mail->setSender($this->language->get('text_sender'));
          $mail->setSubject('Something went wrong');
          $mail->setText("I don't know what but something went wrong");
          $mail->send();
          $sent = true;
  }
  return $sent;
  }

  protected function validate() {
    if ($this->request->post) {
      debuglog("ok its a post good");
      debuglog($this->request->post);
    } else {
      debuglog("must pass info in and validate it");
    }
    return true;
  }
// End of Controller
}