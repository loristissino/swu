<?php if(sizeof($exercises)): ?>
<h<?php echo $level ?>>Exercises</h<?php echo $level ?>>
<?php foreach($exercises as $exercise): ?>
<h<?php echo $level+1 ?>>
«<?php echo CHtml::link($exercise->assignment->title, array('assignment/view', 'id'=>$exercise->assignment_id, 'format'=>'marks')) ?>»
 (<?php echo $exercise->id ?>)
</h<?php echo $level+1 ?>>
<p>due date: <?php echo CHtml::link($exercise->duedate, array('exercise/update', 'id'=>$exercise->id)) ?></p>
<?php if(sizeof($files=$exercise->files)>0): ?>
  <p>
    <?php foreach($files as $file): ?>
    <?php echo CHtml::link($file->original_name, array('file/view', 'id'=>$file->id, 'hash'=>$file->md5)) ?> (<?php echo $file->uploaded_at ?>)<br />
    <?php endforeach ?>
  </p>
<?php endif ?>
<p>
<?php echo $exercise->mark ?><br>
  <?php echo nl2br($exercise->comment) ?>
</p>
<?php endforeach ?>
<?php else: ?>
<p>No exercises assigned.</p>
<?php endif ?>
