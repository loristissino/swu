<?php
/* @var $this UploadFormController */
/* @var $model UploadForm */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
  'id'=>'upload-form-_upload-form',
  'enableAjaxValidation'=>false,
  'htmlOptions' => array('enctype' => 'multipart/form-data'),
)); ?>

  <p class="note"><?php echo Yii::t('swu', 'Fields with <span class="required">*</span> are required.') ?>
  <?php echo Yii::t('swu', 'You can upload a file, provide the URL where the file can be retrieved from, or paste the contents in the box.') ?>
  </p>

  <?php echo $form->errorSummary($model); ?>

  <div class="row">
      <?php echo $form->labelEx($model,'code'); ?>
      <?php echo $form->textField($model,'code'); ?>
      <?php echo $form->error($model,'code'); ?>
  </div>

<?php $this->widget('zii.widgets.jui.CJuiTabs', array(
    'tabs'=>array(
        Yii::t('swu', 'File upload')=>$this->renderPartial('_file_upload', array('model'=>$model, 'form'=>$form), true),
        Yii::t('swu', 'URL')=>$this->renderPartial('_url', array('model'=>$model, 'form'=>$form), true),
        Yii::t('swu', 'Direct input')=>$this->renderPartial('_direct_input', array('model'=>$model, 'form'=>$form), true),
    ),
    'options'=>array(
        'collapsible'=>true,
        'selected'=>0,
    ),
    'htmlOptions'=>array(
        'style'=>'width:700px;'
    ),
));
?>
  
  <div class="row">
    <?php echo $form->labelEx($model,'comment'); ?>
    <?php echo $form->textArea($model, 'comment', array('rows' => 10, 'cols' => 70)); ?>
    <?php echo $form->error($model,'comment'); ?>
  </div>

  <?php if(!$model->byteacher): ?>
    <div class="row checkbox">
      <?php echo $form->label($model, 'honour') ?>
      <?php echo $form->checkBox($model, 'honour') ?>&nbsp;
      <?php echo Yii::t('swu', 'I declare on my honour that what I am uploading / turning in here is the result of my own work without external help (unless collaboration with others is explicitly permitted and declared in the comments field above).') ?>
    </div>

    <?php echo $this->renderPartial('../site/_captcha', array('form'=>$form, 'model'=>$model)); ?>
  <?php else: ?>
    <?php echo $form->hiddenField($model, 'honour', array('value'=>'1')) ?>
  <?php endif ?>

  <div class="row buttons">
    <?php echo CHtml::submitButton(Yii::t('swu', 'Upload / Save')); ?>
  </div>

<?php $this->endWidget(); ?>

</div><!-- form -->
