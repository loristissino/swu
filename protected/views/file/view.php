<?php
/* @var $this FileController */
/* @var $model File */

$this->breadcrumbs=array(
  'Files'=>array('index'),
  $model->id,
);

?>


<h1><?php echo $assignment ?></h1>

<?php if($status==1): ?>
<p><?php echo Yii::t('swu', 'Thank you, %name%!', array('%name%'=>$student->firstname)) ?></p>

<?php if($model->original_name): ?>
  <p><?php echo Yii::t('swu', 'You successfully uploaded a file, as required.') ?> <?php echo $this->renderPartial('_tardy', array('model'=>$model)) ?></p>
  <?php echo $this->renderPartial('_info_file', array('model'=>$model)) ?>
<?php else: ?>
  <p><?php echo Yii::t('swu', 'You successfully submitted the information required.') ?> <?php echo $this->renderPartial('_tardy', array('model'=>$model)) ?></p>
  <?php echo $this->renderPartial('_info_url', array('model'=>$model)) ?>
<?php endif ?>

<?php if(!$student->email): ?>
<p><?php echo Yii::t('swu', 'Your email address is not known.') . ' ' . Yii::t('swu', 'If you want to receive information about the work you uploaded, please use the %contactform% to send it in.', array('%contactform%'=>CHtml::link(Yii::t('swu', 'contact form'), array('site/contact', 
  'name'=>$student->firstname . ' ' . $student->lastname,
  'subject'=>Yii::t('swu', 'Email address'),
  'body'=>Yii::t('swu', 'I\'d like to let you know my email address, in order to receive further information (code=%code%).', array('%code%'=>$model->exercise->code))
  ))))
 ?></p>
<?php else: ?>
<p><?php echo Yii::t('swu', 'You will receive further information about the work you uploaded, when it is checked, by email.') ?></p>
<?php endif ?>

<?php else: ?>
<p><?php echo Yii::t('swu', 'Information about the work uploaded / saved by %name%.', array('%name%'=>$student->firstname)) ?></p>

<?php if($model->original_name): ?>
  <?php echo $this->renderPartial('_info_file', array('model'=>$model)) ?>
<?php else: ?>
  <?php echo $this->renderPartial('_info_url', array('model'=>$model)) ?>
<?php endif ?>
<?php endif ?>
<?php if($model->comment && $status==0): ?>
  <?php echo $this->renderPartial('_info_comment', array('model'=>$model)) ?>
<?php endif ?>

<?php if($status!=1 && $model->isTardy()): ?>
<p><?php echo $this->createIcon('tardy', Yii::t('swu', 'Tardy')) ?> <span class="tardy"><?php echo Yii::t('swu', 'This work is tardy.') ?></span></p>
<?php endif ?> 

<?php if(!Yii::app()->user->isGuest): ?>
<hr />
<p>
<?php echo CHtml::link('Edit basic information', array('file/update', 'id'=>$model->id)) ?><br />
<?php echo CHtml::link('Evaluate this work', array('exercise/update', 'id'=>$exercise->id, 'file'=>$model->id, 'version'=>sizeof($exercise->files)-$v)) ?><br />
Assignment: <?php echo CHtml::link($model->exercise->assignment->title, array('assignment/view', 'id'=>$model->exercise->assignment_id)) ?><br />
Student: <?php echo CHtml::link($student, array('student/view', 'id'=>$student->id)) ?>
</p>
<?php endif ?>
