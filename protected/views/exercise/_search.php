<?php
/* @var $this ExerciseController */
/* @var $model Exercise */
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
    <?php echo $form->label($model,'assignment_id'); ?>
    <?php echo $form->textField($model,'assignment_id'); ?>
  </div>

  <div class="row">
    <?php echo $form->label($model,'student_id'); ?>
    <?php echo $form->textField($model,'student_id'); ?>
  </div>

  <div class="row">
    <?php echo $form->label($model,'code'); ?>
    <?php echo $form->textField($model,'code',array('size'=>10,'maxlength'=>10)); ?>
  </div>

  <div class="row">
    <?php echo $form->label($model,'mark'); ?>
    <?php echo $form->textField($model,'mark',array('size'=>3,'maxlength'=>3)); ?>
  </div>

  <div class="row buttons">
    <?php echo CHtml::submitButton('Search'); ?>
  </div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->