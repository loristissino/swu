<?php
/* @var $this SiteController */
/* @var $model ContactForm */
/* @var $form CActiveForm */

$this->pageTitle=Yii::app()->name . ' - Admin';
$this->breadcrumbs=array(
  'Admin',
);
?>

<h1>Administration</h1>

<h2>Schoolwork management</h2>

<p><?php foreach(array(
  'Students'   =>'student/admin',
  'Assignments'=>'assignment/admin',
  'Files'=>'file/admin',
  'Messages'   =>'message/admin',
  ) as $label=>$action): ?>
  <?php echo CHtml::link($label, array($action)) ?><br />
<?php endforeach ?>
</p>

<h2>Configuration and utilities</h2>

<p><?php foreach(array(
  'Mail Templates' => 'mailtemplate/admin',
  'Backup'   =>'site/backup',
  'Password creation'   =>'utilities/password',
  ) as $label=>$action): ?>
  <?php echo CHtml::link($label, array($action)) ?><br />
<?php endforeach ?>
</p>

