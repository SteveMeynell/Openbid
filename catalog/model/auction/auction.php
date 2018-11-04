<?php
class ModelAuctionAuction extends Model {
    
    public function closeOpenAuctions() {
        
        //must check if latest bid exceeds the reserved bid if set and if not then if it is relisted
        // need a cool off period before relist maybe - setting - then reset the start time, end time,
        // decrease the relist count send email informing the seller that it didn't sell and give them the cool
        // off period to edit settings maybe.
        
        // maybe put all closing none winners with no relist option in a limbo state and send emails to the sellers
        // offering relisting before closing for good.
        
        // if latest bid exceeds the reserved bid then we have a winner!  Close the auction, send an email to
        // the seller informing them that the item has sold and with the buyers details.
        // Send the winning bidder and email informing them that they have won and that the seller will contact
        // them with details.
        
        // We take no responsibility for the payment and delivery of items.  We only give the seller the platform
        // to offer the item for sale.
        
        $this->db->query("UPDATE " . DB_PREFIX . "auctions a
        LEFT JOIN " . DB_PREFIX . "auction_details ad1
        ON (a.auction_id = ad1.auction_id)
        SET a.status = '3' 
        WHERE a.status = '2' AND ad1.end_date <= NOW()");
        
        $results = $this->db->countAffected();
        
        if($results) {
            $mail = new Mail();
            $mail->setTo($this->config->get('config_email'));
            $mail->setFrom($this->config->get('config_email'));
            $mail->setSender("Closing Day");
            $mail->setSubject(html_entity_decode("Closing Auctions", ENT_QUOTES, 'UTF-8'));
            $mail->setText(html_entity_decode($results . " Auctions Closed.", ENT_QUOTES, 'UTF-8'));
            $mail->send();
        }
        
        //$this->log->write($results . 'Auctions Closed.');
    }
    
    public function openCreatedAuctions() {
        $this->db->query("UPDATE " . DB_PREFIX . "auctions a
        LEFT JOIN " . DB_PREFIX . "auction_details ad1
        ON (a.auction_id = ad1.auction_id)
        SET a.status = '2' 
        WHERE a.status = '1' AND ad1.start_date < NOW()");
        
        $results = $this->db->countAffected();
        
        if ($results) {
            $mail = new Mail();
            $mail->setTo($this->config->get('config_email'));
            $mail->setFrom($this->config->get('config_email'));
            $mail->setSender("Opening Day");
            $mail->setSubject(html_entity_decode("Opening Auctions", ENT_QUOTES, 'UTF-8'));
            $mail->setText(html_entity_decode($results . " Auctions Opened.", ENT_QUOTES, 'UTF-8'));
            $mail->send();
        }
        
        //$this->log->write($results . ' Auctions Opened.');
    }
    
    
    // End of Model
}