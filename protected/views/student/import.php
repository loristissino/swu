<?php
/* @var $this StudentController */
/* @var $model Student */

$this->breadcrumbs=array(
  'Students'=>array('index'),
  'Import',
);

$this->menu=array(
  array('label'=>'Create Student', 'url'=>array('create')),
  array('label'=>'Manage Students', 'url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('swu', 'Import students') ?></h1>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
  'id'=>'import-students-form',
  'enableAjaxValidation'=>false,
)); ?>

  <p class="note"><?php echo Yii::t('swu', 'Fields with <span class="required">*</span> are required.') ?><br />
  <?php echo Yii::t('swu', 'The format for each line is: firstname{tab}lastname{tab}gender{tab}email{tab}roster.') ?></p>

  <?php echo $form->errorSummary($model, Yii::t('swu', 'Please fix the following errors:')); ?>
  
  <div class="row">
    <?php echo $form->labelEx($model,'content'); ?>
    <?php echo $form->textArea($model, 'content', array('rows' => 10, 'cols' => 70)); ?>
    <?php echo $form->error($model,'content'); ?>
  </div>
  
  <div class="row buttons">
    <?php echo CHtml::submitButton(Yii::t('swu', 'Import')) ?>
  </div>

<?php $this->endWidget(); ?>

</div><!-- form -->
