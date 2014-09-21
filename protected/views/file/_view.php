<?php
/* @var $this FileController */
/* @var $data File */
?>

<div class="view">

  <b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
  <?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
  <br />

  <b><?php echo CHtml::encode($data->getAttributeLabel('original_name')); ?>:</b>
  <?php echo CHtml::encode($data->original_name); ?>
  <br />

  <b><?php echo CHtml::encode($data->getAttributeLabel('md5')); ?>:</b>
  <?php echo CHtml::encode($data->md5); ?>
  <br />

  <b><?php echo CHtml::encode($data->getAttributeLabel('uploaded_at')); ?>:</b>
  <?php echo CHtml::encode($data->uploaded_at); ?>
  <br />

  <b><?php echo CHtml::encode($data->getAttributeLabel('exercise_id')); ?>:</b>
  <?php echo CHtml::encode($data->exercise_id); ?>
  <br />


</div>