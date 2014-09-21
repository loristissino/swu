<?php
/* @var $this StudentController */
/* @var $model Student */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
  'id'=>'student-form',
  'enableAjaxValidation'=>false,
)); ?>

  <p class="note">Fields with <span class="required">*</span> are required.</p>

  <?php echo $form->errorSummary($model); ?>

  <div class="row">
    <?php echo $form->labelEx($model,'firstname'); ?>
    <?php echo $form->textField($model,'firstname',array('size'=>60,'maxlength'=>64)); ?>
    <?php echo $form->error($model,'firstname'); ?>
  </div>

  <div class="row">
    <?php echo $form->labelEx($model,'lastname'); ?>
    <?php echo $form->textField($model,'lastname',array('size'=>60,'maxlength'=>64)); ?>
    <?php echo $form->error($model,'lastname'); ?>
  </div>

  <div class="row">
    <?php echo $form->labelEx($model,'gender'); ?>
     <?php echo $form->dropDownList(
        $model, 
        'gender',
        $model->getPossibleGenders()
        )
      ?>
    <?php echo $form->error($model,'status'); ?>
  </div>

  <div class="row">
    <?php echo $form->labelEx($model,'email'); ?>
    <?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>128)); ?>
    <?php echo $form->error($model,'email'); ?>
  </div>

  <div class="row">
    <?php echo $form->labelEx($model,'roster'); ?>
    <?php echo $form->textField($model,'roster',array('size'=>60,'maxlength'=>64)); ?>
    <?php echo $form->error($model,'roster'); ?>
  </div>

  <div class="row buttons">
    <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
  </div>

<?php $this->endWidget(); ?>

</div><!-- form -->
