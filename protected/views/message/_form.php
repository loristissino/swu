<?php
/* @var $this MessageController */
/* @var $model Message */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
  'id'=>'message-form',
  'enableAjaxValidation'=>false,
)); ?>

  <p class="note">Fields with <span class="required">*</span> are required.</p>

  <?php echo $form->errorSummary($model); ?>

  <div class="row">
    <?php echo $form->labelEx($model,'id'); ?>
    <?php echo $form->textField($model,'id'); ?>
    <?php echo $form->error($model,'id'); ?>
  </div>

  <div class="row">
    <?php echo $form->labelEx($model,'student_id'); ?>
    <?php echo $form->textField($model,'student_id'); ?>
    <?php echo $form->error($model,'student_id'); ?>
  </div>

  <div class="row">
    <?php echo $form->labelEx($model,'subject'); ?>
    <?php echo $form->textField($model,'subject',array('size'=>60,'maxlength'=>255)); ?>
    <?php echo $form->error($model,'subject'); ?>
  </div>

  <div class="row">
    <?php echo $form->labelEx($model,'body'); ?>
    <?php echo $form->textArea($model,'body',array('rows'=>6, 'cols'=>50)); ?>
    <?php echo $form->error($model,'body'); ?>
  </div>
  
  <div class="row">
    <?php echo $form->labelEx($model,'html'); ?>
    <?php echo $form->textArea($model,'html',array('rows'=>6, 'cols'=>50)); ?>
    <?php echo $form->error($model,'html'); ?>
  </div>

  <div class="row buttons">
    <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
  </div>

<?php $this->endWidget(); ?>

</div><!-- form -->
