<p class="note"><?php echo Yii::t('swu', 'Here you can upload a file choosing it from your computer.') ?> <?php echo Yii::t('swu', 'Please note that the maximum allowed size is %number% KiB.', array('%number%'=>Helpers::getYiiParam('uploadMaxSize'))) ?> <?php echo Yii::t('swu', 'If your file is bigger, you can either zip it or use the URL tab.') ?></p>

<div class="row">
  <?php echo $form->labelEx($model,'uploadedfile'); ?>
  <?php echo $form->fileField($model,'uploadedfile'); ?>
  <?php echo $form->error($model,'uploadedfile'); ?>
</div>
