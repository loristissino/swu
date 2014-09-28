<?php
/* @var $this StudentController */
/* @var $model Student */

$this->breadcrumbs=array(
  'Students'=>array('index'),
  'Manage',
);

if($this->assignment)
{
  $this->menu=array(
    array('label'=>'This Assignment', 'url'=>array('assignment/view', 'id'=>$this->assignment->id), 'linkOptions'=>array('title'=>$this->assignment->title)),
    array('label'=>'All students', 'url'=>array('student/admin')),
  );
}
else
{
  $this->menu=array(
    array('label'=>'Create Student', 'url'=>array('create')),
    array('label'=>'Bulk Import', 'url'=>array('import')),
  );
}

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
  $('.search-form').toggle();
  return false;
});
$('.search-form form').submit(function(){
  $('#student-grid').yiiGridView('update', {
    data: $(this).serialize()
  });
  return false;
});
");
?>

<h1>Manage Students</h1>

<?php if ($this->assignment): ?>
  <p><?php echo Yii::t('swu', 'The following students have been assigned «%title%»:', array('%title%'=>$this->assignment->title)) ?></p>
<?php else: ?>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
  'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php endif ?>

<?php 

echo CHtml::beginForm('','post',array('id'=>'student-form'));

$this->widget('zii.widgets.grid.CGridView', array(
  'id'=>'student-grid',
  'dataProvider'=>$dataProvider,
  'selectableRows'=>2, // multiple rows can be selected

  'filter'=>$model,
  'columns'=>array(
    'id',
    'firstname',
    'lastname',
    'gender',
    array(
      'class'=>'CDataColumn',
      'header'=>'email',
      'type'=>'raw',
      'value'=>'$data->getPartialEmail()',
    ),
    'roster',
    array(
      'class'=>'CButtonColumn',
      'template'=>'{view}{update}',
    ),
    array(
      'class'=>'CCheckBoxColumn',
      'id'=>'id',
      'value'=>'$data->id',
      
    ),
  ),
));

echo CHtml::endForm();
?>

<p><?php echo 'With the selected students:' ?>
<?php if(Yii::app()->getUser()->getState('assignment')): $label = Yii::t('swu', ($this->assignment?'remove assignment «%name%»':'assign «%name%»'), array('%name%'=>Yii::app()->getUser()->getState('assignment-title'))) ?><br />
<?php $this->widget('ext.widgets.bmenu.XBatchMenu', array(
    'formId'=>'student-form',
    'checkBoxId'=>'id',
//    'ajaxUpdate'=>'person-grid', // if you want to update grid by ajax
    'emptyText'=>'Please select the students you would like to perform this action on!',
    'items'=>array(
        array('label'=>$label,'url'=>array('assignment/students', 'action'=>($this->assignment?'remove':'assign'), 'assignment'=>Yii::app()->getUser()->getState('assignment'))),
    ),
    'htmlOptions'=>array('class'=>'actionBar'),
    'containerTag'=>'span',
));
?>
<?php endif ?>
<br /><?php $this->widget('ext.widgets.bmenu.XBatchMenu', array(
    'formId'=>'student-form',
    'checkBoxId'=>'id',
//    'ajaxUpdate'=>'person-grid', // if you want to update grid by ajax
    'emptyText'=>'Please select the students you would like to perform this action on!',
    'items'=>array(
        array('label'=>'prepare message ','url'=>array('student/message')),
        array('label'=>'get email addresses ','url'=>array('student/emailaddresses')),
        array('label'=>'get report','url'=>array('student/report')),
        
    ),
    'htmlOptions'=>array('class'=>'actionBar'),
    'containerTag'=>'span',
));
?>
</p>
