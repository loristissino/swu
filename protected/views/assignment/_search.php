<?php
/* @var $this AssignmentController */
/* @var $model Assignment */
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
    <?php echo $form->label($model,'subject'); ?>
    <?php echo $form->textField($model,'subject',array('size'=>60,'maxlength'=>64)); ?>
  </div>

  <div class="row">
    <?php echo $form->label($model,'title'); ?>
    <?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>64)); ?>
  </div>

  <div class="row">
    <?php echo $form->label($model,'description'); ?>
    <?php echo $form->textArea($model,'description',array('rows'=>6, 'cols'=>50)); ?>
  </div>

  <div class="row">
    <?php echo $form->label($model,'weight'); ?>
    <?php echo $form->textField($model,'weight',array('size'=>10,'maxlength'=>10)); ?>
  </div>

  <div class="row">
    <?php echo $form->label($model,'grace'); ?>
    <?php echo $form->textField($model,'grace',array('size'=>10,'maxlength'=>10)); ?>
  </div>

  <div class="row">
    <?php echo $form->label($model,'url'); ?>
    <?php echo $form->textField($model,'url',array('size'=>60,'maxlength'=>128)); ?>
  </div>

  <div class="row">
    <?php echo $form->label($model,'duedate'); ?>
    <?php echo $form->textField($model,'duedate'); ?>
  </div>

  <div class="row">
    <?php echo $form->label($model,'shown_since'); ?>
    <?php echo $form->textField($model,'shown_since'); ?>
  </div>

  <div class="row buttons">
    <?php echo CHtml::submitButton('Search'); ?>
  </div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->
