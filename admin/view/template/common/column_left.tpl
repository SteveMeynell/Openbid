<nav id="column-left">
  <div id="profile">
    <div>
      <?php if ($image) { ?>
      <img src="<?php echo $image; ?>" alt="<?php echo $firstname; ?> <?php echo $lastname; ?>" title="<?php echo $username; ?>" class="img-circle" />
      <?php } else { ?>
      <i class="fa fa-opencart"></i>
      <?php } ?>
    </div>
    <div>
      <h4><?php echo $firstname; ?> <?php echo $lastname; ?></h4>
      <small><?php echo $user_group; ?></small></div>
  </div>
  <ul id="menu">
    <?php foreach ($menus as $menu) { ?>
    <li id="<?php echo $menu['id']; ?>">
      <?php if ($menu['href']) { ?>
      <a href="<?php echo $menu['href']; ?>"><i class="fa <?php echo $menu['icon']; ?> fw"></i> <span><?php echo $menu['name']; ?></span></a>
      <?php } else { ?>
      <a class="parent"><i class="fa <?php echo $menu['icon']; ?> fw"></i> <span><?php echo $menu['name']; ?></span></a>
      <?php } ?>
      <?php if ($menu['children']) { ?>
      <ul>
        <?php foreach ($menu['children'] as $children_1) { ?>
        <li>
          <?php if ($children_1['href']) { ?>
          <a href="<?php echo $children_1['href']; ?>"><?php echo $children_1['name']; ?></a>
          <?php } else { ?>
          <a class="parent"><?php echo $children_1['name']; ?></a>
          <?php } ?>
          <?php if ($children_1['children']) { ?>
          <ul>
            <?php foreach ($children_1['children'] as $children_2) { ?>
            <li>
              <?php if ($children_2['href']) { ?>
              <a href="<?php echo $children_2['href']; ?>"><?php echo $children_2['name']; ?></a>
              <?php } else { ?>
              <a class="parent"><?php echo $children_2['name']; ?></a>
              <?php } ?>
              <?php if ($children_2['children']) { ?>
              <ul>
                <?php foreach ($children_2['children'] as $children_3) { ?>
                <li><a href="<?php echo $children_3['href']; ?>"><?php echo $children_3['name']; ?></a></li>
                <?php } ?>
              </ul>
              <?php } ?>
            </li>
            <?php } ?>
          </ul>
          <?php } ?>
        </li>
        <?php } ?>
      </ul>
      <?php } ?>
    </li>
    <?php } ?>
  </ul>
  <div id="stats">
    <ul>
      <li>
        <div><?php echo $text_auctions_opening_soon; ?> <span class="pull-right"><?php echo ($created_auction_status)?round(($opening_one_day/$created_auction_status)*100):0; ?>%</span></div>
        <div class="progress">
          <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?php echo ($created_auction_status)?round(($opening_one_day/$created_auction_status)*100):0; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($created_auction_status)?round(($opening_one_day/$created_auction_status)*100):0; ?>%"> <span class="sr-only"><?php echo $opening_one_day; ?>%</span></div>
        </div>
      </li>
      <li>
        <div><?php echo $text_created_auction_status; ?> <span class="pull-right"><?php echo ($status_total)?round(($created_auction_status/$status_total)*100):0; ?>%</span></div>
        <div class="progress">
          <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?php echo ($status_total)?round(($created_auction_status/$status_total)*100):0; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($status_total)?round(($created_auction_status/$status_total)*100):0; ?>%"> <span class="sr-only"><?php echo $closed_auction_status; ?>%</span></div>
        </div>
      </li>
      <li>
        <div><?php echo $text_closed_auction_status; ?> <span class="pull-right"><?php echo ($status_total)?round(($closed_auction_status/$status_total)*100):0; ?>%</span></div>
        <div class="progress">
          <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?php echo ($status_total)?round(($closed_auction_status/$status_total)*100):0; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($status_total)?round(($closed_auction_status/$status_total)*100):0; ?>%"> <span class="sr-only"><?php echo $closed_auction_status; ?>%</span></div>
        </div>
      </li>
      <li>
        <div><?php echo $text_open_auction_status; ?> <span class="pull-right"><?php echo ($status_total)?round(($open_auction_status/$status_total)*100):0; ?>%</span></div>
        <div class="progress">
          <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="<?php echo ($status_total)?round(($open_auction_status/$status_total)*100):0; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($status_total)?round(($open_auction_status/$status_total)*100):0; ?>%"> <span class="sr-only"><?php echo $open_auction_status; ?>%</span></div>
        </div>
      </li>
      <li>
        <div><?php echo $text_suspend_status; ?> <span class="pull-right"><?php echo ($status_total)?round(($suspend_status/$status_total)*100):0; ?>%</span></div>
        <div class="progress">
          <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="<?php echo ($status_total)?round(($suspend_status/$status_total)*100):0; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($status_total)?round(($suspend_status/$status_total)*100):0; ?>%"> <span class="sr-only"><?php echo $suspend_status; ?>%</span></div>
        </div>
      </li>
      <li>
        <div><?php echo $text_moderation_status; ?> <span class="pull-right"><?php echo ($status_total)?round(($moderation_status/$status_total)*100):0; ?>%</span></div>
        <div class="progress">
          <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="<?php echo ($status_total)?round(($moderation_status/$status_total)*100):0; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($status_total)?round(($moderation_status/$status_total)*100):0; ?>%"> <span class="sr-only"><?php echo $moderation_status; ?>%</span></div>
        </div>
      </li>
    </ul>
  </div>
</nav>
