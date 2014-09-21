<?php
/**
 * MailTemplate class file.
 *
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 * @author Loris Tissino <loris.tissino@gmail.com>
 * @copyright Copyright &copy; 2014 Loris Tissino
 * @since 1.4.2
 */
/**
 * This is the model class for table "mailtemplate".
 *
 * The followings are the available columns in table 'mailtemplate':
 * @property integer $id
 * @property string $code
 * @property string $lang
 * @property string $subject
 * @property string $plaintext_body
 * @property string $html_body
 * 
 * @package application.models
 * 
 */
 
class MailTemplate extends CActiveRecord
{
  private $_template = null;
  
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'mailtemplate';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('code', 'length', 'max'=>30),
			array('lang', 'length', 'max'=>7),
			array('subject, plaintext_body, html_body', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, code, lang, subject, plaintext_body, html_body', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'code' => 'Code',
			'lang' => 'Lang',
			'subject' => 'Subject',
			'plaintext_body' => 'Body (plaintext)',
			'html_body' => 'Body (html)',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('lang',$this->lang,true);
		$criteria->compare('subject',$this->subject,true);
		$criteria->compare('plaintext_body',$this->plaintext_body,true);
		$criteria->compare('html_body',$this->html_body,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
  
  public function mailFromTemplate($code, $to, $replacements=array(), $options=array())
  {
    $this->findTemplateAndMakeReplacements($code, $replacements);
    return Mailer::mail($to, $this->_template->subject, $this->_template->plaintext_body, $this->_template->html_body, $options);
  }
  
  public function messageFromTemplate($code, $student_id, $replacements=array(), $confirmed=false, $acknowledgement_required = false)
  {
    $this->findTemplateAndMakeReplacements($code, $replacements);
    $message = new Message;
    $message->student_id = $student_id;
    $message->subject = $this->_template->subject;
    $message->body = $this->_template->plaintext_body;
    $message->html = $this->_template->html_body;
    if($confirmed)
    {
      $message->confirmed_at = date('Y-m-d H:i:s');
    }
    if(!$acknowledgement_required)
    {
      $message->acknowledged_at = 0;
    }
    
    $message->save(false);
    return true;
  }
  
  public function findTemplateAndMakeReplacements($code, $replacements)
  {
    $lang = Yii::app()->language;
    if(!$this->_template = MailTemplate::findByAttributes(array('code'=>$code, 'lang'=>$lang)))
    {
      $lang = 'en';  // as a fallback, we look for a template in English
      if(!$this->_template = MailTemplate::findByAttributes(array('code'=>$code, 'lang'=>$lang)))
      {
        throw new Exception ("Could not find mail template $code (language: $lang)");
      }
    }
    
    $twig = new Twig();
    
    foreach (array('subject', 'plaintext_body', 'html_body') as $field)
    {
      $this->_template->$field = $twig->render($this->_template->$field, array_merge(array(
        'site_url' => Helpers::getYiiParam('siteUrl'),
        'site_name' => Yii::app()->name,
        'lang' => $lang,
        'now' => date('Y-m-d H:i:s'),
      ), $replacements));
    }  
    
  }
  
  public function getSubtemplateField($property)
  {
    return $this->_template->$property;
  }
  
  public function listRequired()
  {
    // this must be kept updated
    return array(
      'standard_head' => 'standard head for all messages',
      'standard_tail' => 'standard tail for all messages',
      'new_work_notification' => 'used to inform admin that a new work has been uploaded',
      'new_work_acknowledgement' => 'used to confirm to the student the correct uploading of his/her work',
      'new_assignment_notification' => 'used to inform a student that he/she has a new assignment',
      'evaluation_notification' => 'used to inform a student about a new evaluation',
      'send_codes' => 'used to send upload codes to a student after explicit request',
      'contact_form' => 'used to send the contents of the contact form',
      'direct_message' => 'used as an envelope for direct messages',
    );
  }
  
	/**
	 * Returns the templates missing or exceeding, according to current language
	 * @return array the names of missing templates on the keys, and a state value
	 */
  public function findMissingAndExceeding()
  {
    $list = $this->listRequired();

    $languages = array('en');    
    if(Yii::app()->language!='en')
    {
      $languages[]=Yii::app()->language;
    }
    
    array_walk($list, function (&$v, $key) {$v='missing';} );
    // first, we set each required template as missing
    
    foreach($languages as $language)
    {
      foreach($this->findAllByAttributes(array('lang'=>$language)) as $template)
      {
        $list[$template->code] = array_key_exists($template->code, $list) ? sprintf('present (%s)', $language): 'unrequired'; 
      }
    }
    
    return $list;
    
  }
  
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return MailTemplate the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
  
  
  
}
