<?php if (sizeof($bt_message['content']) > 0) { ?>
<div class="col-sm-12">
<div class="row">
<div class="col-sm-8">
<div id="better_together_marketing">
<h2><?php echo $bt_message['title']; ?></h2>
<ul>
<?php    foreach ($bt_message['content'] as $message) { ?>
   <li><?php echo $message['data']; ?></li>
<?php    } ?>
</ul>
</div>
<br />
</div>
</div>
</div>
<?php } ?>

