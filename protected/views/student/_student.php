<?php echo CHtml::link(CHtml::encode($student->name), array("student/view","id"=>$student->id), array("class"=>"hiddenlink")) ?>
<?php if(!$student->email): ?> <?php echo $this->createIcon('email_edit', 'Missing email', array('width'=>16, 'height'=>16)) ?><?php endif ?>
