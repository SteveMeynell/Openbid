<?php
class ModelAuctionWishlist extends Model {

  public function getTotalWatching($auction_id) {
    $auctionId = $this->db->escape($auction_id);
    $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_wishlist WHERE auction_id = '" . $auctionId . "'");
    return $query->row['total'];
  }


  // end of model
}