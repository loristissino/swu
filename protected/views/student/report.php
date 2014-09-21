<?php
/* @var $this StudentController */
/* @var $model Student */

$this->breadcrumbs=array(
  'Students'=>array('index'),
  'Report',
);

$this->menu=array(
  array('label'=>'Create Student', 'url'=>array('create')),
  array('label'=>'Manage Students', 'url'=>array('admin')),
);
?>

<h1>Students Report</h1>

<ul id="index">
<?php foreach($students as $student): ?>
  <li><?php echo CHtml::link($student, '#'.$student->id) ?></li>
<?php endforeach ?>
</ul>

<?php foreach($students as $student): ?>
  <h2 id="<?php echo $student->id ?>"><?php echo $student ?></h2>
  <?php echo $this->renderPartial($subtemplate, array(
    'exercises'=>$student->getExercises(), 
    'level'=>3, 
    'student'=>$student,
  )) ?>

<p><?php echo CHtml::link('top', '#index') ?></p>
<?php endforeach ?>
