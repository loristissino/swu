<?php
/* @var $this StudentController */
/* @var $model Student */

$this->breadcrumbs=array(
  'Students',
  'Send codes',
);

?>

<h1><?php echo Yii::t('swu', 'Send Codes') ?></h1>

<?php
/* @var $this StudentController */
/* @var $model Student */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
  'id'=>'sendcodes-form',
  'enableAjaxValidation'=>false,
)); ?>

  <p class="note"><?php echo Yii::t('swu', 'Fields with <span class="required">*</span> are required.') ?></p>

  <?php echo $form->errorSummary($model); ?>

  <div class="row">
    <?php echo $form->labelEx($model,'email'); ?>
    <?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>64)); ?>
    <?php echo $form->error($model,'email'); ?>
  </div>
  
  <?php echo $this->renderPartial('../site/_captcha', array('form'=>$form, 'model'=>$model)); ?>

  <div class="row buttons">
    <?php echo CHtml::submitButton(Yii::t('swu', 'Send me my codes')); ?>
  </div>

<?php $this->endWidget(); ?>

</div><!-- form -->
