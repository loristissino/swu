<?php
/* @var $this UtilitiesController */

$this->breadcrumbs=array(
  'Utilities'=>array('utilities/index'),
  'Password',
);

?>

<h1>Password</h1>

<p>The password has been encrypted.<br />
<textarea style="color: orange" cols="70" rows="1">
<?php echo $password ?>
</textarea><br />
You can save this text in the configuration file.</p>

<p><?php echo CHtml::link('New password', array('utilities/password') ) ?></p>
