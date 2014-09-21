<?php
/* @var $this SiteController */
/* @var $model ContactForm */
/* @var $form CActiveForm */

$this->pageTitle=Yii::app()->name . ' - Admin';
$this->breadcrumbs=array(
  'Files',
);
?>

<h1>Backup files</h1>

<textarea cols="80" rows="20">
<?php foreach($files as $file): ?>
<?php echo $this->renderPartial('../file/_wget', array('model'=>$file, 'name'=>'hash')); ?>
<?php endforeach ?>
</textarea>
