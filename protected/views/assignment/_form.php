<?php
/* @var $this AssignmentController */
/* @var $model Assignment */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
  'id'=>'assignment-form',
  'enableAjaxValidation'=>false,
)); ?>

  <p class="note">Fields with <span class="required">*</span> are required.</p>

  <?php echo $form->errorSummary($model); ?>

  <div class="row">
    <?php echo $form->labelEx($model,'subject'); ?>
    <?php echo $form->textField($model,'subject',array('size'=>60,'maxlength'=>64)); ?>
    <?php echo $form->error($model,'subject'); ?>
  </div>

  <div class="row">
    <?php echo $form->labelEx($model,'title'); ?>
    <?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>64)); ?>
    <?php echo $form->error($model,'title'); ?>
  </div>

  <div class="row">
    <?php echo $form->labelEx($model,'description'); ?>
    <?php echo $form->textArea($model,'description',array('rows'=>6, 'cols'=>50)); ?>
    <?php echo $form->error($model,'description'); ?>
  </div>

  <div class="row">
    <?php echo $form->labelEx($model,'weight'); ?>
    <?php echo $form->textField($model,'weight',array('size'=>10,'maxlength'=>5)); ?>
    <?php echo $form->error($model,'weight'); ?>
  </div>

  <div class="row">
    <?php echo $form->labelEx($model,'checklist'); ?>
    <?php echo $form->textArea($model,'checklist',array('rows'=>6, 'cols'=>50)); ?>
    <?php echo $form->error($model,'checklist'); ?>
  </div>

  <div class="row">
    <?php echo $form->labelEx($model,'url'); ?>
    <?php echo $form->textField($model,'url',array('size'=>60,'maxlength'=>128)); ?>
    <?php echo $form->error($model,'url'); ?>
  </div>

  <div class="row">
    <?php echo $form->labelEx($model,'duedate'); ?>
    <?php echo $form->textField($model,'duedate',array('size'=>19,'maxlength'=>19)); ?>
    <?php echo $form->error($model,'duedate'); ?>
  </div>

  <div class="row">
    <?php echo $form->labelEx($model,'grace'); ?>
    <?php echo $form->textField($model,'grace',array('size'=>10,'maxlength'=>5)); ?>
    <?php echo $form->error($model,'grace'); ?>
  </div>

  <div class="row">
    <?php echo $form->labelEx($model,'language'); ?>
    <?php echo $form->textField($model,'language',array('size'=>7,'maxlength'=>7)); ?>
    <?php echo $form->error($model,'language'); ?>
  </div>

  <div class="row">
    <?php echo $form->labelEx($model,'status'); ?>
     <?php echo $form->dropDownList(
        $model, 
        'status',
        $model->getPossibleStatuses()
        )
      ?>
    <?php echo $form->error($model,'status'); ?>
  </div>

  <div class="row checkbox">
    <?php echo $form->label($model, 'notification') ?>
    <?php echo $form->checkBox($model, 'notification') ?>&nbsp;
    <?php echo Yii::t('swu', 'Notify the admin when new works are uploaded.') ?>
  </div>

  <div class="row">
    <?php echo $form->labelEx($model,'shown_since'); ?>
    <?php echo $form->textField($model,'shown_since',array('size'=>19,'maxlength'=>19)); ?>
    <?php echo $form->error($model,'shown_since'); ?>
  </div>

  <div class="row buttons">
    <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
  </div>

<?php $this->endWidget(); ?>

</div><!-- form -->
