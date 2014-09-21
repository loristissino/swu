<?php
/**
 * UploadForm class file.
 *
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 * @author Loris Tissino <loris.tissino@gmail.com>
 * @copyright Copyright &copy; 2014 Loris Tissino
 * @since 1.0
 */
/**
 * UploadForm class.
 * UploadForm is the data structure for allowing the user to upload / turn in his/her schoolwork.
 * 
 * @package application.forms
 */
class UploadForm extends CFormModel
{
  const URL_EXAMPLE = 'http://';
  
  public $code;
  public $uploadedfile;
  public $url;
  public $comment;
  public $content;
  public $exercise;
  public $verifyCode;
  public $honour;
   
  public function rules()
  {
    return array(
//      array('code', 'exist', 'allowEmpty' => false, 'attributeName' => 'code', 'className' => 'Exercise'),
      array('code, honour', 'required'),
      array('verifyCode', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements(), 'captchaAction' => 'site/captcha'),
      array('code', 'checkCode'),
      array('honour', 'checkHonour'),
      array('comment', 'safe'),
      array('url', 'checkUrl'),
      array('content', 'checkContent'),
      array('uploadedfile', 
          'file',
          'allowEmpty' => true,  // if it is in the list of required fields above, this must be set to true here
          'maxSize'=>1024 * Helpers::getYiiParam('uploadMaxSize'),
          'tooLarge'=>'The file was too large. Please upload a smaller file. The maximum size allowed is ' . Helpers::getYiiParam('uploadMaxSize'). ' KiB.',
      ),
    );
  }
  
  public function checkCode()
  {
    if($this->getError('verifyCode'))
    {
      return;
      // we don't provide any information about the code if the captcha is not valid
    }
    
    Helpers::normalizeCode($this->code);
    
    if($this->exercise=Exercise::model()->findByAttributes(array('code'=>trim($this->code))))
    {
      if($this->exercise->duedate < date('Y-m-d H:m:s', time()-$this->exercise->assignment->grace*24*60*60))
      {
        $this->addError('code', Yii::t('swu', 'The code provided is not valid anymore (time expired on %date%).', array('%date%'=>$this->exercise->duedate)));
        return;
      }
      
    }
    elseif($this->code)
    {
      $this->addError('code', Yii::t('swu', 'The code provided is not valid.'));
      return;
    }
  }

  public function checkHonour()
  {
    if(!$this->honour)
    {
      $this->addError('honour', Yii::t('swu', 'You must make the honour statement.'));
    }
  }

  public function checkContent()
  {
    if(!$this->url && !$this->uploadedfile && !$this->content)
    {
      $this->addError('content', Yii::t('swu', 'If you don\'t upload a file or provide an URL, you must input some text.'));
      return;
    }
    
  }


  public function checkUrl()
  {
    if($this->url==self::URL_EXAMPLE)
    {
      $this->url='';
    }
    
    if(!$this->url && !$this->uploadedfile && !$this->content)
    {
      $this->addError('url', Yii::t('swu', 'If you don\'t upload a file or input some text, you must provide a URL.'));
      return;
    }
    if($this->url)
    {
      if(Helpers::getYiiParam('checkURL'))
      {
        try
        {
          $array = @get_headers($this->url);
        }
        catch(Exception $e)
        {
          $this->addError('url', Yii::t('swu', 'The URL provided is invalid.'));
          return;
        }
        
        $string = $array[0];
        
        if(strpos($string,"200")===false)
        {
          $this->addError('url', 'The URL provided does not seem to work.');
          return;
        }
      }
      else
      {
        $valid_schemes=array('http', 'https', 'ftp');
        $scheme = @parse_url($this->url, PHP_URL_SCHEME);
        if(!in_array($scheme, $valid_schemes))
        {
          $this->addError('url', 'The URL provided does not validate. Valid schemes are: ' . implode(', ', $valid_schemes) . '.');
          return;
        }
      
      }
      
    }
  }
  
  
  /**
   * @return array customized attribute labels (name=>label)
   */
  public function attributeLabels()
  {
    return array(
      'code' => Yii::t('swu', 'Code'),
      'uploadedfile' => 'File',
      'content' => Yii::t('swu', 'Content'),
      'comment' => Yii::t('swu', 'Comment'),
      'honour' => Yii::t('swu', 'Honour Declaration'),
      'verifyCode' => Yii::t('swu', 'Verification Code'),
    );
  }
  
  public function saveData($path)
  {
    $file = new File();
    $file->exercise_id = $this->exercise->id;
    $file->comment = strip_tags($this->comment);
    $file->content = $this->content;
    $file->url = $this->url;
    $file->uploaded_at=date('Y-m-d H:i:s');
    if($this->uploadedfile instanceof CUploadedFile)
    {
      $file->original_name=$this->uploadedfile->name;
      $file->size = $this->uploadedfile->size;
      $file->type = $this->uploadedfile->type;
      $file->md5 = md5_file($this->uploadedfile->tempName);
    }
    else
    {
      $file->md5 = md5($this->url . $this->content);
    }
    
    $transaction = $file->getDbConnection()->beginTransaction();
    try
    {
      if(!$file->save())
      {
        $this->addError('file', 'An error occured during file analysis.');
        return null;
      }
      if($this->uploadedfile instanceof CUploadedFile)
      {
        $this->uploadedfile->saveAs($path . DIRECTORY_SEPARATOR . $file->id . '_' . $file->md5);
      }

      $this->exercise->linked_to = null;  
      $this->exercise->increaseStatus(Exercise::STATUS_WORK_UPLOADED);
      
      $transaction->commit();
      
      return $file;
    }
    catch(Exception $e)
    {
      $this->addError('file', 'An error occured during file storing.');
      $transaction->rollback();
      return null;
    }
  }
  
  public function setUrlExample()
  {
    $this->url = self::URL_EXAMPLE;
  }
  
  
}
