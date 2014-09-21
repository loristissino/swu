<?php
/**
 * ExerciseInfoForm class file.
 *
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 * @author Loris Tissino <loris.tissino@gmail.com>
 * @copyright Copyright &copy; 2014 Loris Tissino
 * @since 1.0
 */
/**
 * ExerciseInfoForm class file.
 * 
 * @package application.forms
 * 
 */

class ExerciseInfoForm extends CFormModel
{
  public $code;
  public $verifyCode;
  public $exercise;
  
  public function rules()
  {
    return array(
      array('code', 'required'),
      array('code', 'checkCode'),
      // verifyCode needs to be entered correctly
      array('verifyCode', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements(), 'captchaAction' => 'site/captcha'),
    );
  }

  public function checkCode()
  {
    Helpers::normalizeCode($this->code);
    if(!$this->exercise=Exercise::model()->findByAttributes(array('code'=>trim($this->code))))
    {
      $this->addError('code', Yii::t('swu', 'The code you entered does not exist.'));
    }
  }
  
  /**
   * @return array customized attribute labels (name=>label)
   */
  public function attributeLabels()
  {
    return array(
      'code' => Yii::t('swu', 'Code'),
      'verifyCode'=>Yii::t('swu', 'Verification Code'),
    );
  }

}

