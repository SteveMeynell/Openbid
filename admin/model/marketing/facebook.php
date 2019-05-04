<?php
class ModelMarketingFacebook extends Model {


  public function postToPage() {
    try {
      // Returns a `FacebookFacebookResponse` object
      $response = $fb->post(
        '/{your-page-id}/feed',
        array (
          'message' => 'Awesome!'
        ),
        '{access-token}'
      );
    } catch(FacebookExceptionsFacebookResponseException $e) {
      echo 'Graph returned an error: ' . $e->getMessage();
      exit;
    } catch(FacebookExceptionsFacebookSDKException $e) {
      echo 'Facebook SDK returned an error: ' . $e->getMessage();
      exit;
    }
    $graphNode = $response->getGraphNode();
  }

  public function postToTestPage() {
    try {
      // Returns a `FacebookFacebookResponse` object
      $response = $fb->post(
        '/546415602553665/feed',
        array (
          'message' => 'hello world'
        ),
        '{access-token}'
      );
    } catch(FacebookExceptionsFacebookResponseException $e) {
      echo 'Graph returned an error: ' . $e->getMessage();
      exit;
    } catch(FacebookExceptionsFacebookSDKException $e) {
      echo 'Facebook SDK returned an error: ' . $e->getMessage();
      exit;
    }
    $graphNode = $response->getGraphNode();
  }
  // end of model
}