<?php
/* @var $this StudentController */
/* @var $model Student */
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
    <?php echo $form->label($model,'firstname'); ?>
    <?php echo $form->textField($model,'firstname',array('size'=>60,'maxlength'=>64)); ?>
  </div>

  <div class="row">
    <?php echo $form->label($model,'lastname'); ?>
    <?php echo $form->textField($model,'lastname',array('size'=>60,'maxlength'=>64)); ?>
  </div>

  <div class="row">
    <?php echo $form->label($model,'gender'); ?>
    <?php echo $form->textField($model,'gender',array('size'=>2,'maxlength'=>1)); ?>
  </div>

  <div class="row">
    <?php echo $form->label($model,'email'); ?>
    <?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>128)); ?>
  </div>

  <div class="row">
    <?php echo $form->label($model,'roster'); ?>
    <?php echo $form->textField($model,'roster',array('size'=>60,'maxlength'=>64)); ?>
  </div>

  <div class="row buttons">
    <?php echo CHtml::submitButton('Search'); ?>
  </div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->
