<?php
/* @var $this FileController */
/* @var $model File */
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
    <?php echo $form->label($model,'original_name'); ?>
    <?php echo $form->textField($model,'original_name',array('size'=>60,'maxlength'=>255)); ?>
  </div>

  <div class="row">
    <?php echo $form->label($model,'md5'); ?>
    <?php echo $form->textField($model,'md5',array('size'=>32,'maxlength'=>32)); ?>
  </div>

  <div class="row">
    <?php echo $form->label($model,'uploaded_at'); ?>
    <?php echo $form->textField($model,'uploaded_at'); ?>
  </div>

  <div class="row">
    <?php echo $form->label($model,'exercise_id'); ?>
    <?php echo $form->textField($model,'exercise_id'); ?>
  </div>

  <div class="row buttons">
    <?php echo CHtml::submitButton('Search'); ?>
  </div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->