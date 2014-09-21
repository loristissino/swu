<?php
/* @var $this AssignmentController */
/* @var $model Assignment */

$this->breadcrumbs=array(
  'Assignments'=>array('index'),
  $cloned?'Clone':'Create',
);

$this->menu=array(
  array('label'=>'List Assignment', 'url'=>array('index')),
  array('label'=>'Manage Assignment', 'url'=>array('admin')),
);
?>

<h1><?php if($cloned): ?>Clone Assignment «<?php echo $cloned->title ?>»<?php else: ?>Create Assignment<?php endif ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
