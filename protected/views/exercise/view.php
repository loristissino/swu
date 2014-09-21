<?php
/* @var $this ExerciseController */
/* @var $model Exercise */

$this->breadcrumbs=array(
  'Exercises'=>array('index'),
  $model->id,
);

$this->menu=array(
  array('label'=>'Evaluate', 'url'=>array('update', 'id'=>$model->id)),
);
?>

<h1><?php echo Yii::t('swu', 'Exercise «%title%» (%name%)', array('%title%'=>$model->assignment->title, '%name%'=>$model->student->name)) ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
  'data'=>$model,
  'attributes'=>array(
    'id',
    array(
      'label'=>'Assignment',
      'type'=>'raw',
      'value'=>CHtml::link(CHtml::encode($model->assignment->title), array('assignment/view', 'id'=>$model->assignment_id), array('class'=>'hiddenlink')),
    ),
    array(
      'label'=>'Student',
      'type'=>'raw',
      'value'=>CHtml::link(CHtml::encode($model->student->name), array('student/view', 'id'=>$model->student_id), array('class'=>'hiddenlink')),
    ),
    'code',
    'linkedToExercise',
    array(
      'label'=>'Linked Exercises',
      'type'=>'raw',
      'value'=>CHtml::encode($model->getLinkedExercises()),
    ),
    array(
      'label'=>'Status',
      'type'=>'raw',
      'value'=>CHtml::encode($model->getStatusDescription()),
    ),
    'mark',
  ),
)); ?>

<?php $this->widget('zii.widgets.grid.CGridView', array(
  'id'=>'file-grid',
  'dataProvider'=>$files,
  'columns'=>array(
    array(
      'header'=>'Number',
      'type'=>'raw',
      'name'=>'title',
      'value'=>'1+$row',
    ),
    array(
      'header'=>'Content',
      'type'=>'raw',
      'name'=>'content',
      'value'=>array($this, 'renderFileContent'),
    ),
    array(
      'header'=>'Comment',
      'type'=>'raw',
      'name'=>'comment',
      'value'=>'CHtml::encode($data->comment)',
    ),
    'uploaded_at',
    'checked_at',
    array(
      'class'=>'CButtonColumn',
      'template'=>'{view}',
      'viewButtonUrl'=>'Yii::app()->controller->createUrl("file/view",array("id"=>$data->id,"hash"=>$data->md5))',
    ),
  ),
)); ?>

