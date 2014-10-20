<?php
/* @var $this AssignmentController */
/* @var $model Assignment */

$this->breadcrumbs=array(
  'Assignments'=>array('index'),
  $model->title,
);

$this->menu=array(
  array('label'=>'Clone Assignment', 'url'=>array('create', 'id'=>$model->id)),
  array('label'=>'Edit Assignment', 'url'=>array('update', 'id'=>$model->id)),
  array('label'=>'Delete Assignment', 'url'=>'#', 'visible'=>sizeof($model->exercises)==0, 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
  array('label'=>'Generate Invitations', 'url'=>array('assignment/generateinvitations', 'id'=>$model->id), 'visible'=>sizeof($model->exercises)>0),
  array('label'=>'Print Codes', 'url'=>array('assignment/codes', 'id'=>$model->id), 'visible'=>sizeof($model->exercises)>0, 'linkOptions'=>array('target'=>'_blank')),
  array('label'=>'Find Students', 'url'=>array('student/admin', 'assignment'=>$model->id), 'visible'=>sizeof($model->exercises)>0),
  array('label'=>'Download Files', 'url'=>array('assignment/files', 'id'=>$model->id), 'visible'=>sizeof($model->exercises)>0),
);
?>

<h1>Assignment «<?php echo $model->title; ?>»</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
  'data'=>$model,
  'attributes'=>array(
    'id',
    'subject',
    'title',
    'description',
    'url',
    'duedate',
    'weight',
    'grace',
    'language',
    array(
      'label'=>'Status',
      'type'=>'raw',
      'value'=>CHtml::encode($model->getStatusDescription()),
    ),
    array(
      'label'=>'Notification',
      'type'=>'raw',
      'value'=>$model->notification ? 'yes': 'no',
    ),
    'shown_since',
  ),
)); ?>

<?php $this->widget('zii.widgets.grid.CGridView', array(
  'id'=>'exercise-grid',
  'summaryText'=>'',
  'dataProvider'=>new CActiveDataProvider('Exercise', array(
    'data'=>$model->getExercisesWithStudents(),
    )),
//  'filter'=>$model,
  'columns'=>array(
    array(
      'header'=>'Student',
      'type'=>'raw',
      'name'=>'title',
      'value'=>array($this, 'RenderStudent'),
    ),
//      'code',
    array(
      'header'=>'Linked To',
      'type'=>'raw',
      'value'=>'$data->getLinkedToStudentName()',
    ),
    array(
      'header'=>'Status',
      'type'=>'raw',
      'value'=>'$data->getStatusDescription(true)',
      'htmlOptions'=>array('style'=>'text-align: center'),
    ),
    array(
      'header'=>'Due Date',
      'value'=>'$data->duedate',
      'htmlOptions'=>array('style'=>'text-align: center'),
    ),
    'mark',
    array(
      'header'=>'Files',
      'type'=>'raw',
      'value'=>array($this, 'RenderFiles'),
    ),
    array(
      'class'=>'CButtonColumn',
      'template'=>'{delete}{upload}{mark}',
      'deleteButtonUrl'=>'Yii::app()->controller->createUrl("exercise/delete",array("id"=>$data->id))',
      'updateButtonUrl'=>'Yii::app()->controller->createUrl("exercise/update",array("id"=>$data->id))',
      'headerHtmlOptions'=>array('style'=>'width: 50px;', 'class'=>'buttons'),
      'htmlOptions'=>array('style'=>'text-align: right', 'class'=>'buttons'),
      'buttons'=>array(
        'delete'=>array(
          'label'=>'Remove',
          'options'=>array('title'=>Yii::t('swu', 'Remove')),
          'visible'=>'$data->isRemovable()',
        ),
        'upload'=>array(
          'label'=>'Upload',
          'options'=>array('title'=>Yii::t('swu', 'Upload')),
          'imageUrl'=>Yii::app()->request->baseUrl.'/images/upload.png',
          'url'=>'Yii::app()->controller->createUrl("file/upload",array("code"=>$data->code))',
        ),
        'mark'=>array(
          'label'=>'Mark',
          'options'=>array('title'=>Yii::t('swu', 'Mark')),
          'imageUrl'=>Yii::app()->request->baseUrl.'/images/mark.png',
          'url'=>'Yii::app()->controller->createUrl("exercise/update",array("id"=>$data->id))',
        )
      )

    ),
  ),
)); ?>

