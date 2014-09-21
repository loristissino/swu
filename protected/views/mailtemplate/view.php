<?php
/* @var $this MailtemplateController */
/* @var $model MailTemplate */

$this->breadcrumbs=array(
	'Mail Templates'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List MailTemplates', 'url'=>array('index')),
	array('label'=>'Create MailTemplate', 'url'=>array('create')),
	array('label'=>'Edit MailTemplate', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete MailTemplate', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage MailTemplates', 'url'=>array('admin')),
);
?>

<h1>View MailTemplate #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'code',
		'lang',
		'subject',
    array(
      'label'=>'Body (plaintext)',
      'type'=>'raw',
      'value'=>nl2br(CHtml::encode($model->plaintext_body)),
    ),
    array(
      'label'=>'Body (html)',
      'type'=>'raw',
      'value'=>nl2br(CHtml::encode($model->html_body)),
    ),
	),
)); ?>
