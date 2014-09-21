<?php if($exercise): ?>
<?php echo CHtml::link($exercise->assignment, array('assignment/view', 'id'=>$exercise->assignment_id)) ?>
<?php endif ?>
