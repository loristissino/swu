<?php
/* @var $this MessageController */
/* @var $model Message */

$this->breadcrumbs=array(
  'Messages',
  'Acknowledgement',
);

?>

<h1>Message Receipt Acknowledgment</h1>

<?php if($result): ?>
  <p>Thank you for acknowledging the receipt of the message.</p>
<?php else: ?>
  <p>Oops... something went wrong. The receipt of this message could not be acknowledged.</p>
<?php endif ?>
