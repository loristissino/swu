<?php
/* @var $this MessageController */
/* @var $model Message */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
  'action'=>Yii::app()->createUrl($this->route),
  'method'=>'get',
)); ?>

  <div class="row">
    <?php echo $form->label($model,'id'); ?>
    <?php echo $form->textField($model,'id'); ?>
  </div>

  <div class="row">
    <?php echo $form->label($model,'student_id'); ?>
    <?php echo $form->textField($model,'student_id'); ?>
  </div>

  <div class="row">
    <?php echo $form->label($model,'subject'); ?>
    <?php echo $form->textField($model,'subject',array('size'=>60,'maxlength'=>255)); ?>
  </div>

  <div class="row">
    <?php echo $form->label($model,'body'); ?>
    <?php echo $form->textArea($model,'body',array('rows'=>6, 'cols'=>50)); ?>
  </div>

  <div class="row">
    <?php echo $form->label($model,'html'); ?>
    <?php echo $form->textArea($model,'html',array('rows'=>6, 'cols'=>50)); ?>
  </div>
  
  <div class="row">
    <?php echo $form->label($model,'confirmed_at'); ?>
    <?php echo $form->textField($model,'confirmed_at'); ?>
  </div>

  <div class="row">
    <?php echo $form->label($model,'sent_at'); ?>
    <?php echo $form->textField($model,'sent_at'); ?>
  </div>

  <div class="row">
    <?php echo $form->label($model,'acknowledged_at'); ?>
    <?php echo $form->textField($model,'acknowledged_at'); ?>
  </div>
  
  <div class="row buttons">
    <?php echo CHtml::submitButton('Search'); ?>
  </div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->
