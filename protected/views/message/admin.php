<?php
/* @var $this MessageController */
/* @var $model Message */

$this->breadcrumbs=array(
  'Messages'=>array('index'),
  'Manage',
);

/*
$this->menu=array(
  array('label'=>'Send Confirmed', 'url'=>array('send')),
);
*/

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
  $('.search-form').toggle();
  return false;
});
$('.search-form form').submit(function(){
  $('#message-grid').yiiGridView('update', {
    data: $(this).serialize()
  });
  return false;
});
");
?>

<h1>Manage Messages</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?> - 
<?php if(Yii::app()->user->getState('listall')=='true'): ?>
  <?php echo CHtml::link('Show only unsent', 
  $url=CHtml::normalizeUrl(array('message/toggle', 
  'all'=>'false')), array('submit'=>$url, 'title'=>'Show only 
  unsent messages')); ?>
<?php else: ?>
  <?php echo CHtml::link('Show all messages', 
  $url = CHtml::normalizeUrl(array('message/toggle', 'all'=>'true')), 
  array('submit'=>$url, 'title'=>'Show sent and unsent messages')); 
  ?>
<?php endif ?>

<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
  'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php

echo CHtml::beginForm('','post',array('id'=>'message-form'));

$this->widget('zii.widgets.grid.CGridView', array(
  'id'=>'message-grid',
  'dataProvider'=>$model->search(),
  'selectableRows'=>2, // multiple rows can be selected
  
  'dataProvider'=>$model->search(),
  'filter'=>$model,
  'columns'=>array(
    'id',
    'student',
    'subject',
    array(
      'name'=>'body',
      'header'=>'Body (plaintext)',
      'type'=>'raw',
      'value'=>array($this, 'RenderBody'),
      ),
    array(
      'name'=>'html',
      'header'=>'Body (html)',
      'type'=>'raw',
      'value'=>array($this, 'RenderHtml'),
      ),
    array(
      'name'=>'confirmed_at',
      'header'=>'Confirmed',
    ),
    array(
      'name'=>'sent_at',
      'header'=>'Sent',
    ),
    array(
      'name'=>'seen_at',
      'header'=>'Seen',
    ),
    array(
      'name'=>'acknowledged_at',
      'header'=>'Acknowledged',
      'type'=>'raw',
      'value'=>'$data->getAcknowledgedDescription()',
    ),
    array(
      'class'=>'CButtonColumn',
    ),
    array(
      'class'=>'CCheckBoxColumn',
      'id'=>'id',
      'value'=>'$data->id',
    ),
  ),
));

echo CHtml::endForm(); ?>

<p>
<?php echo 'With the selected messages, do the following:' ?>
<?php $this->widget('ext.widgets.bmenu.XBatchMenu', array(
    'formId'=>'message-form',
    'checkBoxId'=>'id',
//    'ajaxUpdate'=>'person-grid', // if you want to update grid by ajax
    'emptyText'=>'Please select the messages you would like to perform this action on!',
    'items'=>array(
        array('label'=>'confirm','url'=>array('message/confirm')),
    ),
    'htmlOptions'=>array('class'=>'actionBar'),
    'containerTag'=>'span',
));
?></p>

<hr />
<p><?php echo CHtml::link('Send confirmed messages', array('send')) ?></p>
