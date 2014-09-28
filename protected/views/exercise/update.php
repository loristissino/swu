<?php
/* @var $this ExerciseController */
/* @var $model Exercise */

$this->breadcrumbs=array(
  $model->assignment->title => array('assignment/view', 'id'=>$model->assignment_id),
  $model->student->name=>array('view','id'=>$model->id),
  'Update',
);

$this->menu=array(
  array('label'=>'List Exercise', 'url'=>array('index')),
  array('label'=>'View Exercise', 'url'=>array('view', 'id'=>$model->id)),
  array('label'=>'Manage Exercise', 'url'=>array('admin')),
);
?>

<h1>Evaluate Exercise</h1>
<h2><?php echo $model->student; ?>, <?php echo $model->assignment->title ?></h2>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
