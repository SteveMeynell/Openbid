<?php if (!isset($redirect)) { ?>
<div class="table-responsive">
  <table class="table table-bordered table-hover">
    <thead>
      <tr>
        <td class="text-left"><?php echo $column_name; ?></td>
        <td class="text-right"><?php echo $column_quantity; ?></td>
        <td class="text-right"><?php echo $column_date_added; ?></td>
        <td class="text-right"><?php echo $column_total; ?></td>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($auctions as $auction) { ?>
      <tr>
        <td class="text-left"><a href="<?php echo $auction['href']; ?>"><?php echo $auction['name']; ?></a>
          <?php foreach ($auction['fee_details'] as $fee) { ?>
          <br />
          &nbsp;<small> - <?php echo $fee['description']; ?>: <?php echo $fee['amount']; ?></small>
          <?php } ?>
        </td>
        <td class="text-right"><?php echo $auction['num_fees']; ?></td>
        <td class="text-right"><?php echo $auction['date_added']; ?></td>
        <td class="text-right"><?php echo $auction['total']; ?></td>
      </tr>
      <?php } ?>
    </tbody>
    <tfoot>
      <?php foreach ($totals as $total) { ?>
      <tr>
        <td colspan="4" class="text-right"><strong><?php echo $total['title']; ?>:</strong></td>
        <td class="text-right"><?php echo $total['text']; ?></td>
      </tr>
      <?php } ?>
    </tfoot>
  </table>
</div>
<?php echo $payment; ?>
<?php } else { ?>
<script type="text/javascript"><!--
location = '<?php echo $redirect; ?>';
//--></script>
<?php } ?>
