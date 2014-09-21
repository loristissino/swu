<?php
/**
 * File class file.
 *
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 * @author Loris Tissino <loris.tissino@gmail.com>
 * @copyright Copyright &copy; 2014 Loris Tissino
 * @since 1.0
 */
/**
 * This is the model class for table "file".
 *
 * The followings are the available columns in table 'file':
 * @property integer $id
 * @property string $original_name
 * @property string $md5
 * @property string $size
 * @property string $type
 * @property string $uploaded_at
 * @property integer $exercise_id
 * @property string $comment
 * @property string $url
 * @property string $content
 * @property string $checked_at
 * @property string $published_at
 *
 * The followings are the available model relations:
 * @property Exercise $exercise
 * 
 * @package application.models
 * 
 */
class File extends CActiveRecord
{
  /**
   * Returns the static model of the specified AR class.
   * @param string $className active record class name.
   * @return File the static model class
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
    return 'file';
  }

  /**
   * @return array validation rules for model attributes.
   */
  public function rules()
  {
    // NOTE: you should only define rules for those attributes that
    // will receive user inputs.
    return array(
      array('uploaded_at, exercise_id', 'required'),
      array('exercise_id', 'numerical', 'integerOnly'=>true),
      array('original_name, url', 'length', 'max'=>255),
      array('md5, type', 'length', 'max'=>255),
      array('size', 'length', 'max'=>20),
      array('comment', 'safe'),
      array('content', 'safe'),
      array('checked_at, published_at', 'safe'),
      // The following rule is used by search().
      // Please remove those attributes that should not be searched.
      array('id, original_name, md5, size, type, uploaded_at, exercise_id, comment, content, url, checked_at, published_at', 'safe', 'on'=>'search'),
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
      'exercise' => array(self::BELONGS_TO, 'Exercise', 'exercise_id'),
    );
  }

  /**
   * @return array customized attribute labels (name=>label)
   */
  public function attributeLabels()
  {
    return array(
      'id' => 'ID',
      'original_name' => 'Original Name',
      'md5' => 'Md5',
      'size' => 'Size',
      'type' => 'Type',
      'uploaded_at' => 'Uploaded',
      'exercise_id' => 'Exercise',
      'comment' => 'Comment',
      'url' => 'URL',
      'content' => 'Content',
      'checked_at' => 'Checked',
      'published_at' => 'Published',
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
    $criteria->compare('original_name',$this->original_name,true);
    $criteria->compare('md5',$this->md5,true);
    $criteria->compare('size',$this->size,true);
    $criteria->compare('type',$this->type,true);
    $criteria->compare('uploaded_at',$this->uploaded_at,true);
    $criteria->compare('exercise_id',$this->exercise_id);
    $criteria->compare('comment',$this->comment,true);
    $criteria->compare('url',$this->url,true);
    $criteria->compare('content',$this->content,true);
    $criteria->compare('checked_at',$this->checked_at,true);
    $criteria->compare('published_at',$this->published_at,true);

    return new CActiveDataProvider($this, array(
      'criteria'=>$criteria,
      'sort'=>array(
        'defaultOrder'=>'uploaded_at ASC',
        ),
      'pagination'=>array('pageSize'=>100),
    ));
  }
  
  public function getFile($path=null)
  {
    $filename=$this->id . '_' . $this->md5;
    if($path)
    {
      $filename = $path . DIRECTORY_SEPARATOR . $filename;
    }
    return $filename;
  }
  
  public function getUploadedAt()
  {
    return date('Ymd-His', strtotime($this->uploaded_at));
  }
  
  public function hasBeenChecked()
  {
    return 0!=$this->checked_at;
  }
  
  public function isTardy()
  {
    return $this->uploaded_at > $this->exercise->duedate;
  }
  
  public function markAsChecked($now=true)
  {
    $this->checked_at= $now ? date('Y-m-d H:i:s') : $this->uploaded_at;
    $this->save();
  }
    
}
