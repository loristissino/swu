<?php
/* @var $this FileController */
/* @var $model File */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
  'id'=>'file-form',
  'enableAjaxValidation'=>false,
)); ?>

  <p class="note">Fields with <span class="required">*</span> are required.</p>

  <?php echo $form->errorSummary($model); ?>

  <div class="row">
    <?php echo $form->labelEx($model,'original_name'); ?>
    <?php echo $form->textField($model,'original_name',array('size'=>60,'maxlength'=>255)); ?>
    <?php echo $form->error($model,'original_name'); ?>
  </div>

  <div class="row">
    <?php echo $form->labelEx($model,'md5'); ?>
    <?php echo $form->textField($model,'md5',array('size'=>32,'maxlength'=>32)); ?>
    <?php echo $form->error($model,'md5'); ?>
  </div>

  <div class="row">
    <?php echo $form->labelEx($model,'uploaded_at'); ?>
    <?php echo $form->textField($model,'uploaded_at'); ?>
    <?php echo $form->error($model,'uploaded_at'); ?>
  </div>

  <div class="row">
    <?php echo $form->labelEx($model,'checked_at'); ?>
    <?php echo $form->textField($model,'checked_at'); ?>
    <?php echo $form->error($model,'checked_at'); ?>
  </div>

  <div class="row">
    <?php echo $form->labelEx($model,'exercise_id'); ?>
    <?php echo $form->textField($model,'exercise_id'); ?>
    <?php echo $form->error($model,'exercise_id'); ?>
  </div>

  <div class="row buttons">
    <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
  </div>

<?php $this->endWidget(); ?>

</div><!-- form -->
