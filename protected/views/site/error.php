<?php
/* @var $this SiteController */
/* @var $error array */

$this->pageTitle=Yii::app()->name . ' - Error';
$this->breadcrumbs=array(
  'Error',
);
?>

<h2>Error <?php echo $code; ?></h2>

<div class="error">
  <?php echo CHtml::encode($message); ?>
</div>
<?php if($code==500): ?>
  <div>
  An unexpected error occurred. It happens, sometimes. Please reload this page and see if it persists.
  </div>
<?php endif ?>
