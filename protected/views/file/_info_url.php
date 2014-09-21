<ul>
  <?php if($model->url): ?>
  <li>URL: <b><?php echo CHtml::link($model->url, $model->url, array('target'=>'_blank')) ?></b></li>
  <?php endif ?>
  <?php if($model->content): ?>
  <li>Content: <br />
  <textarea cols="50" rows="10"><?php echo $model->content ?></textarea>
  </li>
  <?php endif ?>
  <?php echo $this->renderPartial('_info_checked_at', array('file'=>$model, 'li'=>true)) ?>
</ul>
