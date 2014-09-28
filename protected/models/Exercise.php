<?php
/**
 * Exercise class file.
 *
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 * @author Loris Tissino <loris.tissino@gmail.com>
 * @copyright Copyright &copy; 2014 Loris Tissino
 * @since 1.0
 */
/**
 * This is the model class for table "exercise".
 *
 * The followings are the available columns in table 'exercise':
 * @property integer $id
 * @property integer $assignment_id
 * @property integer $student_id
 * @property integer $linked_to
 * @property string $code
 * @property string $duedate
 * @property integer $status
 * @property string $mark
 * @property string $comment
 *
 * The followings are the available model relations:
 * @property Assignment $assignment
 * @property Student $student
 * @property Exercise $linkedToExercise
 * @property Exercise[] $linkedExercises
 * @property File[] $files
 * 
 * @package application.models
 * 
 */
class Exercise extends CActiveRecord
{
  public $file;
  public $generate_message;
  public $link;
  
  const STATUS_ASSIGNED        = 10;
  const STATUS_NOTIFIED        = 20;
  const STATUS_ACKNOWLEDGED    = 30;
  const STATUS_WORK_UPLOADED   = 40;
  const STATUS_WORK_INCOMPLETE = 50;
  const STATUS_WORK_IMPROVABLE = 60;
  const STATUS_WORK_COMPLETED  = 70;
  const STATUS_WORK_EVALUATED  = 80;
  const STATUS_MARK_COPIED     = 90;
  
  /**
   * Returns the static model of the specified AR class.
   * @param string $className active record class name.
   * @return Exercise the static model class
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
    return 'exercise';
  }

  /**
   * @return array validation rules for model attributes.
   */
  public function rules()
  {
    // NOTE: you should only define rules for those attributes that
    // will receive user inputs.
    return array(
      array('assignment_id, student_id, code', 'required'),
      array('assignment_id, student_id, linked_to', 'numerical', 'integerOnly'=>true),
      array('code', 'length', 'max'=>16),
      array('status', 'ArrayValidator', 'values'=>$this->getPossibleStatuses(), 'message'=>'You must select a valid status'),
      array('mark', 'length', 'max'=>30),
      array('duedate', 'CDateValidator', 'format'=>'yyyy-MM-dd HH:mm:ss'),
      array('linked_to', 'exist', 
        'allowEmpty'=>true,
        'attributeName'=>'id',
        'className'=>'Exercise',
        'criteria'=>array(
          'condition'=>'assignment_id=:assignment_id',
          'params'=>array(':assignment_id'=>$this->assignment_id)
          )
      ),
      array('comment, file, duedate', 'safe'),
      array('generate_message', 'safe'),
      // The following rule is used by search().
      // Please remove those attributes that should not be searched.
      array('id, assignment_id, student_id, code, mark, comment, linked_to', 'safe', 'on'=>'search'),
    );
  }
  
  public function __toString()
  {
    return Yii::t('swu', 'Exercise assigned to %name% (%number%)', array('%number%'=>$this->id, '%name%'=>$this->student->name));
  }

  public function getPossibleStatuses($from = 0)
  {
    $statuses = array(
      self::STATUS_ASSIGNED        => Yii::t('swu', 'Exercise assigned'),
      self::STATUS_NOTIFIED        => Yii::t('swu', 'Assignment notified'),
      self::STATUS_ACKNOWLEDGED    => Yii::t('swu', 'Notification aknowledged'),
      self::STATUS_WORK_UPLOADED   => Yii::t('swu', 'Uploaded'),
      self::STATUS_WORK_INCOMPLETE => Yii::t('swu', 'Incomplete'),
      self::STATUS_WORK_IMPROVABLE => Yii::t('swu', 'Improvable'),
      self::STATUS_WORK_COMPLETED  => Yii::t('swu', 'Complete'),
      self::STATUS_WORK_EVALUATED  => Yii::t('swu', 'Temporary mark assigned'),
      self::STATUS_MARK_COPIED     => Yii::t('swu', 'Official mark assigned'),    
    );
    
    if($from)
    {
      return array_flip(array_filter(array_flip($statuses), function($v) use ($from) { return $v >= $from; }));
    }
    return $statuses;
  }
  
  
  public function getStatusDescription($as_workflow_string=false)
  {
    $possible_statuses = $this->getPossibleStatuses();
    
    $status = $this->linked_to ? $this->linkedToExercise->status : $this->status;
    
    $description = $possible_statuses[$status];
    
    if(!$as_workflow_string)
    {
      return $description;
    }
    
    $result = array();
    foreach($possible_statuses as $k=>$v)
    {
      $result[] = sprintf('<span class="status %s" title="%s">%s</span>', ($k==$status ? 'on' : 'off'), $description, ($k==$status ? '&#9745;' : '&#9744;'));
    }
    return implode('', $result);
    
  }
  
  /* not used anymore, here for reference
  public function getFilesLink()
  {
    $total = sizeof($this->files);
    $unchecked = array_reduce($this->files, function($carry, $item) {$carry+=$item->checked_at?0:1; return $carry;}, 0);
    
    $text = $total ? $total : '';
    
    if($unchecked)
    {
      $text .= ' (' . $unchecked . ')';
    }
    
    if($text)
    {
      $text = sprintf('<a class="hiddenlink" href="%s">%s</a>', htmlentities(Yii::app()->controller->createUrl('exercise/view', array('id'=>$this->id))), $text);
    }
    return $text;
  }
  */
  
  public function getFilesInformation()
  {
    $result = array('checked'=>0, 'unchecked'=>0, 'tardy'=>0, 'comments'=>array());
    foreach($this->files as $file)
    {
      if($file->hasBeenChecked())
      {
        $result['checked']++;
      }
      else
      {
        $result['unchecked']++;
      }
      if($file->isTardy())
      {
        $result['tardy']++;
      }
      if($file->comment)
      {
        $result['comments'][]=$file->comment;
      }
    }
    return $result;
  }
  
  public function isRemovable()
  {
    return $this->status < self::STATUS_WORK_UPLOADED;
  }
  
  public function getLinkedToStudentName()
  {
    return $this->linked_to ? $this->linkedToExercise->student->name : '';
  }

  /**
   * @return array relational rules.
   */
  public function relations()
  {
    // NOTE: you may need to adjust the relation name and the related
    // class name for the relations automatically generated below.
    return array(
      'assignment' => array(self::BELONGS_TO, 'Assignment', 'assignment_id'),
      'student' => array(self::BELONGS_TO, 'Student', 'student_id'),
      'linkedToExercise' => array(self::BELONGS_TO, 'Exercise', 'linked_to'),  // to which exercise this one is linked to ("parent")
      'linkedExercises' => array(self::HAS_MANY, 'Exercise', 'linked_to'),   // which are the exercises this one are linked to ("children")
      'files' => array(self::HAS_MANY, 'File', 'exercise_id'),
    );
  }

  /**
   * @return array customized attribute labels (name=>label)
   */
  public function attributeLabels()
  {
    return array(
      'id' => 'ID',
      'assignment_id' => 'Assignment',
      'student_id' => 'Student',
      'linked_to' => 'Linked To',
      'code' => 'Code',
      'duedate' => 'Due Date',
      'status' => 'Status',
      'mark' => 'Mark',
      'comment' => 'Comment',
      'generate_message' => 'Generate message',
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

    $sort = new CSort;
    $sort->defaultOrder = 'duedate DESC';

    $criteria=new CDbCriteria;

    $criteria->compare('id',$this->id);
    $criteria->compare('assignment_id',$this->assignment_id);
    $criteria->compare('student_id',$this->student_id);
    $criteria->compare('linked_to',$this->linked_to);
    $criteria->compare('code',$this->code,true);
    $criteria->compare('duedate',$this->duedate,true);
    $criteria->compare('status',$this->mark,true);
    $criteria->compare('mark',$this->mark,true);
    $criteria->compare('comment',$this->comment,true);

    return new CActiveDataProvider($this, array(
      'criteria'=>$criteria,
      'pagination'=>array('pageSize'=>50),
      'sort' => $sort,
    ));
  }
  
      
  public function forStudent($student_id, $order='assignment.duedate ASC')
  {
    $this->getDbCriteria()->mergeWith(array(
        'condition'=>'t.student_id = ' . $student_id,
        'order'=>$order,
    ));
    return $this;
  }
  
  public function forStudents($ids=array())
  {
    $this->getDbCriteria()->addInCondition('t.student_id', $ids);
    return $this;
  }

  public function ofAssignment($assignment_id)
  {
    $this->getDbCriteria()->mergeWith(array(
        'condition'=>'t.assignment_id = ' . $assignment_id,
    ));
    return $this;
  }

  public function sortByDuedate()
  {
    $this->getDbCriteria()->mergeWith(array(
        'order'=>'t.duedate DESC',
    ));
    return $this;
  }
  
  public function getLinkedExercises()
  {
    $values = array();
    foreach($this->linkedExercises as $e)
    {
      $values[]=$e->id;
    }
    return implode(', ', $values);
  }
  
  public function getColor()
  {
    switch($this->status)
    {
      case self::STATUS_WORK_INCOMPLETE:
        return 'red';
      case self::STATUS_WORK_IMPROVABLE:
        return 'yellow';
      case self::STATUS_WORK_COMPLETED:
        return 'green';
      default:
        return 'gray';
    }
  }
  
  public function getColleagues()
  {
    //FIXME -- it should be possible to let a query do all the work...
    $result=array();
    foreach($this->with('student')->findAllByAttributes(array('assignment_id'=>$this->assignment_id)) as $item)
    {
      $result[$item->id]=$item->student->name;
    }
    return $result;
  }
  
  public function generateMessage($subject='')
  {
    if(!$this->student->email)
    {
      return 0;
    }
  
    MailTemplate::model()->messageFromTemplate('evaluation_notification', $this->student->id, array(
      'student'=>$this->student,
      'assignment'=>$this->assignment,
      'exercise'=>$this,
      'link'=>Yii::app()->controller->createAbsoluteSslUrl('exercise/info', array('k'=>$this->generateAckKey())),
      ),
    false, true);

    return 1;

  }
    
  public function generateInvitation()
  {
    if(!$this->student->email)
    {
      return 0;
    }

    if (MailTemplate::model()->messageFromTemplate('new_assignment_notification', $this->student->id, array(
      'student'=>$this->student,
      'assignment'=>$this->assignment,
      'exercise'=>$this,
      'link'=>Yii::app()->controller->createAbsoluteSslUrl('exercise/info', array('k'=>$this->generateAckKey())),
      ),
    true, false))  // confirmed, acknowledgment not required
    {
      $this->increaseStatus(self::STATUS_NOTIFIED);
    }

    return 1;

  }
  
  public function increaseStatus($status)
  {
    if($status>$this->status)
    {
      $this->status = $status;
      $this->save();
    }
  }
  
  public function generateAckKey()
  {
    return Helpers::generateAckKey($this->id);
  }
  
  public function findByAckKey($infokey)
  {
    return $this->findByPK(Helpers::idFromAckKey($infokey));
  }
  
}




