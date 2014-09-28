<?php if($model->original_name): ?>
<?php
if($name=='hash')
{
  $filename=$model->getFile();
}
else
{
  $filename=sprintf('%s, %s - %s %s - %s', $student->lastname, $student->firstname, $model->getUploadedAt(), $model->getFile(), $model->original_name);
}
?>
wget -c '<?php echo $this->createAbsoluteSslUrl('file/raw', array('id'=>$model->id, 'hash'=>$model->md5))?>' \
   -O "<?php echo $filename ?>"; sleep 6;
<?php endif ?>
<?php if($model->content): ?>
<?php $filename=sprintf('%s, %s - %s - plaintext.txt', $student->lastname, $student->firstname, $model->getUploadedAt()); ?>
wget -c '<?php echo $this->createAbsoluteSslUrl('file/plaintext', array('id'=>$model->id, 'hash'=>$model->md5))?>' \
   -O "<?php echo $filename ?>"; sleep 6;
<?php endif ?>
