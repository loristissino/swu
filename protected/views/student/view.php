<?php
/* @var $this StudentController */
/* @var $model Student */

$this->breadcrumbs=array(
  'Students'=>array('index'),
  $model->id,
);

$this->menu=array(
  array('label'=>'Create Student', 'url'=>array('create')),
  array('label'=>'Edit Student', 'url'=>array('update', 'id'=>$model->id)),
  array('label'=>'Delete Student', 'visible'=>sizeof($model->exercises)==0, 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
  array('label'=>'Manage Students', 'url'=>array('admin')),
);
?>

<h1><?php echo $model ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
  'data'=>$model,
  'attributes'=>array(
    'id',
    'firstname',
    'lastname',
    'roster',
  ),
)); ?>

<br />
<?php echo $this->renderPartial('_exercises', array('exercises'=>$model->exercises, 'level'=>2)) ?>
