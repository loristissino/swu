<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name . ' - About';
$this->breadcrumbs=array(
  'About',
);
?>
<h1><?php echo Yii::t('swu', 'About') ?></h1>

<?php $this->renderPartialIfAvailable('pages/_about_customcontent') ?>

<hr />
<h2>License and Credits</h2>

<p>This website is based on the application <a href="<?php echo SWU::WEBSITE ?>">SWU</a>, by <a href="http://loris.tissino.it">Loris Tissino</a>.
The release used here is <?php echo SWU::RELEASE ?>.</p>
