<?php
class ModelAuctionBidding extends Model {
    
    
    
    public function placeBid($bid) {
        debuglog("got to the place");
        $sql = "SELECT * FROM '" . DB_PREFIX . "current_bids'
        WHERE auction_id = '" . $this->db->excape($bid['auction_id']) . "'
        ORDER BY bid_date ASC";
        
        $query = $this->db->query($sql);
        $result = $query->row;
        
        if(!$result) {
            $this->db->query("INSERT INTO '" . DB_PREFIX . "current_bids'
            SET
            auction_id = '" . $this->db->escape($bid['auction_id']) . "',
            bidder_id = '" . $this->db->escape($bid['bidder_id']) . "',
            bid_date = " . NOW() . ",
            bid_amount = '" . $this->db->escape($bid['bid_amount']) . "',
            quantity = '1',
            proxy_bidder_id = '" . $this->db->escape($bid['bidder_id']) . "',
            proxy_bid_amount = '" . $this->db->escape($bid['bid_amount']) . "'");
            
            $result = $this->db->getLastId();
        }
        
        return $result;
    }
    
    public function getCurrentBid($data) {
        $sql = "SELECT * FROM " . DB_PREFIX . "current_bids 
                         WHERE auction_id = '" . $data . "'
                         ORDER BY bid_id DESC";
        $query = $this->db->query($sql);
        $results = $query->row;
        if (isset($results['bid_amount'])) {
            return $results;
        } else {
            return array('bid_amount'=>'0.00');
        }
    }
    
    
} // End of Model