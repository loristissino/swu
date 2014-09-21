<p class="note"><?php echo Yii::t('swu', 'Here you can paste your exercise.') ?> <?php echo Yii::t('swu', 'This is meant for source codes of programs, code snippets, markdown texts, and the like. Do not use it for formatted texts.') ?></p>
<div class="row">
  <?php echo $form->labelEx($model,'content'); ?>
  <?php echo $form->textArea($model, 'content', array('rows' => 10, 'cols' => 70)); ?>
  <?php echo $form->error($model,'content'); ?>
</div>
