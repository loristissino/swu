<?php
/**
 * SendcodesForm class file.
 *
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 * @author Loris Tissino <loris.tissino@gmail.com>
 * @copyright Copyright &copy; 2014 Loris Tissino
 * @since 1.0.5
 */
/**
 * SendcodesForm class.
 * SendcodesForm is the data structure for allowing the user to be sent the codes for his/her exercise.
 * 
 * @package application.forms
 * 
 */
class SendcodesForm extends CFormModel
{
  public $email;
  public $verifyCode;
  
  public function rules()
  {
    return array(
      array('email', 'required'),
      // verifyCode needs to be entered correctly
      array('verifyCode', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements(), 'captchaAction' => 'site/captcha'),
    );
  }
  
  /**
   * @return array customized attribute labels (name=>label)
   */
  public function attributeLabels()
  {
    return array(
      'email' => Yii::t('swu', 'Email'),
      'verifyCode'=>Yii::t('swu', 'Verification Code'),
    );
  }
  
  public function sendCodes($controller)
  {
    if(!$student = Student::model()->findByAttributes(array('email'=>$this->email)))
    {
      return false;
    }
    
    $exercises = Exercise::model()->with('assignment')->sortByDuedate()->findAllByAttributes(array('student_id'=>$student->id));
    foreach($exercises as $exercise)
    {
      $exercise->link = Yii::app()->controller->createAbsoluteSslUrl('exercise/info', array('k'=>$exercise->generateAckKey()));
    }
    
    $options = array();
    if(Helpers::getYiiParam('addOriginatingIP'))
    {
      $options['originating_IP']=sprintf('[%s]', Yii::app()->request->userHostAddress);
    }
    
    return MailTemplate::model()->mailFromTemplate('send_codes', array($student->email=>$student->name), array(
        'student'=>$student,
        'exercises'=>$exercises,
      ),
      $options
    );
    
  }

}

