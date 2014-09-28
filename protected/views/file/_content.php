<?php if($file->isTardy()) echo $this->createIcon('tardy', Yii::t('swu', 'Tardy')) ?><?php $link = Yii::app()->user->isGuest ? null : array('file/view', 'id'=>$file->id, 'hash'=>$file->md5) ?>
<?php if($file->content): ?>
  <?php echo CHtml::link($this->createIcon('paste_plain', 'Pasted Content', array('width'=>16, 'height'=>16)), $link) ?> <?php echo Yii::t('swu', '%number% characters', array('%number%'=>strlen($file->content))) ?><br />
<?php endif ?>
<?php if($file->url): $host=parse_url($file->url, PHP_URL_HOST) ?>
  <?php echo CHtml::link($this->createIcon('link', 'URL', array('width'=>16, 'height'=>16)), $link, array('class'=>'hiddenlink')) ?> <?php echo CHtml::link($host, $file->url) ?><br />
<?php endif ?>
<?php if($file->original_name): ?>
  <?php echo CHtml::link($this->createIcon('page_white_text', 'Uploaded File', array('width'=>16, 'height'=>16)), $link, array('class'=>'hiddenlink')) ?> <?php echo Yii::t('swu', '%number% bytes', array('%number%'=>$file->size)) ?><br />
<?php endif ?>
