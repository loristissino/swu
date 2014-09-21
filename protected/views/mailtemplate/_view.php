<?php
/* @var $this MailtemplateController */
/* @var $data MailTemplate */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('code')); ?>:</b>
	<?php echo CHtml::encode($data->code); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('lang')); ?>:</b>
	<?php echo CHtml::encode($data->lang); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('subject')); ?>:</b>
	<span class="preformatted"><?php echo CHtml::encode($data->subject); ?></span>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('plaintext_body')); ?>:</b><br />
  <span class="preformatted"><?php echo nl2br(CHtml::encode($data->plaintext_body)); ?></span>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('html_body')); ?>:</b><br />
  <span class="preformatted"><?php echo nl2br(CHtml::encode($data->html_body)); ?></span>
	<br />


</div>
