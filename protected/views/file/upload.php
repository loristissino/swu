<?php
/* @var $this FileController */
/* @var $model File */

?>

<h1><?php echo Yii::t('swu', 'Upload File / Turn in Homework') ?></h1>

<?php echo $this->renderPartial('_upload', array('model'=>$model)); ?>

<hr />
<p>
<?php echo CHtml::link(Yii::t('swu', 'Don\'t have the code?'), array('student/sendcodes')) ?><br />
<?php echo CHtml::link(Yii::t('swu', 'Want only some info?'), array('exercise/info')) ?>
</p>
