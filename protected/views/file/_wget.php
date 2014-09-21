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
