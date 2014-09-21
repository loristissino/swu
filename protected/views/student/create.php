<?php
/* @var $this StudentController */
/* @var $model Student */

$this->breadcrumbs=array(
  'Students'=>array('index'),
  'Create',
);

$this->menu=array(
  array('label'=>'Manage Students', 'url'=>array('admin')),
);
?>

<h1>Create Student</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
