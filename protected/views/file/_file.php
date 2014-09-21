<span class="<?php echo $model->hasBeenChecked() ? 'file_checked': 'file_unchecked' ?>"><?php echo $model->uploaded_at ?></span>

<?php if($model->original_name): ?>
<b><?php echo CHtml::link($model->original_name, array('file/download', 'id'=>$model->id, 'hash'=>$model->md5)) ?></b> (Size: <?php echo $model->size ?>, type: <?php echo $model->type ?>)

<?php else: ?>
<i><?php echo CHtml::link($model->url, $model->url, array('target'=>'_blank')) ?></i>
<?php endif ?>

<?php echo CHtml::link('info', array('file/view', 'id'=>$model->id, 'hash'=>$model->md5)) ?>
 - 
<?php echo CHtml::link('evaluate', array('exercise/update', 'id'=>$exercise->id, 'version'=>$version, 'file'=>$model->id)) ?>
<?php if(!$model->checked_at): ?>
 - 
<?php echo CHtml::link('touch', $url=CHtml::normalizeUrl(array('file/mark', 'id'=>$model->id)), array(
      'submit' => $url,
      'title' => 'Mark this file as checked',
      'csrf'=>false,
    )) ?>
<?php endif ?>
<?php if($model->comment): ?>
<br />
<em style="color: green"><?php echo $model->comment ?></em>
<?php endif ?>
