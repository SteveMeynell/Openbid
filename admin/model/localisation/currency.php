<?php
class ModelLocalisationCurrency extends Model {
	public function addCurrency($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "currency SET title = '" . $this->db->escape($data['title']) . "', code = '" . $this->db->escape($data['code']) . "', symbol_left = '" . $this->db->escape($data['symbol_left']) . "', symbol_right = '" . $this->db->escape($data['symbol_right']) . "', decimal_place = '" . $this->db->escape($data['decimal_place']) . "', value = '" . $this->db->escape($data['value']) . "', status = '" . (int)$data['status'] . "', date_modified = NOW()");

		$currency_id = $this->db->getLastId();

		if ($this->config->get('config_currency_auto')) {
			$this->refresh(true);
		}

		$this->cache->delete('currency');
		
		return $currency_id;
	}

	public function editCurrency($currency_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "currency SET title = '" . $this->db->escape($data['title']) . "', code = '" . $this->db->escape($data['code']) . "', symbol_left = '" . $this->db->escape($data['symbol_left']) . "', symbol_right = '" . $this->db->escape($data['symbol_right']) . "', decimal_place = '" . $this->db->escape($data['decimal_place']) . "', value = '" . $this->db->escape($data['value']) . "', status = '" . (int)$data['status'] . "', date_modified = NOW() WHERE currency_id = '" . (int)$currency_id . "'");

		$this->cache->delete('currency');
	}

	public function deleteCurrency($currency_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "currency WHERE currency_id = '" . (int)$currency_id . "'");

		$this->cache->delete('currency');
	}

	public function getCurrency($currency_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "currency WHERE currency_id = '" . (int)$currency_id . "'");

		return $query->row;
	}

	public function getCurrencyByCode($currency) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "currency WHERE code = '" . $this->db->escape($currency) . "'");

		return $query->row;
	}

	public function getCurrencies($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "currency";

			$sort_data = array(
				'title',
				'code',
				'value',
				'date_modified'
			);

			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];
			} else {
				$sql .= " ORDER BY title";
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
		} else {
			$currency_data = $this->cache->get('currency');

			if (!$currency_data) {
				$currency_data = array();

				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "currency ORDER BY title ASC");

				foreach ($query->rows as $result) {
					$currency_data[$result['code']] = array(
						'currency_id'   => $result['currency_id'],
						'title'         => $result['title'],
						'code'          => $result['code'],
						'symbol_left'   => $result['symbol_left'],
						'symbol_right'  => $result['symbol_right'],
						'decimal_place' => $result['decimal_place'],
						'value'         => $result['value'],
						'status'        => $result['status'],
						'date_modified' => $result['date_modified']
					);
				}

				$this->cache->set('currency', $currency_data);
			}

			return $currency_data;
		}
	}

	public function refresh($force = false) {
		$data = array();
		$data[] = $this->config->get('config_currency');

		if ($force) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "currency WHERE code != '" . $this->db->escape($this->config->get('config_currency')) . "'");
		} else {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "currency WHERE code != '" . $this->db->escape($this->config->get('config_currency')) . "' AND date_modified < '" .  $this->db->escape(date('Y-m-d H:i:s', strtotime('-1 day'))) . "'");
		}
		
		

		foreach ($query->rows as $result) {
			$data[] = $result['code'];
		}

		$Rates = [];
		// https://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml

		$XML = simplexml_load_file("https://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml");
		$currentRates = $XML->Cube->Cube->Cube;
		$currate_date = $XML->Cube->Cube['time'];
		
		//debuglog($currentRates);
			foreach ($currentRates as $rate) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "historic_currency SET 
				code = '" . strval($rate['currency']) . "', 
				value = '" . floatval($rate['rate']) . "'");
				if(in_array($rate['currency'], $data)) {
					$currencyCodes[] = strval($rate['currency']);
					$currencyRates[] = floatval($rate['rate']);
				} else {
					// Save as historical records maybe?
					//debuglog("Hmm what could I save here?  ");
					//debuglog($rate);
				}; 
			}
			if(in_array("EUR", $data)){
				$currencyCodes[] = "EUR";
				$currencyRates[] = 1.0;
			}
			
			$Rates = array_combine($currencyCodes, $currencyRates);
			// All these rates are based on the EUR.
			// If the configured currency is the EUR then the currency codes stand as they are.
			// If not then the currency rates are CCRates/ConRate and the EUR if it is in the list is 1/ConRate
			if($this->config->get('config_currency') != "EUR") {
				$baseRate = $Rates[$this->config->get('config_currency')];
				//debuglog($Rates);
				foreach ($Rates as $CCode => $otherRate) {
					$updatedRate[$CCode] = $otherRate/$baseRate;
					$this->db->query("UPDATE " . DB_PREFIX . "currency SET value = '" . (float)$updatedRate[$CCode] . "', date_modified = '" .  $this->db->escape(date('Y-m-d H:i:s')) . "' WHERE code = '" . $this->db->escape($CCode) . "'");
					/*
					$this->db->query("INSERT INTO " . DB_PREFIX . "historic_currency 
					SET value = '" . (float)$updatedRate[$CCode] . "', 
					date_modified = '" .  $this->db->escape(date('Y-m-d H:i:s')) . "'");
					*/
				}
			} else {
				foreach($Rates as $CCode => $otherRate) {
					$this->db->query("UPDATE " . DB_PREFIX . "currency SET value = '" . (float)$otherRate . "', date_modified = '" .  $this->db->escape(date('Y-m-d H:i:s')) . "' WHERE code = '" . $this->db->escape($CCode) . "'");
				}
			}
			$this->cache->delete('currency');
			//debuglog($updatedRate);
			
			
			
		/*
		$curl = curl_init();

		//curl_setopt($curl, CURLOPT_URL, 'http://download.finance.yahoo.com/d/quotes.csv?s=' . implode(',', $data) . '&f=sl1&e=.csv');
		
		curl_setopt($curl, CURLOPT_URL, 'http://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml');
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($curl, CURLOPT_TIMEOUT, 30);

		$content = curl_exec($curl);

		curl_close($curl);
		debuglog($content);

		$lines = explode("\n", trim($content));

		foreach ($lines as $line) {
			$currency = utf8_substr($line, 4, 3);
			$value = utf8_substr($line, 11, 6);

			if ((float)$value) {
				$this->db->query("UPDATE " . DB_PREFIX . "currency SET value = '" . (float)$value . "', date_modified = '" .  $this->db->escape(date('Y-m-d H:i:s')) . "' WHERE code = '" . $this->db->escape($currency) . "'");
			}
		}

		$this->db->query("UPDATE " . DB_PREFIX . "currency SET value = '1.00000', date_modified = '" .  $this->db->escape(date('Y-m-d H:i:s')) . "' WHERE code = '" . $this->db->escape($this->config->get('config_currency')) . "'");

		$this->cache->delete('currency');
		*/
	}

	public function getTotalCurrencies() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "currency");

		return $query->row['total'];
	}
}