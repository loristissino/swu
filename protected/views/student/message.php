<?php
/* @var $this StudentController */
/* @var $model Student */

$this->pageTitle=Yii::app()->name . ' - Prepare message';

$this->breadcrumbs=array(
  'Students'=>array('admin'),
  'Prepare message',
);

?>

<h1><?php echo Yii::t('swu', 'Prepare message') ?></h1>

<p><?php echo Yii::t('swu', 'You are preparing a message to be delivered to:') ?><br>
<?php echo implode(', ', $students) ?>
</p>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
  'id'=>'message-form',
  'enableClientValidation'=>false,
)); ?>

  <p class="note"><?php echo Yii::t('swu', 'Fields with <span class="required">*</span> are required.') ?></p>

  <?php echo $form->errorSummary($model); ?>

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

  <div class="row checkbox">
    <?php echo $form->label($model, 'acknowledgement') ?>
    <?php echo $form->checkBox($model, 'acknowledgement') ?>&nbsp;
    <?php echo Yii::t('swu', 'Require explicit acknowledgement of receipt.') ?>
  </div>

  <div class="row checkbox">
    <?php echo $form->label($model, 'confirmed') ?>
    <?php echo $form->checkBox($model, 'confirmed') ?>&nbsp;
    <?php echo Yii::t('swu', 'Confirm message without further ado.') ?>
  </div>

  <div class="row buttons">
    <?php echo CHtml::submitButton(Yii::t('swu', 'Prepare')); ?>
  </div>

<?php $this->endWidget(); ?>

</div><!-- form -->

