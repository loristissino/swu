<?php if($li): ?><li><?php endif ?>
<?php if($file->hasBeenChecked()): ?>
  Checked: <b><?php echo $file->checked_at ?></b>
<?php else: ?>
  Not yet checked.
<?php endif ?>
<?php if($li): ?></li><?php endif ?>
