<?php
/**
 * Student class file.
 *
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 * @author Loris Tissino <loris.tissino@gmail.com>
 * @copyright Copyright &copy; 2014 Loris Tissino
 * @since 1.0
 */
/**
 * This is the model class for table "student".
 *
 * The followings are the available columns in table 'student':
 * @property integer $id
 * @property string $firstname
 * @property string $lastname
 * @property string $gender
 * @property string $email
 * @property string $roster
 *
 * The followings are the available model relations:
 * @property Exercise[] $exercises
 * @property Message[] $messages
 * 
 * 
 * @package application.models
 * 
 */
class Student extends CActiveRecord
{
  /**
   * Returns the static model of the specified AR class.
   * @param string $className active record class name.
   * @return Student the static model class
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
    return 'student';
  }

  /**
   * @return array validation rules for model attributes.
   */
  public function rules()
  {
    // NOTE: you should only define rules for those attributes that
    // will receive user inputs.
    return array(
      array('firstname, lastname, roster, gender', 'required'),
      array('firstname, lastname, roster', 'length', 'max'=>64),
      array('gender', 'ArrayValidator', 'values'=>$this->getPossibleGenders(), 'message'=>'You must select a valid gender'),
      array('email', 'length', 'max'=>128),
      // The following rule is used by search().
      // Please remove those attributes that should not be searched.
      array('id, firstname, lastname, gender, email, roster', 'safe', 'on'=>'search'),
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
      'exercises' => array(self::HAS_MANY, 'Exercise', 'student_id'),
      'messages' => array(self::HAS_MANY, 'Message', 'student_id'),
    );
  }

  /**
   * @return array customized attribute labels (name=>label)
   */
  public function attributeLabels()
  {
    return array(
      'id' => 'ID',
      'firstname' => 'Firstname',
      'lastname' => 'Lastname',
      'gender' => 'Gender',
      'email' => 'Email',
      'roster' => 'Roster',
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
    $sort->defaultOrder = 'lastname ASC';

    $criteria=new CDbCriteria;

    $criteria->compare('id',$this->id);
    $criteria->compare('firstname',$this->firstname,true);
    $criteria->compare('lastname',$this->lastname,true);
    $criteria->compare('gender',$this->gender,true);
    $criteria->compare('email',$this->email,true);
    $criteria->compare('roster',$this->roster,true);

    return new CActiveDataProvider($this, array(
      'criteria'=>$criteria,
      'pagination'=>array('pageSize'=>50),
      'sort' => $sort,
    ));
  }
  
  public function __toString()
  {
    return $this->getName();
  }
  
  public function getPossibleGenders()
  {
    return array('-'=>'unset', 'M'=>'Male', 'F'=>'Female');
  }
  
  public function getName()
  {
    return $this->firstname . ' ' . $this->lastname;
  }

  public function getMale()
  {
    return 'M'==$this->gender;
  }

  public function getFemale()
  {
    return 'F'==$this->gender;
  }
  
  public function getPartialEmail()
  {
    list($username, $domain) = explode('@', $this->email);
    $topleveldomain = substr(strrchr($domain, "."), 1);
    return sprintf('%s...@%s....%s', substr($username, 0, 2), substr($domain, 0, 1), $topleveldomain);
  }
  
  public function sortByLastname()
  {
    $this->getDbCriteria()->mergeWith(array(
        'order'=>'lastname ASC, firstname ASC',
    ));
    return $this;
  }

  public function withEmail()
  {
    $this->getDbCriteria()->mergeWith(array(
        'condition'=>'t.email <> ""',
    ));
    return $this;
  }

  public function withoutEmail()
  {
    $this->getDbCriteria()->mergeWith(array(
        'condition'=>'t.email IS NULL OR t.email =""',
    ));
    return $this;
  }
  
  public function addMessage($subject, $body)
  {
    $message = new Message();
    $message->student_id = $this->id;
    $message->subject = $subject;
    $message->body = $body;
    try
    {
      $message->save(false);
      return 1;
    }
    catch(Exception $e)
    {
      return 0;
    }
  }
  
  protected function beforeDelete()
  {
    // this could be avoided if we were sure that database has integrity constraints checked...
    if(sizeof($this->exercises)>0)
    {
      throw new Exception('Students with exercises cannot be deleted.');
    }
    return parent::beforeDelete();
  }
  
  public function getExercises()
  {
    return Exercise::model()->forStudent($this->id)->with('assignment')->findAll();
  }
  
  public function importStudentsFrom($form)
  {
    $result=array('imported'=>0, 'skipped'=>0);
    
    $lines=explode("\n", $form->content);
    
    foreach($lines as $line)
    {
      $line=chop($line);
      if($line=='')
      {
        continue;
      }
      $items=array();
      if(strpos($line, "\t")===false)
      {
        $result['skipped']++;
        continue;
        // we exclude lines with no tabs
      }
      $items=explode("\t", trim($line));
      if(sizeof($items)!=5)
      {
        $result['skipped']++;
        continue;
        // we expect lines to have 5 fields (firstname, lastname, gender, email, roster)
      }

      $student = new Student;
      
      list(
        $student->firstname, 
        $student->lastname, 
        $student->gender, 
        $student->email, 
        $student->roster
        ) = $items;
      
      $saved = false;
      try
      {
        if($student->save())
        {
          $result['imported']++;
          $saved=true;
        }
      }
      catch(Exception $e)
      {
        // we just silently ignore accounts that can't be imported
      }
      if(!$saved) $result['skipped']++;
        
      unset($student);
    }   // endforeach
    
    return $result;
    
  }
  
  
}
