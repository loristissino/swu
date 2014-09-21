<h1><?php echo CHtml::encode($model->assignment->title) ?></h1>

<p><?php echo Yii::t('swu', 'Exercise to be done by %name% within 
%date%', array('%name%'=>$model->student->firstname, 
'%date%'=>$model->duedate)) ?><?php if($model->duedate < 
date('Y-m-d')): ?> <span class="expired">(<?php echo Yii::t('swu', 'expired') ?>)</span><?php endif ?>.</p>

<div class="description">
<?php if($model->assignment->description): ?>
<p><?php echo nl2br(CHtml::encode($model->assignment->description)) ?></p>
<?php endif ?>
</div>

<?php if($model->assignment->url): ?>
<p><?php echo Yii::t('swu', 'Further information %here%.', array('%here%'=>CHtml::link(Yii::t('swu', 'here'), $model->assignment->url))) ?></p>
<?php endif ?>


<div class="view">

  <b>Upload Code:</b>
  <?php echo CHtml::link(CHtml::encode($model->code), array('file/upload', 'code'=>$model->code)) ?>
  <br />
  
  <b>Current Status:</b>
  <?php echo CHtml::encode($model->getStatusDescription()) ?>
  <br />
</div>

<?php $this->widget('zii.widgets.grid.CGridView', array(
  'id'=>'file-grid',
  'dataProvider'=>$files,
  'columns'=>array(
    array(
      'header'=>'Number',
      'type'=>'raw',
      'name'=>'title',
      'value'=>'1+$row',
    ),
    array(
      'header'=>'Content',
      'type'=>'raw',
      'name'=>'title',
      'value'=>array($this, 'renderFileContent'),
    ),
    array(
      'header'=>'Comment',
      'type'=>'raw',
      'name'=>'comment',
      'value'=>'CHtml::encode($data->comment)',
    ),
    'uploaded_at',
    'checked_at',
    array(
      'class'=>'CButtonColumn',
      'template'=>'{view}',
      'viewButtonUrl'=>'Yii::app()->controller->createUrl("file/view",array("id"=>$data->id,"hash"=>$data->md5))',
    ),
  ),
)); ?>
