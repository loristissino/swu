<?php
/**
 * MessageForm class file.
 *
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 * @author Loris Tissino <loris.tissino@gmail.com>
 * @copyright Copyright &copy; 2014 Loris Tissino
 * @since 1.0
 */
/**
 * MessageForm class.
 * MessageForm is the data structure for keeping
 * message form data.
 * 
 * @package application.forms
 * 
 */
 
class MessageForm extends CFormModel
{
  public $subject;
  public $body;
  public $acknowledgement;
  public $confirmed;

  /**
   * Declares the validation rules.
   */
  public function rules()
  {
    return array(
      // name, email, subject and body are required
      array('subject, body', 'required'),
      array('acknowledgement, confirmed', 'safe'),
    );
  }

  /**
   * Declares customized attribute labels.
   * If not declared here, an attribute would have a label that is
   * the same as its name with the first letter in upper case.
   */
  public function attributeLabels()
  {
    return array(
      'subject'=> Yii::t('swu', '<!--email-->Subject'),
      'body'=> Yii::t('swu', 'Message Text'),
      'acknowledgement' => Yii::t('swu', 'Require Acknowledgment'),
      'confirmed' => Yii::t('swu', 'Confirmed'),
    );
  }
  
  public function prepareMessages($students)
  {
    $result = array('prepared'=>0, 'failed'=>0);
    
    foreach($students as $student)
    {
      if(!$student->email)
      {
        $result['failed']++;
        continue;
      }
      if (MailTemplate::model()->messageFromTemplate('direct_message', $student->id, array(
        'student'=>$student,
        'subject'=>$this->subject,
        'body'=>$this->body,
        ),
        $this->confirmed, $this->acknowledgement))
      {
        $result['prepared']++;
      }
      else
      {
        $result['failed']++;
      }
    }
    return $result;      

  }
  
}
