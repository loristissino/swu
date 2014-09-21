<?php
/* @var $this AssignmentController */
/* @var $model Assignment */

$this->breadcrumbs=array(
  'Assignments'=>array('index'),
  $model->title=>array('view','id'=>$model->id),
  'Update',
);

$this->menu=array(
  array('label'=>'List Assignment', 'url'=>array('index')),
  array('label'=>'Create Assignment', 'url'=>array('create')),
  array('label'=>'View Assignment', 'url'=>array('view', 'id'=>$model->id)),
  array('label'=>'Manage Assignment', 'url'=>array('admin')),
);
?>

<h1>Edit Assignment <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
