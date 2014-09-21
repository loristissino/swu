<?php
/* @var $this AssignmentController */
/* @var $data Assignment */
?>

<div class="view">

  <b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
  <?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
  <br />

  <b><?php echo CHtml::encode($data->getAttributeLabel('subject')); ?>:</b>
  <?php echo CHtml::encode($data->subject); ?>
  <br />

  <b><?php echo CHtml::encode($data->getAttributeLabel('title')); ?>:</b>
  <?php echo CHtml::encode($data->title); ?>
  <br />

  <b><?php echo CHtml::encode($data->getAttributeLabel('description')); ?>:</b><br />
  <?php echo nl2br(CHtml::encode($data->description)); ?>
  <br />
  
  <b><?php echo CHtml::encode($data->getAttributeLabel('weight')); ?>:</b>
  <?php echo CHtml::encode($data->weight); ?>
  <br />

  <b><?php echo CHtml::encode($data->getAttributeLabel('url')); ?>:</b>
  <?php echo CHtml::encode($data->url); ?>
  <br />

  <b><?php echo CHtml::encode($data->getAttributeLabel('duedate')); ?>:</b>
  <?php echo CHtml::encode($data->duedate); ?>
  <br />

  <b><?php echo CHtml::encode($data->getAttributeLabel('grace')); ?>:</b>
  <?php echo CHtml::encode($data->grace); ?>
  <br />

  <b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
  <?php echo CHtml::encode($data->getStatusDescription()); ?>
  <br />

</div>
