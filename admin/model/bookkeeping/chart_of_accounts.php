<?php
class ModelBookkeepingChartOfAccounts extends Model {
    
    public function getAllAccounts() {
        $sql = "SELECT * FROM '" . DB_PREFIX . "chart_of_accounts'
        ORDER BY account_code";
        
        $result = $this->db->query($sql)->rows; // make sql call when tables set up
        
        return $result; 
    }
    
    private function addAccount($data) {
        // Data should include desired account_code(unique), short_code, description, Type(ALCRE)
        // First check if the account exists
        // Second make sure all fields are set
        // then add the account
    }
    
} // End of Model