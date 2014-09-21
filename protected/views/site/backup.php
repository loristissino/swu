<?php
/* @var $this SiteController */
/* @var $model ContactForm */
/* @var $form CActiveForm */

$this->pageTitle=Yii::app()->name . ' - Admin';
$this->breadcrumbs=array(
  'Backup',
);
?>

<h1>Backup</h1>

<textarea cols="80" rows="20">
<?php echo $sql ?>
ALTER TABLE assignment ENGINE=InnoDB;
ALTER TABLE exercise ENGINE=InnoDB;
ALTER TABLE file ENGINE=InnoDB;
ALTER TABLE student ENGINE=InnoDB;
ALTER TABLE message ENGINE=InnoDB;
</textarea>
<?php /*
<form method="post">
<input type="submit" name="Backup" value="Prepare the backup file" />
</form>
*/ ?>

