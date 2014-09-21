<?php
/* @var $this FileController */
/* @var $model File */

$this->breadcrumbs=array(
  'Files'=>array('index'),
  'Manage',
);

$this->menu=array(
  array('label'=>'List File', 'url'=>array('index')),
  array('label'=>'Create File', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
  $('.search-form').toggle();
  return false;
});
$('.search-form form').submit(function(){
  $('#file-grid').yiiGridView('update', {
    data: $(this).serialize()
  });
  return false;
});
");
?>

<h1>Manage Files</h1>

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

<?php $this->widget('zii.widgets.grid.CGridView', array(
  'id'=>'file-grid',
  'dataProvider'=>$model->search(),
  'filter'=>$model,
  'columns'=>array(
    array(
      'header'=>'Content',
      'type'=>'raw',
      'name'=>'content',
      'value'=>array($this, 'renderFileContent'),
      ),
    'uploaded_at',
    'checked_at',
    array(
      'header'=>'Assignment',
      'value'=>'CHtml::link(CHtml::encode($data->exercise->assignment->title), array("assignment/view","id"=>$data->exercise->assignment->id), array("class"=>"hiddenlink"))',
      'type'=>'raw',
      ),
    array(
//      'name'=>'student',
      'header'=>'Student',
      'value'=>'CHtml::link(CHtml::encode($data->exercise->student), array("student/view","id"=>$data->exercise->student->id), array("class"=>"hiddenlink"))',
      'type'=>'raw',
      ),
    array(
      'class'=>'CButtonColumn',
      'template'=>'{view}',
      'viewButtonUrl'=>'Yii::app()->controller->createUrl("file/view",array("id"=>$data->id,"hash"=>$data->md5))',
    ),
  ),
)); ?>
