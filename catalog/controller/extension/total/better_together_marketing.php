<?php
class ControllerExtensionTotalBetterTogetherMarketing extends Controller {

	public function get_marketing($id) {
     $this->load->model('extension/total/better_together');
     $this->load->language('extension/total/better_together');
     return $this->model_extension_total_better_together->get_discount_info_both($id);
  }
}
