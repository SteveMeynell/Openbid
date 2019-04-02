<?php
class ModelBookkeepingAccounting extends Model {
    
    // Assets = Liabilities + Equity
    // Assets - bank account, Accounts Recievable - Debit

    // Liabilities - Bank Loans, Accounts Payable - Credit

    // Equity - Owners 

    // DER increase with debits
    // LER increase with credits

    // journal entries include Date, account names, amounts debited, amounts credited and description
    // Ledger groups all transactions of a particular account together
    


    public function getAllAccounts() {
        $sql = "SELECT * FROM " . DB_PREFIX . "chart_of_accounts
        ORDER BY account_code";
        
        $result = $this->db->query($sql)->rows; // make sql call when tables set up
        
        return $result; 
    }

    public function getAccountDescriptionByAccount($code) {
        $shortCode = $this->db->escape($code);
        $query = $this->db->query("SELECT description FROM " . DB_PREFIX . "chart_of_accounts 
        WHERE account_code = '" . $shortCode . "'");

        return $query->row['description'];
    }

    public function getAccountByShortCode($code) {
        $shortCode = $this->db->escape($code);
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "chart_of_accounts 
        WHERE short_code = '" . $shortCode . "'");

        return $query->row;
    }

    public function getAccountCodeByShortCode($code) {
        $shortCode = $this->db->escape($code);
        $query = $this->db->query("SELECT account_code FROM " . DB_PREFIX . "chart_of_accounts 
        WHERE short_code = '" . $shortCode . "'");

        return strval($query->row['account_code']);
    }

    public function addTransaction($data) {
        $customerId = $this->db->escape($data['customer_id']);
        $auctionId = $this->db->escape($data['auction_id']);
        $description = $this->db->escape($data['description']);
        $amount = $this->db->escape($data['current_total']);
        $glCode = $this->db->escape($data['gl_code']);

        $this->db->query("INSERT INTO " . DB_PREFIX . "customer_transaction 
        SET 
        customer_id = '" . $customerId . "', 
        auction_id = '" . $auctionId . "', 
        gl_code = '" . $glCode . "', 
        description = '" . $description . "', 
        amount = '" . $amount . "', 
        date_added = NOW()");

        return $this->db->getLastId();
    }
    
} // End of Model