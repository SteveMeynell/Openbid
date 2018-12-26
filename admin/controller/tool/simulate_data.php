<?php
class ControllerToolSimulateData extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('tool/simulate_data');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('tool/simulate_data');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->user->hasPermission('modify', 'tool/simulate_data')) {
			
			if (isset($this->request->post['newAuction'])) {
				
				$tempJson = file_get_contents(DIR_TEMPDATA . "Auction_Data.json");
				$Auctions = json_decode($tempJson, true);
				$this->load->model('customer/customer');
				$filter['filter_customer_group_id'] = '2';
				$totalSellersOnly = $this->model_customer_customer->getCustomers($filter);
				$filter['filter_customer_group_id'] = '3';
				$totalBoth = $this->model_customer_customer->getCustomers($filter);
				$totalSellers = array_merge($totalSellersOnly, $totalBoth);
				$this->load->model('catalog/category');
				$categories = $this->model_catalog_category->getCategories();
				$this->load->model('catalog/auction');
				
				$whichCategory = rand(1, count($categories));
				
				
				//$newAuction = $Auctions[0];
				//foreach($Auctions as $newAuction) {
					//debuglog($newAuction);
					$chance = rand(0,999);
					$newAuction = $Auctions[$chance];
					//debuglog($newAuction);
					//$newData = array();
					//if($chance>=25){
						//Pick a customer
						$SellerInfo = $totalSellers[rand(0,count($totalSellers)-1)];
						$newData['store_id']	=	$SellerInfo['store_id'];
						$newData['seller_id']	=	$SellerInfo['customer_id'];
						$newData['auction_type']	=	'0';
						$newData['auction_status']			=	'1';
						$newData['num_relist']		=	'5';
						$query = "SELECT NOW() as currenttime";
						$current_datetime = $this->db->query($query)->row;
						$newData['date_created']	=	$current_datetime['currenttime'];
						$newData['title']			=	$newAuction['title'];
						$newData['subtitle']		=	$newAuction['subtitle'];
						$seader = $newAuction['title'] . ' ' . (null !== $newAuction['subtitle'] ? $newAuction['subtitle'] .' ': '') . $newAuction['description'];
						$newData['auction_description'][1]	=	array(
																  'name' => $newAuction['title'],
																  'subname' => $newAuction['subtitle'],
																  'description' => $newAuction['description'],
																  'tag' => 'put tag code in',
																  'meta_title' => 'Auctioning ' . $newAuction['title'],
																  'meta_description' => strip_tags($newAuction['description']),
																  'meta_keyword' => make_keywords($seader)
																 );
						$NumHours = rand(2,24);
						$newStartDates = date_add(date_create($newData['date_created']),date_interval_create_from_date_string($NumHours . ' hours'));
						$newData['custom_start_date']		=	$newStartDates->format('Y-m-d H:i:s');
						$numDays = rand(1,5);
						$end_date = date_add($newStartDates,date_interval_create_from_date_string($numDays . ' days'));
						
						$imgnum = rand(1,25);
						$testimage = 'catalog/auctions/IMG_' . $imgnum . '.JPG';
						
						$newData['image'] = $testimage;
						//date_add($end_date,date_interval_create_from_date_string('1 days'));
						$newData['custom_end_date']		= $end_date->format('Y-m-d H:i:s');
						$newData['duration']		=	$numDays;
						$newData['min_bid']	=	$newAuction['min_bid'];
						$newData['shipping_cost']	= '0';
						$newData['additional_shipping']	=	'0';
						$newData['reserve_price']	=	(float)$newAuction['min_bid'] * 1.5;
						$newData['increment']	=	'1';
						$newData['shipping']	=	'0';
						$newData['payment']		=	'0';
						$newData['international_shipping']	=	'0';
						$newData['initial_quantity']	=	'1';
						$newData['buy_now_price']	=	round((float)$newAuction['min_bid'] * 2.5);
						$newData['auction_store'][] = '0';
						
						
						$newData['bolded_item']		=	$newAuction['bolded_item'];
						$newData['on_carousel']		=	$newAuction['on_carousel'];
						$newData['buy_now_only']		=	$newAuction['buy_now'];
						$newData['featured']		=	$newAuction['featured'];
						$newData['highlighted']		=	$newAuction['highlighted'];
						$newData['slideshow']		=	$newAuction['slideshow'];
						$newData['social_media']		=	$newAuction['social_media'];
						$newData['auto_relist']		=	'1';
						$newData['auction_category'][0]	=	$whichCategory;
						//debuglog("new data:");
						//debuglog($newData);
						$this->model_catalog_auction->addAuction($newData);
						//debuglog($id);
						// $newAuction Info
					/*} else {
						debuglog("Naa don't want that one");
					}
				}*/
				$json = array();
				$json['success'] = $newData['title'];
			}
			
			if (isset($this->request->post['newUser'])) {
				$newUsers = $this->request->post['newUser']['results'];
				foreach($newUsers as $newUser){
					//debuglog($newUser);
					$this->model_tool_simulate_data->simulateUser($newUser);
				}
				$json = array();
				$json['success'] = $newUsers[0]['name']['first'] . ' ' . $newUsers[0]['name']['last'];
				
			}
			$this->response->setOutput(json_encode($json));
			exit;
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_select_all'] = $this->language->get('text_select_all');
		$data['text_unselect_all'] = $this->language->get('text_unselect_all');

		$data['entry_type'] = $this->language->get('entry_type');
		
		$data['entry_users']	= $this->language->get('entry_users');
		$data['entry_auctions']	= $this->language->get('entry_auctions');
		$data['entry_bids']	= $this->language->get('entry_bids');
		
		$data['button_simulate'] = $this->language->get('button_simulate');

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
			'href' => $this->url->link('tool/simulate_data', 'token=' . $this->session->data['token'], true)
		);

		$data['simulate'] = $this->url->link('tool/simulate_data', 'token=' . $this->session->data['token'], true);
		$data['usersimulator']	= $this->url->link('tool/simulate_data/simulateUsers', 'token=' . $this->session->data['token'], true);

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('tool/simulate_data', $data));
	}

	public function backup() {
		$this->load->language('tool/backup');

		if (!isset($this->request->post['backup'])) {
			$this->session->data['error'] = $this->language->get('error_export');

			$this->response->redirect($this->url->link('tool/backup', 'token=' . $this->session->data['token'], true));
		} elseif ($this->user->hasPermission('modify', 'tool/backup')) {
			$this->response->addheader('Pragma: public');
			$this->response->addheader('Expires: 0');
			$this->response->addheader('Content-Description: File Transfer');
			$this->response->addheader('Content-Type: application/octet-stream');
			$this->response->addheader('Content-Disposition: attachment; filename="' . DB_DATABASE . '_' . date('Y-m-d_H-i-s', time()) . '_backup.sql"');
			$this->response->addheader('Content-Transfer-Encoding: binary');

			$this->load->model('tool/backup');

			$this->response->setOutput($this->model_tool_backup->backup($this->request->post['backup']));
		} else {
			$this->session->data['error'] = $this->language->get('error_permission');

			$this->response->redirect($this->url->link('tool/backup', 'token=' . $this->session->data['token'], true));
		}
	}

	public function simulateAuction(){
		debuglog("ok simulating an auction");
		$json = array();
		$json['success'] = 'Ok got something';
		$this->response->setOutput(json_encode($json));
		exit;
	}

}