<?php
/* @var $this MessageController */
/* @var $model Message */

$this->breadcrumbs=array(
  'Messages'=>array('index'),
  $model->id,
);

$this->menu=array(
  array('label'=>'List Messages', 'url'=>array('index')),
  array('label'=>'Update Message', 'url'=>array('update', 'id'=>$model->id)),
  array('label'=>'Delete Message', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
  array('label'=>'Manage Messages', 'url'=>array('admin')),
);
?>

<h1>View Message #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
  'data'=>$model,
  'attributes'=>array(
    'id',
    array(
      'label'=>'To',
      'type'=>'raw',
      'value'=>CHtml::encode($student . ' <' . $student->email . '> (id: ' . $student->id . ')'),
    ),
    'subject',
    array(
      'label'=>'Body',
      'type'=>'raw',
      'value'=>nl2br(CHtml::encode($model->body)),
    ),
    array(
      'label'=>'Html',
      'type'=>'raw',
      'value'=>nl2br(CHtml::encode($model->html)),
    ),
    'confirmed_at',
    'sent_at',
    array(
      'label'=>'Date Acknowledged',
      'type'=>'raw',
      'value'=>nl2br(CHtml::encode($model->getAcknowledgedDescription())),
    ),
    
  ),
)); ?>
