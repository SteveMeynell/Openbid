<?php
class ModelReportAuction extends Model {
	public function getTotalClosedAuctions($data = array()) {
		
		$auction_status = $this->config->get('config_auction_closed_status');
		
		$sql = "SELECT SUM(winning_bid) AS total FROM `" . DB_PREFIX . "auctions` WHERE status = '" . $auction_status . "'";

		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(date_created) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}
	
	public function getTotalOpenAuctions($data = array()) {
		
		$auction_status = $this->config->get('config_auction_open_status');
		
		$sql = "SELECT SUM(winning_bid) AS total FROM `" . DB_PREFIX . "auctions` WHERE status = '" . $auction_status . "'";

		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(date_created) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}
	
	public function getTotalAuctions($data = array()) {
		
		$auction_status = $this->config->get('config_auction_moderation_status');
		
		$sql = "SELECT SUM(winning_bid) AS total FROM `" . DB_PREFIX . "auctions` WHERE status > '" . $auction_status . "'";

		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(date_created) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getTotalAuctionsByCountry() {
		$sql = "SELECT COUNT(*) AS total, SUM(a.winning_bid) AS amount, c.iso_code_2
								  FROM `" . DB_PREFIX . "auctions` a
								  LEFT JOIN `" . DB_PREFIX . "customer` cu
								  ON (a.customer_id = cu.customer_id)
								  LEFT JOIN `" . DB_PREFIX . "address` ad
								  ON (cu.address_id = ad.address_id)
								  LEFT JOIN `" . DB_PREFIX . "country` c
								  ON (ad.country_id = c.country_id)
								  
								  
								  
								  WHERE a.status > '0' AND a.status < '4'
								  GROUP BY ad.country_id";
		
		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getTotalClosedAuctionsByDay() {
		
		$auction_status = $this->config->get('config_auction_closed_status');
		

		$auction_data = array();

		for ($i = 0; $i < 24; $i++) {
			$auction_data[$i] = array(
				'hour'  => $i,
				'total' => 0
			);
		}
								  
		$query = $this->db->query("SELECT COUNT(*) AS total, HOUR(ad.end_date) AS hour
								  FROM " . DB_PREFIX . "auctions a 
								  LEFT JOIN " . DB_PREFIX . "auction_details ad 
								  ON (a.auction_id = ad.auction_id)
								  WHERE a.status ='" . $auction_status . "'
								  AND DATE(ad.end_date) = DATE(NOW())
								  GROUP BY HOUR(ad.end_date)
								  ORDER BY ad.end_date ASC");

		foreach ($query->rows as $result) {
			$auction_data[$result['hour']] = array(
				'hour'  => $result['hour'],
				'total' => $result['total']
			);
		}

		return $auction_data;
	}
	
	public function getTotalOpenAuctionsByDay() {
		$auction_status = $this->config->get('config_auction_open_status');

		$auction_data = array();

		for ($i = 0; $i < 24; $i++) {
			$auction_data[$i] = array(
				'hour'  => $i,
				'total' => 0
			);
		}

		$query = $this->db->query("SELECT COUNT(*) AS total, HOUR(ad.start_date) AS hour
								  FROM " . DB_PREFIX . "auctions a
								  LEFT JOIN " . DB_PREFIX . "auction_details ad
								  ON (a.auction_id = ad.auction_id) 
								  WHERE a.status ='" . $auction_status . "'
								  AND DATE(ad.start_date) = DATE(NOW())
								  GROUP BY HOUR(ad.start_date)
								  ORDER BY ad.start_date ASC");

		foreach ($query->rows as $result) {
			$auction_data[$result['hour']] = array(
				'hour'  => $result['hour'],
				'total' => $result['total']
			);
		}

		return $auction_data;
	}

	public function getTotalClosedAuctionsByWeek() {
		
		$auction_status = $this->config->get('config_auction_closed_status');

		$auction_data = array();

		$date_start = strtotime('-' . date('w') . ' days');

		for ($i = 0; $i < 7; $i++) {
			$date = date('Y-m-d', $date_start + ($i * 86400));

			$auction_data[date('w', strtotime($date))] = array(
				'day'   => date('D', strtotime($date)),
				'total' => 0
			);
		}

		$query = $this->db->query("SELECT COUNT(*) AS total, ad.end_date AS end_date 
								  FROM " . DB_PREFIX . "auctions a
								  LEFT JOIN " . DB_PREFIX . "auction_details ad
								  ON (a.auction_id = ad.auction_id) 
								  WHERE a.status ='" . $auction_status . "'
								  AND DATE(ad.end_date) >= DATE('" . $this->db->escape(date('Y-m-d', $date_start)) . "')
								  GROUP BY DAYNAME(ad.end_date)");

		foreach ($query->rows as $result) {
			$auction_data[date('w', strtotime($result['end_date']))] = array(
				'day'   => date('D', strtotime($result['end_date'])),
				'total' => $result['total']
			);
		}

		return $auction_data;
	}
	
	public function getTotalOpenAuctionsByWeek() {
		
		$auction_status = $this->config->get('config_auction_open_status');

		$auction_data = array();

		$date_start = strtotime('-' . date('w') . ' days');

		for ($i = 0; $i < 7; $i++) {
			$date = date('Y-m-d', $date_start + ($i * 86400));

			$auction_data[date('w', strtotime($date))] = array(
				'day'   => date('D', strtotime($date)),
				'total' => 0
			);
		}

		$query = $this->db->query("SELECT COUNT(*) AS total, ad.start_date AS start_date
								  FROM " . DB_PREFIX . "auctions a
								  LEFT JOIN " . DB_PREFIX . "auction_details ad
								  ON (a.auction_id = ad.auction_id) 
								  WHERE a.status = '" . $auction_status . "'
								  AND DATE(ad.start_date) >= DATE('" . $this->db->escape(date('Y-m-d', $date_start)) . "')
								  GROUP BY DAYNAME(ad.start_date)");

		foreach ($query->rows as $result) {
			$auction_data[date('w', strtotime($result['start_date']))] = array(
				'day'   => date('D', strtotime($result['start_date'])),
				'total' => $result['total']
			);
		}

		return $auction_data;
	}

	public function getTotalClosedAuctionsByMonth() {
		
		$auction_status = $this->config->get('config_auction_closed_status');

		$auction_data = array();

		for ($i = 1; $i <= date('t'); $i++) {
			$date = date('Y') . '-' . date('m') . '-' . $i;

			$auction_data[date('j', strtotime($date))] = array(
				'day'   => date('d', strtotime($date)),
				'total' => 0
			);
		}

		$query = $this->db->query("SELECT COUNT(*) AS total, ad.end_date AS end_date
								  FROM " . DB_PREFIX . "auctions a
								  LEFT JOIN " . DB_PREFIX . "auction_details ad
								  ON (a.auction_id = ad.auction_id) 
								  WHERE a.status = '" . $auction_status . "'
								  AND DATE(ad.end_date) >= '" . $this->db->escape(date('Y') . '-' . date('m') . '-1') . "'
								  GROUP BY DATE(ad.end_date)");

		foreach ($query->rows as $result) {
			$auction_data[date('j', strtotime($result['end_date']))] = array(
				'day'   => date('d', strtotime($result['end_date'])),
				'total' => $result['total']
			);
		}

		return $auction_data;
	}
	
	public function getTotalOpenAuctionsByMonth() {
		
		$auction_status = $this->config->get('config_auction_open_status');

		$auction_data = array();

		for ($i = 1; $i <= date('t'); $i++) {
			$date = date('Y') . '-' . date('m') . '-' . $i;

			$auction_data[date('j', strtotime($date))] = array(
				'day'   => date('d', strtotime($date)),
				'total' => 0
			);
		}

		$query = $this->db->query("SELECT COUNT(*) AS total, ad.start_date AS start_date 
								  FROM " . DB_PREFIX . "auctions a
								  LEFT JOIN " . DB_PREFIX . "auction_details ad
								  ON (a.auction_id = ad.auction_id) 
								  WHERE a.status = '" . $auction_status . "'
								  AND DATE(ad.start_date) >= '" . $this->db->escape(date('Y') . '-' . date('m') . '-1') . "'
								  GROUP BY DATE(ad.start_date)");

		foreach ($query->rows as $result) {
			$auction_data[date('j', strtotime($result['start_date']))] = array(
				'day'   => date('d', strtotime($result['start_date'])),
				'total' => $result['total']
			);
		}

		return $auction_data;
	}

	public function getTotalClosedAuctionsByYear() {
		
		$auction_status = $this->config->get('config_auction_closed_status');

		$auction_data = array();

		for ($i = 1; $i <= 12; $i++) {
			$auction_data[$i] = array(
				'month' => date('M', mktime(0, 0, 0, $i)),
				'total' => 0
			);
		}

		$query = $this->db->query("SELECT COUNT(*) AS total, ad.end_date AS end_date 
								  FROM " . DB_PREFIX . "auctions a
								  LEFT JOIN " . DB_PREFIX . "auction_details ad
								  ON (a.auction_id = ad.auction_id) 
								  WHERE a.status = '" . $auction_status . "'
								  AND YEAR(ad.end_date) = YEAR(NOW())
								  GROUP BY MONTH(ad.end_date)");

		foreach ($query->rows as $result) {
			$auction_data[date('n', strtotime($result['end_date']))] = array(
				'month' => date('M', strtotime($result['end_date'])),
				'total' => $result['total']
			);
		}

		return $auction_data;
	}
	
	public function getTotalOpenAuctionsByYear() {
		
		$auction_status = $this->config->get('config_auction_open_status');

		$auction_data = array();

		for ($i = 1; $i <= 12; $i++) {
			$auction_data[$i] = array(
				'month' => date('M', mktime(0, 0, 0, $i)),
				'total' => 0
			);
		}

		$query = $this->db->query("SELECT COUNT(*) AS total, ad.start_date AS start_date 
								  FROM " . DB_PREFIX . "auctions a
								  LEFT JOIN " . DB_PREFIX . "auction_details ad
								  ON (a.auction_id = ad.auction_id) 
								  WHERE a.status = '" . $auction_status . "'
								  AND YEAR(ad.start_date) = YEAR(NOW())
								  GROUP BY MONTH(ad.start_date)");

		foreach ($query->rows as $result) {
			$auction_data[date('n', strtotime($result['start_date']))] = array(
				'month' => date('M', strtotime($result['start_date'])),
				'total' => $result['total']
			);
		}

		return $auction_data;
	}

	public function getOrders($data = array()) {
		$sql = "SELECT MIN(o.date_added) AS date_start, MAX(o.date_added) AS date_end, COUNT(*) AS `orders`, SUM((SELECT SUM(op.quantity) FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id GROUP BY op.order_id)) AS products, SUM((SELECT SUM(ot.value) FROM `" . DB_PREFIX . "order_total` ot WHERE ot.order_id = o.order_id AND ot.code = 'tax' GROUP BY ot.order_id)) AS tax, SUM(o.total) AS `total` FROM `" . DB_PREFIX . "order` o";

		if (!empty($data['filter_order_status_id'])) {
			$sql .= " WHERE o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " WHERE o.order_status_id > '0'";
		}

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		if (!empty($data['filter_group'])) {
			$group = $data['filter_group'];
		} else {
			$group = 'week';
		}

		switch($group) {
			case 'day';
				$sql .= " GROUP BY YEAR(o.date_added), MONTH(o.date_added), DAY(o.date_added)";
				break;
			default:
			case 'week':
				$sql .= " GROUP BY YEAR(o.date_added), WEEK(o.date_added)";
				break;
			case 'month':
				$sql .= " GROUP BY YEAR(o.date_added), MONTH(o.date_added)";
				break;
			case 'year':
				$sql .= " GROUP BY YEAR(o.date_added)";
				break;
		}

		$sql .= " ORDER BY o.date_added DESC";

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getTotalOrders($data = array()) {
		if (!empty($data['filter_group'])) {
			$group = $data['filter_group'];
		} else {
			$group = 'week';
		}

		switch($group) {
			case 'day';
				$sql = "SELECT COUNT(DISTINCT YEAR(date_added), MONTH(date_added), DAY(date_added)) AS total FROM `" . DB_PREFIX . "order`";
				break;
			default:
			case 'week':
				$sql = "SELECT COUNT(DISTINCT YEAR(date_added), WEEK(date_added)) AS total FROM `" . DB_PREFIX . "order`";
				break;
			case 'month':
				$sql = "SELECT COUNT(DISTINCT YEAR(date_added), MONTH(date_added)) AS total FROM `" . DB_PREFIX . "order`";
				break;
			case 'year':
				$sql = "SELECT COUNT(DISTINCT YEAR(date_added)) AS total FROM `" . DB_PREFIX . "order`";
				break;
		}

		if (!empty($data['filter_order_status_id'])) {
			$sql .= " WHERE order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " WHERE order_status_id > '0'";
		}

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getTaxes($data = array()) {
		$sql = "SELECT MIN(o.date_added) AS date_start, MAX(o.date_added) AS date_end, ot.title, SUM(ot.value) AS total, COUNT(o.order_id) AS `orders` FROM `" . DB_PREFIX . "order` o LEFT JOIN `" . DB_PREFIX . "order_total` ot ON (ot.order_id = o.order_id) WHERE ot.code = 'tax'";

		if (!empty($data['filter_order_status_id'])) {
			$sql .= " AND o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " AND o.order_status_id > '0'";
		}

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		if (!empty($data['filter_group'])) {
			$group = $data['filter_group'];
		} else {
			$group = 'week';
		}

		switch($group) {
			case 'day';
				$sql .= " GROUP BY YEAR(o.date_added), MONTH(o.date_added), DAY(o.date_added), ot.title";
				break;
			default:
			case 'week':
				$sql .= " GROUP BY YEAR(o.date_added), WEEK(o.date_added), ot.title";
				break;
			case 'month':
				$sql .= " GROUP BY YEAR(o.date_added), MONTH(o.date_added), ot.title";
				break;
			case 'year':
				$sql .= " GROUP BY YEAR(o.date_added), ot.title";
				break;
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getTotalTaxes($data = array()) {
		if (!empty($data['filter_group'])) {
			$group = $data['filter_group'];
		} else {
			$group = 'week';
		}

		switch($group) {
			case 'day';
				$sql = "SELECT COUNT(DISTINCT YEAR(o.date_added), MONTH(o.date_added), DAY(o.date_added), ot.title) AS total FROM `" . DB_PREFIX . "order` o";
				break;
			default:
			case 'week':
				$sql = "SELECT COUNT(DISTINCT YEAR(o.date_added), WEEK(o.date_added), ot.title) AS total FROM `" . DB_PREFIX . "order` o";
				break;
			case 'month':
				$sql = "SELECT COUNT(DISTINCT YEAR(o.date_added), MONTH(o.date_added), ot.title) AS total FROM `" . DB_PREFIX . "order` o";
				break;
			case 'year':
				$sql = "SELECT COUNT(DISTINCT YEAR(o.date_added), ot.title) AS total FROM `" . DB_PREFIX . "order` o";
				break;
		}

		$sql .= " LEFT JOIN `" . DB_PREFIX . "order_total` ot ON (o.order_id = ot.order_id) WHERE ot.code = 'tax'";

		if (!empty($data['filter_order_status_id'])) {
			$sql .= " AND o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " AND o.order_status_id > '0'";
		}

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getShipping($data = array()) {
		$sql = "SELECT MIN(o.date_added) AS date_start, MAX(o.date_added) AS date_end, ot.title, SUM(ot.value) AS total, COUNT(o.order_id) AS `orders` FROM `" . DB_PREFIX . "order` o LEFT JOIN `" . DB_PREFIX . "order_total` ot ON (o.order_id = ot.order_id) WHERE ot.code = 'shipping'";

		if (!empty($data['filter_order_status_id'])) {
			$sql .= " AND o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " AND o.order_status_id > '0'";
		}

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		if (!empty($data['filter_group'])) {
			$group = $data['filter_group'];
		} else {
			$group = 'week';
		}

		switch($group) {
			case 'day';
				$sql .= " GROUP BY YEAR(o.date_added), MONTH(o.date_added), DAY(o.date_added), ot.title";
				break;
			default:
			case 'week':
				$sql .= " GROUP BY YEAR(o.date_added), WEEK(o.date_added), ot.title";
				break;
			case 'month':
				$sql .= " GROUP BY YEAR(o.date_added), MONTH(o.date_added), ot.title";
				break;
			case 'year':
				$sql .= " GROUP BY YEAR(o.date_added), ot.title";
				break;
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getTotalShipping($data = array()) {
		if (!empty($data['filter_group'])) {
			$group = $data['filter_group'];
		} else {
			$group = 'week';
		}

		switch($group) {
			case 'day';
				$sql = "SELECT COUNT(DISTINCT YEAR(o.date_added), MONTH(o.date_added), DAY(o.date_added), ot.title) AS total FROM `" . DB_PREFIX . "order` o";
				break;
			default:
			case 'week':
				$sql = "SELECT COUNT(DISTINCT YEAR(o.date_added), WEEK(o.date_added), ot.title) AS total FROM `" . DB_PREFIX . "order` o";
				break;
			case 'month':
				$sql = "SELECT COUNT(DISTINCT YEAR(o.date_added), MONTH(o.date_added), ot.title) AS total FROM `" . DB_PREFIX . "order` o";
				break;
			case 'year':
				$sql = "SELECT COUNT(DISTINCT YEAR(o.date_added), ot.title) AS total FROM `" . DB_PREFIX . "order` o";
				break;
		}

		$sql .= " LEFT JOIN `" . DB_PREFIX . "order_total` ot ON (o.order_id = ot.order_id) WHERE ot.code = 'shipping'";

		if (!empty($data['filter_order_status_id'])) {
			$sql .= " AND order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " AND order_status_id > '0'";
		}

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}
}