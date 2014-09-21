<?php
/* @var $this ExerciseController */
/* @var $model Exercise */

if($model)
{
  $this->breadcrumbs=array(
    $model->assignment->subject,
    'My Assignments',
    );
}
else
{
  $this->breadcrumbs=array(
    'Assignments',
    'Search',
    );
}


?>

<?php if($model): ?>
  <?php echo $this->renderPartial('_details', array('model'=>$model, 'files'=>$files)) ?>
<?php else: ?>

<h1><?php echo Yii::t('swu', 'Information on assignments and exercises') ?></h1>

<div class="form">

  <?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'ExerciseInfoForm',
    'enableAjaxValidation'=>false,
    'method'=>'POST',
    'action'=>$this->createUrl('exercise/info'),
  )); ?>

    <div class="row">
      <?php echo $form->labelEx($exerciseInfoForm, 'code') ?>
      <?php echo $form->textField($exerciseInfoForm, 'code') ?>
      <?php echo $form->error($exerciseInfoForm, 'code') ?>
    </div>

    <?php echo $this->renderPartial('../site/_captcha', array('form'=>$form, 'model'=>$exerciseInfoForm)); ?>
    
    <div class="row buttons">
      <?php echo CHtml::submitButton(Yii::t('swu', 'Search'), array('name'=>null)) ?>
    </div>

  <?php $this->endWidget() ?>


<?php endif ?>

