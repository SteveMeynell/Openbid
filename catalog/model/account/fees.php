<?php
class ModelAccountFees extends Model {
	public function getMyFees($data = array()) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "fees_charged` WHERE customer_id = '" . (int)$this->customer->getId() . "'";

		$sql .= " GROUP BY auction_id";

		$sort_data = array(
			'date_added',
			'auction_id',
			'amount',
			'description'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY date_added";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
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

	public function getMyTotalFees() {
		/*
		I have to fix this.  What I am looking for is fees that are in the current cart to be displayed as unpaid and historic 
		fees displayed as paid.
		*/
		$query = $this->db->query("
		SELECT COUNT(*) AS total 
		FROM `" . DB_PREFIX . "fees_charged` 
		WHERE customer_id = '" . (int)$this->customer->getId() . "' 
		GROUP BY auction_id");

		if($query->num_rows) {
			return $query->row['total'];
		} else {
			return false;
		}
	}

	public function getTotalAmountByAuction($auction_id) {
		$auctionId = $this->db->escape($auction_id);
		$query = "SELECT SUM(amount) AS total FROM `" . DB_PREFIX . "fees_charged` WHERE auction_id = '" . $auctionId . "'";
		return $this->db->query($query)->row['total'];
	}
	
	public function getTotalAmount() {
		$query = $this->db->query("SELECT SUM(amount) AS total FROM `" . DB_PREFIX . "fees_charged` WHERE customer_id = '" . (int)$this->customer->getId() . "'");

		if ($query->num_rows) {
			return $query->row['total'];
		} else {
			return 0;
		}
	}
}