<?php
class ControllerToolFacebookTest extends Controller {
  private $error = array();
 
  public function index() {
    $this->load->language('tool/facebook_test');

    $this->document->setTitle($this->language->get('heading_title'));
    
    $this->load->model('tool/facebook_test');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->user->hasPermission('modify', 'tool/facebook_test')) {
      debuglog($this->request->post);
      if($this->request->post['type'] == 'create') {
        $this->testCreation();
      }
    }
  

    $data['heading_title'] = $this->language->get('heading_title');

		$data['text_select_all'] = $this->language->get('text_select_all');
		$data['text_unselect_all'] = $this->language->get('text_unselect_all');

		$data['entry_type'] = $this->language->get('entry_type');
		
		$data['entry_facebook_test']	= $this->language->get('entry_facebook_test');
		$data['entry_auctions']	= $this->language->get('entry_auctions');
		$data['entry_bids']	= $this->language->get('entry_bids');
		
		$data['button_facebook'] = $this->language->get('button_facebook');

		if (isset($this->session->data['error'])) {
			$data['error_warning'] = $this->session->data['error'];

			unset($this->session->data['error']);
		} elseif (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('tool/facebook_test', 'token=' . $this->session->data['token'], true)
		);

    $data['facebooktest'] = $this->url->link('tool/facebook_test', 'token=' . $this->session->data['token'], true);
    
    $data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

    $this->response->setOutput($this->load->view('tool/facebook_test', $data));
  }

  public function testCreation(){
    require DIR_SYSTEM . '../vendor/Facebook/Facebook.php';
    $json = array();

    $pageId = '546415602553665';
    $access_token = 'EAAFt4bVT8Y4BAPZCRHptVVMzZCaS2d0gAOAZCBcA53rvEJrMkcVffZAiHK3geFe7pyvblBnHGKnd20ZAftl9XVq8zxktG1tq5X7sQGmsngM8ZCnR20hXHACleXF8aAa1oCokZBlMU6X3JQv8O2RreaiZAqsNKk14hrRkSQhkTHZCtZBOzZBjHKya1ZCLIXcekoTupbty2zoFC05cbgZDZD';
    debuglog("ok in the controller");
    $fb = new \Facebook\Facebook(array(
      'app_id' => '402291153957262',
      'app_secret' => '1c5a0b65683b236ebb994169d51da56a',
      'default_graph_version' => 'v2.10'
      //'default_access_token' => $access_token
    ));
    
    try {
      // Get the \Facebook\GraphNodes\GraphUser object for the current user.
      // If you provided a 'default_access_token', the '{access-token}' is optional.
      $response = $fb->get('/me');
    } catch(\Facebook\Exceptions\FacebookResponseException $e) {
      // When Graph returns an error
      debuglog('Graph returned an error: ' . $e->getMessage());
      exit;
    } catch(\Facebook\Exceptions\FacebookSDKException $e) {
      // When validation fails or other local issues
      debuglog('Facebook SDK returned an error: ' . $e->getMessage());
      
      exit;
    }
    
    $me = $response->getGraphUser();
    debuglog('Logged in as ' . $me->getName());

    $this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
  }
  // end of controller
}