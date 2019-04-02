<?php
namespace Cart;
class Cart {
	private $data = array();

	public function __construct($registry) {
		$this->config = $registry->get('config');
		$this->customer = $registry->get('customer');
		$this->session = $registry->get('session');
		$this->db = $registry->get('db');

		/* A cart is different in this program.  In this program a cart will hold all of a customers outstanding fees.  These fees will not disappear until
		 they have been paid.  Once paid then a transaction number is generated and that will be added to the fee record to indicate it has been paid and
		 also allowing drill down to payments.
		
		 Fees in this case will be handled like products in other carts
		 Fees will have a link to the auction that incurred them.
		 Fees will have a link to the transaction that paid for them.
		 Fees will have a link to the customer that created the auction.
		 Fees will have a code that will link them to the accounting system.
		 Fees will have an amount charged.
		*/

		// Remove all the expired carts with no customer ID
		// I don't think this will ever need to happen.  I think this is for carts by guests and carts that expire
		$this->db->query("DELETE FROM " . DB_PREFIX . "cart WHERE (api_id > '0' OR customer_id = '0') AND date_added < DATE_SUB(NOW(), INTERVAL 1 HOUR)");

		if ($this->customer->getId()) {
			// We want to change the session ID on all the old items in the customers cart
			$this->db->query("UPDATE " . DB_PREFIX . "cart SET session_id = '" . $this->db->escape($this->session->getId()) . "' WHERE api_id = '0' AND customer_id = '" . (int)$this->customer->getId() . "'");

			// Once the customer is logged in we want to update the customers cart
			$cart_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cart WHERE api_id = '0' AND customer_id = '0' AND session_id = '" . $this->db->escape($this->session->getId()) . "'");

			foreach ($cart_query->rows as $cart) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "cart WHERE cart_id = '" . (int)$cart['cart_id'] . "'");

				// The advantage of using $this->add is that it will check if the products already exist and increaser the quantity if necessary.
				// this can be used and instead of checking if the products already exists and the quantity, we can check if the fee is paid or not.
				$this->add($cart['auction_id'], $cart['fee_id'], $cart['amount'], $cart['recurring_id']);
			}
		}
	}

	public function getFees() {
		$auction_data = array();

		$cart_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cart WHERE api_id = '" . (isset($this->session->data['api_id']) ? (int)$this->session->data['api_id'] : 0) . "' AND customer_id = '" . (int)$this->customer->getId() . "' AND session_id = '" . $this->db->escape($this->session->getId()) . "'");

		foreach ($cart_query->rows as $cart) {

			$query = "SELECT *, COUNT(fc.fee_id) AS num_fees, SUM(fc.amount) AS total_fees FROM " . DB_PREFIX . "fees_charged fc 
			LEFT JOIN " . DB_PREFIX . "auctions a ON (fc.auction_id = a.auction_id) 
			LEFT JOIN " . DB_PREFIX . "auction_description ad ON (fc.auction_id = ad.auction_id) 
			LEFT JOIN " . DB_PREFIX . "auction_to_store a2s ON (a2s.auction_id = ad.auction_id) 
			WHERE a2s.store_id = '" . (int)$this->config->get('config_store_id') . "' 
			AND a2s.auction_id = '" . (int)$cart['auction_id'] . "' 
			AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "'";
			//debuglog($query);

			$auction_query = $this->db->query($query);

			if ($auction_query->num_rows && ($cart['amount'] > 0)) {
			
				$recurring = false;

				$auction_data[] = array(
					'cart_id'         => $cart['cart_id'], 
					'auction_id'      => $auction_query->row['auction_id'], 
					'status'					=> $auction_query->row['status'],
					'name'            => $auction_query->row['name'], 
					'image'           => $auction_query->row['main_image'], 
					'num_fees'				=> $auction_query->row['num_fees'], 
					'amount'        	=> $cart['amount'], 
					'total_fees'			=> $auction_query->row['total_fees'], 
					'date_added'			=> $auction_query->row['date_added'], 
					'recurring'       => $recurring
				);
			} else {
				$this->remove($cart['cart_id']);
			}
		}

		return $auction_data;
	}

	public function getFeeDetails($auction_id) {
		$sql = "SELECT fc.amount, fc.date_added, ca.description FROM " . DB_PREFIX . "fees_charged fc 
		LEFT JOIN " . DB_PREFIX . "chart_of_accounts ca ON (fc.fee_code = ca.account_code) 
		WHERE auction_id = '" . $this->db->escape($auction_id) . "'";

		//debuglog($sql);

		$fee_details = $this->db->query($sql)->rows;

		return $fee_details;
	}


	public function add($auction_id, $amount, $recurring_id = 0) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "cart 
		WHERE api_id = '" . (isset($this->session->data['api_id']) ? (int)$this->session->data['api_id'] : 0) . "' 
		AND customer_id = '" . (int)$this->customer->getId() . "' 
		AND session_id = '" . $this->db->escape($this->session->getId()) . "' 
		AND auction_id = '" . (int)$auction_id . "' 
		AND recurring_id = '" . (int)$recurring_id . "'");

		if (!$query->row['total']) {
			$this->db->query("INSERT " . DB_PREFIX . "cart 
			SET 
			api_id = '" . (isset($this->session->data['api_id']) ? (int)$this->session->data['api_id'] : 0) . "', 
			customer_id = '" . (int)$this->customer->getId() . "', 
			session_id = '" . $this->db->escape($this->session->getId()) . "', 
			auction_id = '" . (int)$auction_id . "', 
			recurring_id = '" . (int)$recurring_id . "', 
			amount = '" . (float)$amount . "', 
			date_added = NOW()");
		} else {
			$this->db->query("UPDATE " . DB_PREFIX . "cart 
			SET 
			amount = '" . (float)$amount . " 
			WHERE api_id = '" . (isset($this->session->data['api_id']) ? (int)$this->session->data['api_id'] : 0) . "' 
			AND customer_id = '" . (int)$this->customer->getId() . "' 
			AND session_id = '" . $this->db->escape($this->session->getId()) . "' 
			AND auction_id = '" . (int)$auction_id . "' 
			AND recurring_id = '" . (int)$recurring_id . "'");
		}
	}



	public function update($cart_id, $amount) {
		$this->db->query("UPDATE " . DB_PREFIX . "cart 
		SET amount = '" . (int)$amount . "' 
		WHERE cart_id = '" . (int)$cart_id . "' 
		AND api_id = '" . (isset($this->session->data['api_id']) ? (int)$this->session->data['api_id'] : 0) . "' 
		AND customer_id = '" . (int)$this->customer->getId() . "' 
		AND session_id = '" . $this->db->escape($this->session->getId()) . "'");
	}

	public function remove($cart_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "cart WHERE cart_id = '" . (int)$cart_id . "' AND api_id = '" . (isset($this->session->data['api_id']) ? (int)$this->session->data['api_id'] : 0) . "' AND customer_id = '" . (int)$this->customer->getId() . "' AND session_id = '" . $this->db->escape($this->session->getId()) . "'");
	}

	public function clear() {
		$this->db->query("DELETE FROM " . DB_PREFIX . "cart WHERE api_id = '" . (isset($this->session->data['api_id']) ? (int)$this->session->data['api_id'] : 0) . "' AND customer_id = '" . (int)$this->customer->getId() . "' AND session_id = '" . $this->db->escape($this->session->getId()) . "'");
	}

	/*public function getRecurringProducts() {
		$product_data = array();

		foreach ($this->getProducts() as $value) {
			if ($value['recurring']) {
				$product_data[] = $value;
			}
		}

		return $product_data;
	}

	public function getWeight() {
		$weight = 0;

		foreach ($this->getProducts() as $product) {
			if ($product['shipping']) {
				$weight += $this->weight->convert($product['weight'], $product['weight_class_id'], $this->config->get('config_weight_class_id'));
			}
		}

		return $weight;
	}*/

	public function getSubTotal() {
		$total = 0;

		foreach ($this->getFees() as $product) {
			$total += $product['total'];
		}

		return $total;
	}

/*	public function getTaxes() {
		$tax_data = array();

		foreach ($this->getProducts() as $product) {
			if ($product['tax_class_id']) {
				$tax_rates = $this->tax->getRates($product['price'], $product['tax_class_id']);

				foreach ($tax_rates as $tax_rate) {
					if (!isset($tax_data[$tax_rate['tax_rate_id']])) {
						$tax_data[$tax_rate['tax_rate_id']] = ($tax_rate['amount'] * $product['quantity']);
					} else {
						$tax_data[$tax_rate['tax_rate_id']] += ($tax_rate['amount'] * $product['quantity']);
					}
				}
			}
		}

		return $tax_data;
	}
	*/

	public function getTotal() {
		$total = 0;

		foreach ($this->getFees() as $auctions) {
			$total += $auctions['total_fees'];
		}

		return $total;
	}

	public function countFees() {
		$fee_total = 0;

		$fees = $this->getFees();

		foreach ($fees as $fee) {
			$fee_total += $fee['num_fees'];
		}

		return $fee_total;
	}

	public function hasFees() {
		return count($this->getFees());
	}
/*
	public function hasRecurringProducts() {
		return count($this->getRecurringProducts());
	}

	public function hasStock() {
		foreach ($this->getFees() as $product) {
			if (!$product['stock']) {
				return false;
			}
		}

		return true;
	}

	public function hasShipping() {
		foreach ($this->getFees() as $product) {
			if ($product['shipping']) {
				return true;
			}
		}

		return false;
	}

	public function hasDownload() {
		foreach ($this->getFees() as $product) {
			if ($product['download']) {
				return true;
			}
		}

		return false;
	}
*/
}
