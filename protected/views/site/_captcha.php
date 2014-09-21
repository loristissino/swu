<?php if(CCaptcha::checkRequirements()): ?>
<div class="row">
  <?php echo $form->labelEx($model,'verifyCode'); ?>
  <div>
  <?php $this->widget('CCaptcha', array('captchaAction' => 'site/captcha', 'buttonLabel'=>Yii::t('swu', 'Get a new code'))); ?>
  <?php echo $form->textField($model,'verifyCode'); ?>
  </div>
  <div class="hint"><?php echo Yii::t('swu', 'Please enter the letters as they are shown in the image above.') ?>
  <br/><?php echo Yii::t('swu', 'Letters are not case-sensitive.') ?></div>
  <?php echo $form->error($model,'verifyCode'); ?>
</div>
<?php endif; ?>
