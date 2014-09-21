<?php
/* @var $this SiteController */
/* @var $model ContactForm */
/* @var $form CActiveForm */

$this->pageTitle=Yii::app()->name . ' - Contact Me';
$this->breadcrumbs=array(
  'Contact',
);
?>

<h1><?php echo Yii::t('swu', 'Contact Me') ?></h1>

<?php if(Yii::app()->user->hasFlash('contact')): ?>

<div class="flash-success">
  <?php echo Yii::app()->user->getFlash('contact'); ?>
</div>

<?php else: ?>

<p>
<?php echo Yii::t('swu', 'If you have educational inquiries or other questions, please fill out the following form to contact me.') ?> 
<?php echo Yii::t('swu', 'Thank you.') ?>
</p>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
  'id'=>'contact-form',
  'enableClientValidation'=>true,
  'clientOptions'=>array(
    'validateOnSubmit'=>true,
  ),
)); ?>

  <p class="note"><?php echo Yii::t('swu', 'Fields with <span class="required">*</span> are required.') ?></p>

  <?php echo $form->errorSummary($model); ?>

  <div class="row">
    <?php echo $form->labelEx($model,'name'); ?>
    <?php echo $form->textField($model,'name'); ?>
    <?php echo $form->error($model,'name'); ?>
  </div>

  <div class="row">
    <?php echo $form->labelEx($model,'email'); ?>
    <?php echo $form->textField($model,'email'); ?>
    <?php echo $form->error($model,'email'); ?>
  </div>

  <div class="row">
    <?php echo $form->labelEx($model,'subject'); ?>
    <?php echo $form->textField($model,'subject',array('size'=>60,'maxlength'=>128)); ?>
    <?php echo $form->error($model,'subject'); ?>
  </div>

  <div class="row">
    <?php echo $form->labelEx($model,'body'); ?>
    <?php echo $form->textArea($model,'body',array('rows'=>6, 'cols'=>50)); ?>
    <?php echo $form->error($model,'body'); ?>
  </div>

  <?php echo $this->renderPartial('../site/_captcha', array('form'=>$form, 'model'=>$model)); ?>

  <div class="row buttons">
    <?php echo CHtml::submitButton(Yii::t('swu', 'Submit')); ?>
  </div>

<?php $this->endWidget(); ?>

</div><!-- form -->

<?php endif; ?>
