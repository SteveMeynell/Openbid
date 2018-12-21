<?php
class ModelBookkeepingChartOfAccounts extends Model {
    
    public function getAllAccounts() {
        $sql = "SELECT * FROM '" . DB_PREFIX . "chartofaccounts'
        ORDER BY glcode";
        
        $result = $sql; // make sql call when tables set up
        
        return $result; 
    }
    
    private function addAccount($data) {
        // Data should include desired glcode, description, parent glcode
    }
    
} // End of Model