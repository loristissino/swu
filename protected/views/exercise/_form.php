<?php
/* @var $this ExerciseController */
/* @var $model Exercise */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
  'id'=>'exercise-form',
  'enableAjaxValidation'=>false,
)); ?>

  <?php echo $form->errorSummary($model); ?>

  <?php echo $form->hiddenField($model,'assignment_id'); ?>

  <?php echo $form->hiddenField($model,'student_id'); ?>

  <?php echo $form->hiddenField($model,'code',array('size'=>10,'maxlength'=>10)); ?>

  <?php echo $form->hiddenField($model,'file'); ?>

  <div class="row">
    <?php echo $form->labelEx($model,'status'); ?>
     <?php echo $form->dropDownList(
        $model, 
        'status',
        $model->getPossibleStatuses($model->status)
        )
      ?>
    <?php echo $form->error($model,'status'); ?>
  </div>

  <div class="row">
    <?php echo $form->labelEx($model,'mark'); ?>
    <?php echo $form->textField($model,'mark',array('size'=>30,'maxlength'=>30)); ?>
    <?php echo $form->error($model,'mark'); ?>
  </div>

  <div class="row">
    <?php echo $form->labelEx($model,'comment'); ?>
    <?php echo $form->textArea($model,'comment',array('cols'=>60,'rows'=>10)); ?>
    <?php echo $form->error($model,'comment'); ?>
  </div>

  <div class="row">
    <?php echo $form->labelEx($model,'duedate'); ?>
    <?php echo $form->textField($model,'duedate',array('size'=>19,'maxlength'=>19)); ?>
    <?php echo $form->error($model,'duedate'); ?>
  </div>

  <div class="row checkbox">
    <?php echo $form->labelEx($model,'generate_message'); ?>
    <?php echo $form->checkBox($model,'generate_message'); ?>
  </div>

  <div class="row buttons">
    <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
  </div>

<?php $this->endWidget(); ?>

</div><!-- form -->
