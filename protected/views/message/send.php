<?php
/* @var $this MessageController */
/* @var $model Message */

$this->breadcrumbs=array(
  'Messages'=>array('index'),
  'Send',
);

if(Yii::app()->user->getState('sending')=='true')
{
  Yii::app()->clientScript->registerScript('stats', "

    (function worker() {
      $.ajax({
        url: 'index.php?r=message/do', 
        success: function(data) {
          $('#status').html(data);
        },
        complete: function() {
          // Schedule the next request when the current one's complete
          setTimeout(worker, 5000);
        }
      });
    })();
    ");
}
?>

<h1>Send Messages</h1>

<?php if(Yii::app()->user->getState('sending')!='true'): ?>
  <p>Mailing is not currently active.
  
  <?php if(Helpers::getYiiParam('sendEmails')): ?>
    <?php echo CHtml::link(
      'Activate it now',
      $url=CHtml::normalizeUrl(array('message/activation', 'active'=>'true')),
      array(
        'submit' => $url,
        'title' => 'Activate mailing',
      )
    )
    ?>.
  <?php else: ?>
    This web site does not allow sending emails, though (check configuration).
  <?php endif ?>
  </p>
<?php else: ?>
  <p>Mailing is currently active.
  <?php echo CHtml::link(
    'Disactivate it now',
    $url=CHtml::normalizeUrl(array('message/activation', 'active'=>'false')),
    array(
      'submit' => $url,
      'title' => 'Disactivate mailing',
    )
  )
  ?>.
  </p>
<?php endif ?>
<div id="status"><?php $this->renderPartial('_queue', array(
      'queued_messages'=>Message::model()->findAllConfirmedToBeSent(),
      )) ?>
</div>
