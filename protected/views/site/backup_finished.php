<?php
/* @var $this SiteController */
/* @var $model ContactForm */
/* @var $form CActiveForm */

$this->pageTitle=Yii::app()->name . ' - Admin';
$this->breadcrumbs=array(
  'Backup',
);
?>

<h1>Backup finished</h1>

<p>Backup is ready. Filename: <?php echo CHtml::link($file, array('file/servesql', 'filename'=>$file)) ?></p>
