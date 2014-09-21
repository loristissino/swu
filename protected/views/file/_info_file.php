<ul>
  <li>Original file name: <b><?php echo CHtml::link($model->original_name, array('file/download', 'id'=>$model->id, 'hash'=>$model->md5)) ?></b></li>
  <li>Size (bytes): <b><?php echo $model->size ?></b></li>
  <li>Media type: <b><?php echo $model->type ?></b></li>
  <li>Date: <b><?php echo $model->uploaded_at ?></b></li>
  <?php echo $this->renderPartial('_info_checked_at', array('file'=>$model, 'li'=>true)) ?>
</ul>
