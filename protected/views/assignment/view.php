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
      'value'=>'CHtml::link(CHtml::encode($data->student->name), array("student/view","id"=>$data->student_id), array("class"=>"hiddenlink"))',
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
      'value'=>'$data->getFilesLink()',
    ),
    array(
      'class'=>'CButtonColumn',
      'template'=>'{delete}{update}',
      'deleteButtonUrl'=>'Yii::app()->controller->createUrl("exercise/delete",array("id"=>$data->id))',
      'updateButtonUrl'=>'Yii::app()->controller->createUrl("exercise/update",array("id"=>$data->id))',
      'headerHtmlOptions'=>array('style'=>'width: 40px;', 'class'=>'buttons'),
      'htmlOptions'=>array('style'=>'text-align: right', 'class'=>'buttons'),
      'buttons'=>array(
        'delete'=>array(
          'label'=>'Remove',
          'options'=>array('title'=>Yii::t('swu', 'Remove')),
          'visible'=>'$data->isRemovable()',
        ),
        'update'=>array(
          'label'=>'Edit',
          'options'=>array('title'=>Yii::t('swu', 'Edit')),
        )
      )

    ),
  ),
)); ?>


<?php /*


<?php if($format!='codes'): ?>
<?php foreach($exercises as $exercise): ?>
  <h3 id="student<?php echo $exercise->student_id ?>"><?php echo CHtml::link($exercise->student, array('student/view', 'id'=>$exercise->student_id)) ?><?php echo $this->renderPartial('../student/_email_icon', array('model'=>$exercise->student)); ?></h3>
  
  <p>
    <?php echo CHtml::link('evaluate', array('exercise/update', 'id'=>$exercise->id))?>
  </p>

<?php endforeach ?>

<?php endif ?>
<?php if($format=='files'): ?>
<hr />
<div>
<h2>Backup</h2>
<textarea cols="70" rows="10">
<?php foreach($exercises as $exercise): ?>
<?php foreach($exercise->files as $file): ?>
<?php echo $this->renderPartial('../file/_wget', array('model'=>$file, 'name'=>'hash')); ?>
<?php endforeach ?>
<?php endforeach ?>
</textarea>
</div>

<div>
<h2>Correction</h2>
<textarea cols="70" rows="10">
<?php foreach($exercises as $exercise): ?>
<?php foreach($exercise->files as $file): ?>
<?php echo $this->renderPartial('../file/_wget', array('model'=>$file, 'name'=>'student', 'student'=>$exercise->student)); ?>
<?php endforeach ?>
<?php endforeach ?>
</textarea>
</div>


<?php endif ?>

<?php if($format=='marks'): ?>
<h2>Recap</h2>
<table>
  <tr>
    <th style="width: 200px">Name</th><th>Mark</th>
  </tr>
  <?php foreach($exercises as $exercise): ?>
  <tr>
    <td><?php echo $exercise->student ?></td><td style="font-family: monospaced, Courier"><?php echo $exercise->mark ?></td>
  </tr>
  <?php endforeach ?>
</table>

<?php endif ?>



<hr />
<p>
<?php foreach(array('codes', 'marks', 'files') as $viewformat): ?>
  <?php echo Chtml::link($viewformat, array('assignment/view', 'id'=>$model->id, 'format'=>$viewformat)) ?><br />
<?php endforeach ?>
  <?php echo Chtml::link('generate messages', array('assignment/messages', 'id'=>$model->id)) ?><br />
</p>

<?php if($format=='codes'): ?>

<?php foreach($exercises as $exercise): ?>
  <h3><?php echo $exercise->student ?></h3>
  <h4><?php echo $exercise->assignment->title ?></h4>
    <p><?php echo $exercise->assignment->description ?></p>
    <p>
    Website: <?php echo Helpers::getYiiParam('siteUrl') ?><br />
    Code: <?php echo $exercise->code ?><br />
    <?php if($exercise->assignment->url): ?>
      Info: <?php echo $exercise->assignment->url ?><br />
    <?php endif ?>
    </p>
    <hr />
<?php endforeach ?>

<hr />
  <?php echo Chtml::link('generate invitations', array('assignment/generateinvitations', 'id'=>$model->id)) ?>
<?php endif ?>

*/ ?>
