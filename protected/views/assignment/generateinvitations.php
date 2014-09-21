<?php
/* @var $this AssignmentController */
/* @var $model Assignment */

$this->breadcrumbs=array(
  'Assignments'=>array('index'),
  $model->title => array('view', 'id'=>$model->id),
  'Invitations',
);

$this->menu=array(
  array('label'=>'List Assignment', 'url'=>array('index')),
  array('label'=>'Create Assignment', 'url'=>array('create')),
  array('label'=>'Update Assignment', 'url'=>array('update', 'id'=>$model->id)),
  array('label'=>'Delete Assignment', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
  array('label'=>'Manage Assignment', 'url'=>array('admin')),
);
?>

<h1>Generate invitations</h1>

<form method="post">
<?php echo CHtml::submitButton('Generate') ?>
</form>

