<?php
/* @var $this ExerciseController */
/* @var $data Exercise */
?>

<div class="view">

  <b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
  <?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
  <br />

  <b><?php echo CHtml::encode($data->getAttributeLabel('assignment_id')); ?>:</b>
  <?php echo CHtml::encode($data->assignment_id); ?>
  <br />

  <b><?php echo CHtml::encode($data->getAttributeLabel('student_id')); ?>:</b>
  <?php echo CHtml::encode($data->student_id); ?>
  <br />

  <b><?php echo CHtml::encode($data->getAttributeLabel('linked_to')); ?>:</b>
  <?php echo CHtml::encode($data->linked_to); ?>
  <br />

  <b><?php echo CHtml::encode($data->getAttributeLabel('code')); ?>:</b>
  <?php echo CHtml::encode($data->code); ?>
  <br />

  <b><?php echo CHtml::encode($data->getAttributeLabel('mark')); ?>:</b>
  <?php echo CHtml::encode($data->mark); ?>
  <br />

  <b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
  <?php echo CHtml::encode($data->getStatusDescription()); ?>
  <br />


</div>
