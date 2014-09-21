<?php
/* @var $this StudentController */
/* @var $model Student */

$this->breadcrumbs=array(
  'Students'=>array('index'),
  $model->id=>array('view','id'=>$model->id),
  'Edit',
);

$this->menu=array(
  array('label'=>'Create Student', 'url'=>array('create')),
  array('label'=>'View Student', 'url'=>array('view', 'id'=>$model->id)),
  array('label'=>'Manage Students', 'url'=>array('admin')),
);
?>

<h1>Edit Student «<?php echo $model; ?>»</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
