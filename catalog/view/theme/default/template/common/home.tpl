<?php
  echo $header; 
  ?>

<div class="container">
  <div class="row">
    <?php 
      echo $column_left;
      if ($column_left && $column_right) {
        $class = 'col-sm-6';
      } elseif ($column_left || $column_right) {
        $class = 'col-sm-9';
      } else {
        $class = 'col-sm-12';
      } 
      ?>
    <div id="content" class="<?php echo $class; ?>">
      <?php 
        echo $content_top; 
        if ($closed_auction_data) {
          echo $closed_auction_data;
        }
        if (isset($jumbotron)) {
          echo $jumbotron;
        } 
        echo $content_bottom; 
        ?>
    </div>
    <?php 
      echo $column_right; 
      ?>
    </div>
  </div>
<?php
  echo $footer; 
  ?>