<?php
/* @var $this UtilitiesController */
/* @var $model PasswordForm */
/* @var $form CActiveForm */
/* @var $this UtilitiesController */

$this->breadcrumbs=array(
  'Utilities'=>array('utilities/index'),
  'Password Management',
);

?>

<h1>Password Management</h1>


<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
  'id'=>'password-form-password-form',
  // Please note: When you enable ajax validation, make sure the corresponding
  // controller action is handling ajax validation correctly.
  // See class documentation of CActiveForm for details on this,
  // you need to use the performAjaxValidation()-method described there.
  'enableAjaxValidation'=>false,
)); ?>

  <p class="note">Fields with <span class="required">*</span> are required.</p>

  <?php echo $form->errorSummary($model); ?>

  <div class="row">
    <?php echo $form->labelEx($model,'cost'); ?>
    <?php echo $form->textField($model,'cost',array('size'=>10,'maxlength'=>3)); ?>
    <?php echo $form->error($model,'cost'); ?>
  </div>
  
  <div class="row">
    <?php echo $form->labelEx($model,'password'); ?>
    <?php echo $form->passwordField($model,'password',array('size'=>30,'maxlength'=>30)); ?>
    <?php echo $form->error($model,'password'); ?>
  </div>

  <div class="row buttons">
    <?php echo CHtml::submitButton('Encrypt'); ?>
  </div>

<?php $this->endWidget(); ?>

</div><!-- form -->
