<?php
/**
 * Message class file.
 *
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 * @author Loris Tissino <loris.tissino@gmail.com>
 * @copyright Copyright &copy; 2014 Loris Tissino
 * @since 1.4.2
 */
/**
 * This is the model class for table "message".
 *
 * The followings are the available columns in table 'message':
 * @property integer $id
 * @property integer $student_id
 * @property string $subject
 * @property string $body
 * @property string $html
 * @property string $confirmed_at
 * @property string $sent_at
 * @property string $seen_at
 * @property string $acknowledged_at
 *
 * The followings are the available model relations:
 * @property Student $student
 * 
 * @package application.models
 * 
 */
class Message extends CActiveRecord
{
  /**
   * Returns the static model of the specified AR class.
   * @param string $className active record class name.
   * @return Message the static model class
   */
  public static function model($className=__CLASS__)
  {
    return parent::model($className);
  }

  /**
   * @return string the associated database table name
   */
  public function tableName()
  {
    return 'message';
  }

  /**
   * @return array validation rules for model attributes.
   */
  public function rules()
  {
    // NOTE: you should only define rules for those attributes that
    // will receive user inputs.
    return array(
      array('id, student_id, subject, body', 'required'),
      array('id, student_id', 'numerical', 'integerOnly'=>true),
      array('subject', 'length', 'max'=>255),
      array('body', 'safe'),
      array('html', 'safe'),
      array('confirmed_at, sent_at, acknowledged_at', 'safe'),
      // The following rule is used by search().
      // Please remove those attributes that should not be searched.
      array('id, student_id, subject, body, html, confirmed_at, sent_at, acknowledged_at', 'safe', 'on'=>'search'),
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
      'student' => array(self::BELONGS_TO, 'Student', 'student_id'),
    );
  }

  /**
   * @return array customized attribute labels (name=>label)
   */
  public function attributeLabels()
  {
    return array(
      'id' => 'ID',
      'student_id' => 'Student',
      'subject' => 'Subject',
      'body' => 'Body (plaintext)',
      'html' => 'Body (html)',
      'confirmed_at' => 'Date Confirmed',
      'sent_at' => 'Date Sent',
      'acknowledged_at' => 'Date Acknowledged',
    );
  }

  /**
   * Retrieves a list of models based on the current search/filter conditions.
   * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
   */
  public function search()
  {
    // Warning: Please modify the following code to remove attributes that
    // should not be searched.

    $criteria=new CDbCriteria;

    $criteria->compare('id',$this->id);
    $criteria->compare('student_id',$this->student_id);
    $criteria->compare('subject',$this->subject,true);
    $criteria->compare('body',$this->body,true);
    $criteria->compare('html',$this->html,true);
    $criteria->compare('confirmed_at',$this->confirmed_at,true);
    $criteria->compare('sent_at',$this->sent_at,true);
    $criteria->compare('acknowledged_at',$this->acknowledged_at,true);


    // Not a good separation of concerns in MVC here...
    // Must think of something better...
    if(Yii::app()->user->getState('listall')!='true')
    {
      $criteria->mergeWith(array(
          'condition'=>'sent_at IS NULL',
      ));
    }

    return new CActiveDataProvider($this, array(
      'criteria'=>$criteria,
      'sort'=>array(
        'defaultOrder'=>'id DESC',
        ),
      'pagination'=>array('pageSize'=>50),
    ));
  }
  
  public function getAcknowledgedDescription()
  {
    return $this->acknowledged_at === '0000-00-00 00:00:00' ? 'not required' : $this->acknowledged_at;
  }
  
  public function sendFirstQueued()
  {
    $message = self::model()->firstQueued();
    
    if(!$message->student->email)
    {
      return 0;
    }
    
    // acknowledged_at is null for not yet acknowledged messages
    // and it is set to 0 for messages that do not need to be acknowledged

    if(Mailer::mail(
      array($message->student->email => $message->student->__toString()),
      $message->subject,
      $message->body,
      $message->html,
      array(
        'ack' => (is_null($message->acknowledged_at) ? array('message_id'=>$message->id) : false),
        'message_id' => $message->id,
        )
      ))
    {
      $message->sent_at=date('Y-m-d H:i:s');
      $message->save(false);
      return 1;
    }
    else
    {
      return 0;
    }
    
  }
  
  public function firstQueued()
  {
    $criteria = new CDbCriteria();
    $criteria->condition = 'confirmed_at IS NOT NULL and confirmed_at < NOW() and sent_at IS NULL';
    $criteria->order = 'confirmed_at ASC';
    return self::model()->find($criteria);
  }
  
  public function findAllConfirmedToBeSent()
  {
    $criteria = new CDbCriteria();
    $criteria->condition = 'confirmed_at IS NOT NULL and sent_at IS NULL';
    $criteria->order = 'confirmed_at ASC';
    return self::model()->findAll($criteria);
  }
  
  public static function acknowledge($k, $explicit=true)
  {
    if($message=Message::model()->findByPK(Helpers::idFromAckKey($k)))
    {
      $dirty = false;
      if($explicit)
      {
        if(!$message->acknowledged_at)
        {
          $message->acknowledged_at = date('Y-m-d H:i:s');
          $dirty = true;
        }
      }
      else
      {
        if(!$message->seen_at)
        {
          $message->seen_at = date('Y-m-d H:i:s');
          $dirty = true;
        }
      }
      if($dirty)
      { 
        $message->save();
      }
      return true;
    }
    
    return false;
  }

  
}
