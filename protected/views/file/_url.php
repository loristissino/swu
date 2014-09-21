<p class="note"><?php echo Yii::t('swu', 'Here you can write the URL of a file that you put somewhere on the web.') ?> <?php echo Yii::t('swu', 'Please be sure that the file is publicly accessible and downloadable, and do not remove it from where until it is evaluated.') ?></p>

<div class="row">
    <?php echo $form->labelEx($model,'url'); ?>
    <?php echo $form->textField($model,'url', array('size'=>60, 'placeholder'=>UploadForm::URL_EXAMPLE)); ?>
    <?php echo $form->error($model,'url'); ?>
</div>
