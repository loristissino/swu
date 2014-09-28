<?php
/* @var $this AssignmentController */
/* @var $model Assignment */

$this->breadcrumbs=array(
  'Assignments'=>array('index'),
  $model->title=>array('assignment/view', 'id'=>$model->id),
);

$this->layout = '//layouts/column1';

?>

<h1>Assignment «<?php echo $model->title; ?>»</h1>

<div>
<h2>Backup</h2>
<textarea cols="70" rows="10">
<?php foreach($exercises as $exercise): ?>
<?php foreach($exercise->files as $file): ?>
<?php if($file->original_name): ?>
<?php echo $this->renderPartial('../file/_wget', array('model'=>$file, 'name'=>'hash', 'student'=>$exercise->student)); ?>
<?php endif ?>
<?php endforeach ?>
<?php endforeach ?>
</textarea>
</div>

<div>
<h2>Evaluation</h2>
<textarea cols="70" rows="10">
<?php foreach($exercises as $exercise): ?>
<?php foreach($exercise->files as $file): ?>
<?php echo $this->renderPartial('../file/_wget', array('model'=>$file, 'name'=>'student', 'student'=>$exercise->student)); ?>
<?php endforeach ?>
<?php endforeach ?>
</textarea>
</div>
