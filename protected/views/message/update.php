<?php
/* @var $this MessageController */
/* @var $model Message */

$this->breadcrumbs=array(
  'Messages'=>array('index'),
  $model->id=>array('view','id'=>$model->id),
  'Update',
);

$this->menu=array(
  array('label'=>'List Messages', 'url'=>array('index')),
  array('label'=>'View Message', 'url'=>array('view', 'id'=>$model->id)),
  array('label'=>'Manage Messages', 'url'=>array('admin')),
);
?>

<h1>Edit Message <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
