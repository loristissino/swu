<?php if($exercise): ?>
<?php echo CHtml::link($exercise->student, array('student/view', 'id'=>$exercise->student_id)) ?>
<?php endif ?>
