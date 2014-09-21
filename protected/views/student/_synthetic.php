<?php if(sizeof($exercises)): ?>

  <table>
    <tr>
      <th>Due Date</th>
      <th>Files</th>
      <th>Mark</th>
      <th>Title</th>
    </tr>
    <?php foreach($exercises as $exercise): ?>
    <tr>
      <td><?php echo $exercise->assignment->duedate ?></td>
      <td><?php echo sizeof($exercise->files) ?></td>
      <td><?php echo $exercise->mark ?></td>
      <td><?php echo CHtml::link($exercise->assignment->title, array('assignment/view', 'id'=>$exercise->assignment_id, '#'=>'student'.$student->id), array('class'=>'hiddenlink')) ?></td>
    </tr>
    <?php endforeach ?>
  </table>

<?php else: ?>
<p>No exercises assigned.</p>
<?php endif ?>
