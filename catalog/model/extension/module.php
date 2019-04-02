<?php
class ModelExtensionModule extends Model {
	public function getModule($module_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "module WHERE module_id = '" . (int)$module_id . "'");
		
		if ($query->row) {
			return json_decode($query->row['setting'], true);
		} else {
			return array();	
		}
	}
	
		public function getModulebyName($module_name, $code) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "module WHERE name = '" . $module_name . "' AND code = '" . $code . "'");
		
		if ($query->row) {
			return json_decode($query->row['setting'], true);
		} else {
			return array();	
		}
	}		

	public function isModuleUsed ($code) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "module WHERE code = '" . $code . "'");

		if ($query->row) {
			$status = json_decode($query->row['setting'], true);
			if ($status['status']) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	public function getModuleByCode ($code) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "module WHERE code = '" . $code . "'");

		$results = $query->rows;
		foreach($results as $result) {
			$settings = json_decode($result['setting'], true);
			if ($settings['status'] && $settings['homepage']) {
					return $result;
			} 
		}
		return false;
	}
}