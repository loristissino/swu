<?php
/* @var $this MailtemplateController */
/* @var $model MailTemplate */

$this->breadcrumbs=array(
	'Mail Templates'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List MailTemplates', 'url'=>array('index')),
	array('label'=>'Manage MailTemplates', 'url'=>array('admin')),
);
?>

<h1>Create MailTemplate</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>
