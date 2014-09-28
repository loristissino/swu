<?php
/* @var $this AssignmentController */
/* @var $dataProvider CActiveDataProvider */

?>

<h1><?php echo Yii::t('swu', 'Assignments') ?></h1>

<?php $number=1; foreach($assignments as $assignment): ?>
  <div class="assignment" lang="<?php echo $assignment->language ?>">
    <h2><?php echo $number++ ?>. <?php echo CHtml::encode($assignment->title) ?>
    <?php if($assignment->url): ?>
    <?php echo CHtml::link($this->createIcon('link', Yii::t('swu', 'Further information')), $assignment->url) ?>
    <?php endif ?>
    </h2>
    <p><?php echo nl2br(CHtml::encode($assignment->description)) ?></p>
  </div>
<?php endforeach ?>

<hr />
<?php echo CHtml::link(Yii::t('swu', 'Do you want information about an assignment not yet public?'), array('exercise/info')) ?>
