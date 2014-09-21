<?php
/* @var $this AssignmentController */
/* @var $model Assignment */

$this->layout='//layouts/labels';
$this->pageTitle=Yii::app()->name . ' - ' . $model->title;

?>
<?php foreach($exercises as $exercise): ?>
  <div class="label">
    <p>
    <span class="student"><?php echo $exercise->student ?></span> <?php if(!$exercise->student->email) echo $this->createIcon('email_edit', 'Missing email', array('width'=>16, 'height'=>16)) ?><br />
    <span class="assignment"><?php echo $exercise->assignment->title ?></span><br />
    <span class="url"><?php echo Helpers::getYiiParam('siteUrl') ?></span><br />
    <?php echo Yii::t('swu', 'code: %code%', array('%code%'=>$exercise->code)) ?><br />
    </p>
  </div>
<?php endforeach ?>
<div class="page-break"></div>
