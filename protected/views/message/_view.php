<?php
/* @var $this MessageController */
/* @var $data Message */
?>

<div class="view">

  <b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
  <?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
  <br />

  <b><?php echo CHtml::encode($data->getAttributeLabel('student_id')); ?>:</b>
  <?php echo CHtml::encode($data->student_id); ?>
  <br />

  <b><?php echo CHtml::encode($data->getAttributeLabel('subject')); ?>:</b>
  <span class="preformatted"><?php echo CHtml::encode($data->subject); ?></span>
  <br />

  <b><?php echo CHtml::encode($data->getAttributeLabel('body')); ?>:</b><br />
  <span class="preformatted"><?php echo nl2br(CHtml::encode($data->body)); ?></span>
  <br />

  <b><?php echo CHtml::encode($data->getAttributeLabel('html')); ?>:</b><br />
  <span class="preformatted"><?php echo nl2br(CHtml::encode($data->html)); ?></span>
  <br />

  <b><?php echo CHtml::encode($data->getAttributeLabel('confirmed_at')); ?>:</b>
  <?php echo CHtml::encode($data->confirmed_at); ?>
  <br />

  <b><?php echo CHtml::encode($data->getAttributeLabel('sent_at')); ?>:</b>
  <?php echo CHtml::encode($data->sent_at); ?>
  <br />

  <b><?php echo CHtml::encode($data->getAttributeLabel('acknowledged_at')); ?>:</b>
  <?php echo CHtml::encode($data->getAcknowledgedDescription()); ?>
  <br />

</div>
