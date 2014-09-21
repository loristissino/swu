<?php
/* @var $this MailtemplateController */
/* @var $model MailTemplate */

$this->breadcrumbs=array(
	'Mail Templates'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List MailTemplates', 'url'=>array('index')),
	array('label'=>'Create MailTemplate', 'url'=>array('create')),
	array('label'=>'View MailTemplate', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage MailTemplates', 'url'=>array('admin')),
);
?>

<h1>Edit MailTemplate <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>
