<?php
class ModelAccountBid extends Model {

  public function getTotalBids(){

    $bidTotals = array(
      'currentTotal'  =>  '0',
      'historyTotal'  =>  '0'
    );
    $currentQuery = "SELECT COUNT(*) AS current FROM " . DB_PREFIX . "current_bids WHERE bidder_id = '" . (int)$this->customer->getId() . "'";
    $historyQuery = "SELECT COUNT(*) AS history FROM " . DB_PREFIX . "bid_history WHERE bidder_id = '" . (int)$this->customer->getId() . "'";

    $bidTotals['currentTotal'] = $this->db->query($currentQuery)->row['current'];
    $bidTotals['historyTotal'] = $this->db->query($historyQuery)->row['history'];

		return $bidTotals;
  }

  public function getWinningBids(){
    $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "bid_history WHERE bidder_id = '" . (int)$this->customer->getId() . "' AND winner = '1'");
    return $query->row['total'];
  }

  public function getTopBid($auction_id) {
    $auctionId = $this->db->escape($auction_id);
    $query = $this->db->query("SELECT MAX(bid_amount) AS bid FROM " . DB_PREFIX . "bid_history bh 
    WHERE auction_id = '" . $auctionId . "' 
    UNION 
    SELECT MAX(bid_amount) as bid FROM " . DB_PREFIX . "current_bids cb 
    WHERE auction_id = '" . $auctionId . "' 
    ORDER BY bid DESC");

    return $query->row['bid'];
  }

  public function getBids(){
    
    $bids = array(
      'history'   => array(),
      'current'   => array()
    );
    $winnersQuery = "SELECT 
    bh.auction_id, 
    bh.bidder_id, 
    bh.bid_amount, 
    ad.title, 
    c.firstname 
    FROM " . DB_PREFIX . "bid_history bh 
    JOIN " . DB_PREFIX . "auction_details ad ON (bh.auction_id = ad.auction_id) 
    JOIN " . DB_PREFIX . "customer c ON (bh.bidder_id = c.customer_id) 
    WHERE bh.winner = '1'";

    $allWinners = $this->db->query($winnersQuery)->rows;
    //debuglog("All Winners:");
    //debuglog($allWinners);

    $allMyHistoryBids = "SELECT DISTINCT auction_id FROM " . DB_PREFIX . "bid_history WHERE bidder_id = '" . (int)$this->customer->getId() . "'";
    //debuglog($allMyHistoryBids);
    $auctionsIveBidOn = $this->db->query($allMyHistoryBids)->rows;

    //debuglog($auctionsIveBidOn);
    foreach($auctionsIveBidOn as $auctions) {
      $historyQuery = "SELECT 
      ad.title, 
      a.status, 
      bh.bid_id, 
      bh.auction_id, 
      bh.bidder_id, 
      bh.bid_date, 
      bh.bid_amount, 
      bh.quantity, 
      bh.proxy_bidder_id, 
      bh.proxy_bid_amount, 
      bh.winner, 
      bh.relist 
      FROM " . DB_PREFIX . "auction_details ad 
      JOIN " . DB_PREFIX . "auctions a ON (ad.auction_id = a.auction_id) 
      JOIN " . DB_PREFIX . "bid_history bh ON (ad.auction_id = bh.auction_id)
      WHERE bh.auction_id = '" . $auctions['auction_id'] . "' 
      ORDER BY bh.auction_id, bh.bid_date, bh.relist";
      //debuglog($query);
      $auction_counter = array_push($bids['history'],$this->db->query($historyQuery)->rows);
     
    }
    
    

    $currentQuery = "SELECT 
    ad.title, 
    a.status, 
    cb.bid_id, 
    cb.auction_id, 
    cb.bidder_id, 
    cb.bid_date, 
    cb.bid_amount, 
    cb.quantity, 
    cb.proxy_bidder_id, 
    cb.proxy_bid_amount, 
    cb.winner 
    FROM " . DB_PREFIX . "auction_details ad 
    JOIN " . DB_PREFIX . "auctions a ON (ad.auction_id = a.auction_id) 
    JOIN " . DB_PREFIX . "current_bids cb ON (ad.auction_id = cb.auction_id)
    WHERE bidder_id = '" . (int)$this->customer->getId() . "' 
    ORDER BY cb.auction_id, cb.bid_date";

    //debuglog($winnersQuery);
    //debuglog($historyQuery);
    //debuglog($currentQuery);
    //$bids = $this->db->query($query);
    array_push($bids['current'],$this->db->query($currentQuery)->rows);

    //debuglog($bids);
    return $bids;
  }

  // end of model
}