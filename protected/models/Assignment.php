<?php
/**
 * Assignment class file.
 *
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 * @author Loris Tissino <loris.tissino@gmail.com>
 * @copyright Copyright &copy; 2014 Loris Tissino
 * @since 1.0
 */
/**
 * Assignment represent an assignment.
 *
 * The followings are the available columns in table 'assignment':
 * @property integer $id
 * @property string $subject
 * @property string $title
 * @property string $description
 * @property integer $weight
 * @property string $url
 * @property string $duedate
 * @property integer $grace
 * @property string $checklist
 * @property string $language
 * @property integer $status
 * @property integer $notification
 * @property string $shown_since
 *
 * The followings are the available model relations:
 * @property Exercise[] $exercises
 * 
 * @package application.models
 * 
 */
class Assignment extends CActiveRecord
{
  private $_stored_duedate; // used to update exercises
  
  const STATUS_PRIVATE        = 1;
  const STATUS_PUBLIC         = 2;

  /**
   * Returns the static model of the specified AR class.
   * @param string $className active record class name.
   * @return Assignment the static model class
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
    return 'assignment';
  }

  /**
   * @return array validation rules for model attributes.
   */
  public function rules()
  {
    // NOTE: you should only define rules for those attributes that
    // will receive user inputs.
    return array(
      array('subject, title, language', 'required'),
      array('subject, title', 'length', 'max'=>64),
      array('weight', 'numerical', 'min'=>0),
      array('grace', 'numerical', 'min'=>0),
      array('language', 'length', 'min'=>2),
      array('url', 'length', 'max'=>128),
      array('duedate', 'CDateValidator', 'format'=>'yyyy-MM-dd HH:mm:ss'),
      array('shown_since', 'CDateValidator', 'format'=>'yyyy-MM-dd HH:mm:ss'),
      array('description, duedate, checklist, status, notification, shown_since', 'safe'),
      // The following rule is used by search().
      // Please remove those attributes that should not be searched.
      array('id, subject, title, description, url, duedate, weight, grace, checklist, language, status, notification, shown_since', 'safe', 'on'=>'search'),
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
      'exercises' => array(self::HAS_MANY, 'Exercise', 'assignment_id'),
    );
  }

  public function getExercisesWithStudents()
  {
    // we redefine exercises in order to use the with clause
    return Exercise::model()->with('student')->findAllByAttributes(array('assignment_id'=>$this->id), array('order'=>'lastname ASC, firstname ASC'));
  }
  
  public function getStudents()
  {
    $students = Yii::app()->db->createCommand()
      ->select('s.id, s.firstname, s.lastname, s.gender, s.email, s.roster')
      ->from('{{student}} s')
      ->leftJoin('{{exercise}} e', 's.id = e.student_id')
      ->leftJoin('{{assignment}} a', 'e.assignment_id = a.id')
      ->where('a.id=:id', array(':id'=>$this->id))
      ->order('s.lastname')
      ->setFetchMode(PDO::FETCH_OBJ)
      ->queryAll();

    $result = array();
    foreach($students as $item)
    {
      $student = new Student();
      Helpers::object2object($item, $student, array('id', 'firstname', 'lastname', 'gender', 'email', 'roster'));
      $result[] = $student;
    }
      
    return $result;  
    
  }

  /**
   * @return array customized attribute labels (name=>label)
   */
  public function attributeLabels()
  {
    return array(
      'id' => 'ID',
      'subject' => 'Subject',
      'title' => 'Title',
      'description' => 'Description',
      'url' => 'Url',
      'duedate' => 'Duedate',
      'weight' => 'Weight',
      'grace' => 'Grace',
      'checklist' => 'Checklist',
      'language' => 'Language',
      'status' => 'Status',
      'notification' => 'Notification',
      'shown_since' => 'Shown Since',
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
    $criteria->compare('subject',$this->subject,true);
    $criteria->compare('title',$this->title,true);
    $criteria->compare('description',$this->description,true);
    $criteria->compare('url',$this->url,true);
    $criteria->compare('duedate',$this->duedate,true);
    $criteria->compare('weight',$this->weight,true);
    $criteria->compare('grace',$this->grace,true);
    $criteria->compare('checklist',$this->checklist,true);
    $criteria->compare('language',$this->language,true);
    $criteria->compare('status',$this->status,true);
    $criteria->compare('notification',$this->notification,true);
    $criteria->compare('shown_since',$this->shown_since,true);

    return new CActiveDataProvider($this, array(
      'criteria'=>$criteria,
      'pagination'=>array('pageSize'=>100),
    ));
  }
  
  public function showable()
  {
    $this->getDbCriteria()->mergeWith(array(
        'condition'=>'t.shown_since < "' . date('Y-m-d H:i:s') . '"',
    ));
    return $this;
  }
  
  public function __toString()
  {
    return $this->title;
  }
  
  public function getPossibleStatuses()
  {
    return array(
      self::STATUS_PRIVATE        => Yii::t('swu', 'Private'),
      self::STATUS_PUBLIC         => Yii::t('swu', 'Public'),
    );
  }
  
  public function isPublic()
  {
    return self::STATUS_PUBLIC == $this->status;
  }
  
  public function isPrivate()
  {
    return self::STATUS_PRIVATE == $this->status;
  }
  
  public function getStatusDescription()
  {
    $possible=$this->getPossibleStatuses();
    return $possible[$this->status];
  }

  public function storeAttributes()
  {
    $this->_stored_duedate = $this->duedate;
  }

  public function afterSave()
  {
    $this->_updateChildren();
    return parent::afterSave();
  }
  
  private function _updateChildren()
  {
    // we want to update all exercises for this assignment that had the same duedate
    if($this->duedate!=$this->_stored_duedate)
    {
      // a parameters list where we define what to look for
      $params=array(
         ':assignment_id'=>$this->id,
         ':oldduedate'=>$this->_stored_duedate,   // the name of the parameter cannot be the same of the field used for where clause
        );
      
      Yii::app()->db->createCommand()->update(
        'exercise',   // which table we want to update
        array('duedate'=>$this->duedate),   // 
        
        array('and',      // the "where" condition we define
          'assignment_id=:assignment_id',
          'duedate=:oldduedate',
          ),
        $params
        );
    }
  }
  
  public function generateMessages()
  {
    $count = 0;
    foreach($this->getExercisesWithStudents() as $exercise)
    {
      // we could find the exercises with comments or marks with a query
      $count += $exercise->generateMessage($this->title);
    }
    return $count;
  }
  
  public function generateInvitations()
  {
    $count = 0;
    foreach($this->getExercisesWithStudents() as $exercise)
    {
      $count += $exercise->generateInvitation();
    }
    return $count;
  }
  
  public function createExercises($ids)
  {
    $result=array('added'=>array(), 'found'=>array());
    
    foreach($ids as $id)
    {
      
      $exercise = new Exercise;
      $exercise->assignment_id = $this->id;
      $exercise->student_id = $id;
      $exercise->duedate = $this->duedate;   // it can be customized later for each student separately
      $exercise->status = Exercise::STATUS_ASSIGNED;
      
      $done = false;
      while(!$done)
      {
        $s = sprintf('%05d%04d', floor(rand(10000, 99999)), $this->id);
        // first, we generate a string with a 5-digit random value and a 4-digit id
        $s = sprintf('%s-%s-%s', 
          $s[0].$s[5].$s[1], 
          $s[6].$s[2].$s[7], 
          $s[3].$s[8].$s[4]
          );
        // second, we split the 9 digits in 3 pieces, mixing them up and adding two minus signs
        
        $exercise->code = $s;
        try
        {
          $exercise->save();
          $result['added'][]=$id;
          $done = true;
        }
        catch (Exception $e)
        {
          // we can fail for one of these two reasons:
          
          // 1: the exercise is already there
          if (strpos($e->getMessage(), "key 'assignment_student'")!== false)
          {
            $result['found'][]=$id;
            $done = true;
          }
          
          // 2: we generated the same code twice
          // we don't need to do anything, since we are in a loop and the code will get regenerated
          
        }
      }   // end while
      
    } // end foreach
    return $result;
    
  }

  public function removeExercises($ids)
  {
    $result=array('removed'=>array(), 'left'=>array());
    
    $exercises = Exercise::model()->ofAssignment($this->id)->forStudents($ids)->with('files')->findAll();
    
    foreach($exercises as $exercise)
    {
      $id = $exercise->id;
      if($exercise && (sizeof($exercise->files)>0))
      {
        $result['left'][]=$id;
        continue;
        // we won't delete exercises when files are uploaded
      }
      try
      {
        $exercise->delete();
        $result['removed'][]=$id;
      }
      catch (Exception $e)
      {
        $result['left'][]=$id;  // this should never happen
      }
    }
    
    return $result;
    
  }



  
}
