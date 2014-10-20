<?php if($filesInfo['checked']): ?><?php echo CHtml::link($this->createIcon('tick_'. $exercise->getColor(), Yii::t('swu', 'There is one checked file.|There are {n} checked files.', $filesInfo['checked'])), array('exercise/view', 'id'=>$exercise->id)) ?><?php endif ?>
<?php if($filesInfo['unchecked']): ?><?php echo CHtml::link($this->createIcon('new', Yii::t('swu', 'There is one file to be checked.|There are {n} files to be checked.', $filesInfo['unchecked'])), array('exercise/view', 'id'=>$exercise->id)) ?><?php endif ?>
<?php if($filesInfo['tardy']): ?><?php echo $this->createIcon('tardy', Yii::t('swu', 'One file has been marked tardy.|{n} files have been marked tardy.', $filesInfo['tardy'])) ?><?php endif ?>
<?php if(sizeof($filesInfo['comments'])): ?><?php echo $this->createIcon('comments', implode("\n", $filesInfo['comments'])) ?><?php endif ?>
<?php if($exercise->comment): ?><?php echo $this->createIcon('comment_edit', $exercise->comment) ?><?php endif ?>

