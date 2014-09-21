<?php if(($count=count($queued_messages))>0): ?>
  <p>Queued (confirmed) messages: <?php echo $count ?></p>
    <ul>
    <?php foreach($queued_messages as $message): ?>
      <li>«<?php echo CHtml::link(CHtml::encode($message->subject), array('message/view', 'id'=>$message->id)) ?>»
      (confirmed: <?php echo $message->confirmed_at ?>)
      <?php if($message->confirmed_at > date('Y-m-d H:i:s')): ?> ** scheduled **<?php endif ?>
      </li>
    <?php endforeach ?>
    </ul>
<?php else: ?>
  <p>There are no queued (confirmed) messages.</p>
<?php endif ?>
