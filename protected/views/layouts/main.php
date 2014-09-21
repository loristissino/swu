<?php /* @var $this Controller */ ?>
<!DOCTYPE html >
<html lang="<?php echo Yii::app()->language ?>">
<head>
  <meta charset="utf-8" />

  <!-- blueprint CSS framework -->
  <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
  <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
  <!--[if lt IE 8]>
  <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
  <![endif]-->

  <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
  
  <link rel="icon" href="<?php echo Yii::app()->request->baseUrl; ?>/images/swu.png" type="image/png" />
  <title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>

<div class="container" id="page">

  <div id="header">
    <div id="logo"><?php echo CHtml::encode(Yii::app()->name); ?></div>
  </div><!-- header -->

  <div id="mainmenu">
    <?php $this->widget('zii.widgets.CMenu',array(
      'items'=>array(
        array('label'=>Yii::t('swu', 'Home'), 'url'=>array('/assignment/list')),
        array('label'=>Yii::t('swu', 'Upload'), 'url'=>array('/file/upload')),
        array('label'=>Yii::t('swu', 'About'), 'url'=>array('/site/page', 'view'=>'about')),
        array('label'=>Yii::t('swu', 'Contact'), 'url'=>array('/site/contact')),
        array('label'=>Yii::t('swu', 'Login'), 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
        array('label'=>'Logout', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest),
        array('label'=>'Students', 'url'=>array('/student/admin'), 'visible'=>!Yii::app()->user->isGuest),
        array('label'=>'Assignments', 'url'=>array('/assignment/admin'), 'visible'=>!Yii::app()->user->isGuest),
//        array('label'=>'Exercises', 'url'=>array('/exercise/admin'), 'visible'=>!Yii::app()->user->isGuest),
        array('label'=>'Files', 'url'=>array('/file/admin'), 'visible'=>!Yii::app()->user->isGuest),
        array('label'=>'Messages', 'url'=>array('/message/admin'), 'visible'=>!Yii::app()->user->isGuest),
        array('label'=>'Admin', 'url'=>array('/site/admin'), 'visible'=>!Yii::app()->user->isGuest),
      ),
    )); ?>
  </div><!-- mainmenu -->
  <?php if(isset($this->breadcrumbs)):?>
    <?php $this->widget('zii.widgets.CBreadcrumbs', array(
      'links'=>$this->breadcrumbs,
    )); ?><!-- breadcrumbs -->
  <?php endif?>

  <?php if(Yii::app()->user->hasFlash('flash-success')): ?>
    <div class="flash-success">
    <?php echo Yii::app()->user->getFlash('flash-success') ?>
    </div>
  <?php endif ?>
  <?php if(Yii::app()->user->hasFlash('flash-error')): ?>
    <div class="flash-error">
    <?php echo Yii::app()->user->getFlash('flash-error') ?>
    </div>
  <?php endif ?>

  <?php echo $content; ?>

  <div class="clear"></div>

  <div id="footer">
    Copyright &copy; <?php echo date('Y'); ?> by Loris Tissino. - release <?php echo SWU::RELEASE ?><br/>
    All Rights Reserved.<br/>
    <?php echo Yii::powered(); ?>
  </div><!-- footer -->
  
</div><!-- page -->

<?php // phpinfo(); ?>
</body>
</html>
