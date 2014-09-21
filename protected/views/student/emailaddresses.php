<?php
/* @var $this StudentController */
/* @var $model Student */

$this->breadcrumbs=array(
  'Students'=>array('admin'),
  'Email addresses',
);

$this->menu=array(
  array('label'=>'Manage Students', 'url'=>array('admin')),
);
?>

<h1>Students' email addresses</h1>

<?php if(sizeof($students_with_email)): ?>
<p>Copy and paste the following email addresses in the Bcc field of your Mail User Agent.</p>

<textarea cols="80" rows="<?php echo 1+sizeof($students_with_email) ?>">
<?php foreach($students_with_email as $student): ?>
<?php echo $student ?> &lt;<?php echo $student->email ?>&gt;,
<?php endforeach ?>
</textarea><br />
<?php endif ?>

<?php if(sizeof($students_without_email)): ?>
<p>For the following students no email address is provided:</p>
<ul>
<?php foreach($students_without_email as $student): ?>
  <li><?php echo $student ?></li>
<?php endforeach ?>
</ul>
<?php endif ?>
