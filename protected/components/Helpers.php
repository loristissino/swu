<?php
/**
 * Helpers class file.
 *
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 * @author Loris Tissino <loris.tissino@gmail.com>
 * @copyright Copyright &copy; 2013 Loris Tissino
 * @since 1.0
 * 
 * @package application.components
 * 
 */
class Helpers {

  public static function backupDb($filepath=null, $tables = '*') {
    // see http://www.yiiframework.com/forum/index.php/topic/29291-full-database-backup/
    if ($tables == '*') {
        $tables = array();
        $tables = Yii::app()->db->schema->getTableNames();
    } else {
        $tables = is_array($tables) ? $tables : explode(',', $tables);
    }
    $return = '';

    foreach ($tables as $table) {
        $result = Yii::app()->db->createCommand('SELECT * FROM ' . $table)->query();
        $return.= 'DROP TABLE IF EXISTS ' . $table . ';';
        $row2 = Yii::app()->db->createCommand('SHOW CREATE TABLE ' . $table)->queryRow();
        $return.= "\n\n" . $row2['Create Table'] . ";\n\n";
        foreach ($result as $row) {
            $return.= 'INSERT INTO ' . $table . ' VALUES(';
            foreach ($row as $data) {
                $data = addslashes($data);

                // Updated to preg_replace to suit PHP5.3 +
                $data = preg_replace("/\n/", "\\n", $data);
                if (isset($data)) {
                    $return.= '"' . $data . '"';
                } else {
                    $return.= '""';
                }
                $return.= ',';
            }
            $return = substr($return, 0, strlen($return) - 1);
            $return.= ");\n";
        }
        $return.="\n\n\n";
    }
    //save file
    if(is_null($filepath)){
      return $return;
      }
    
    $handle = fopen($filepath, 'w+');
    fwrite($handle, $return);
    fclose($handle);
  }

  public static function SimpleSubmitButton($name, $value='', $action=''){
    if(!$value) $value=$name;
    
    $html='<form method="post"';
    if($action)
    {
      $html.=' action="' . $action .'"';
    }
    $html.='>';
    
    $html .= CHtml::submitButton($name, array('value'=>$value));
    
    $html .= '</form>';
    
    return $html;
  }
  
  public static function getOption($key, $options, $default='')
  {
    return isset($options[$key])?$options[$key]:$default;
  }
  
  public static function getYiiParam($key, $default='')
  {
    
    return self::getOption($key, array_merge(array(
    
      // this is a list of defaults that are used if the requested key is not set in the config file
      'adminEmail'=>'admin@example.com',
      'botEmail'=>'bot@example.com',
      'siteUrl'=>'www.example.com',
      'sslServerName'=>false,
      'tagline'=>'Copyright &copy; ' . date('Y') . ' for contents by ... and his/her students.',
      'adminPassword'=>'$2a$08$YfUCHCkxvBDk7PD4Pj4Xyu7denkdBhUWAgcsgX4KtgXluDp2x5Uhu',  // "NotSoSecret"
      'redirection_url_replace' => array('from'=>'', 'to'=>''),
      'uploadDirectory'=>'data/myFilesDir',
      'uploadMaxSize'=>0, // KiB
      'checkURL'=>false,
      'sendEmails'=>true,
      'key'=>'denkdBhUWA',  // used to generate hash values to increase security
      'customMailHeaders' => array(
          // 'Bcc: Backup <backup@example.com>',
        ),
      'addOriginatingIP' => false,
      'defaultGrace' => 10,
      'defaultAssignmentStatus' => Assignment::STATUS_PRIVATE,
      
      'labelsStyle' => <<<EOT
/* based on http://boulderinformationservices.wordpress.com/2011/08/25/print-avery-labels-using-css-and-html/ */
@page{
  margin: 7mm 0px 0px 0px;
}

body {
    width: 210mm;
    margin: 0mm;
    }
    
.label{
    width: 70mm;
    height: 29mm;
    padding: 0mm;
    margin-right: 0mm;

    float: left;

    text-align: center;
    overflow: hidden;

    outline: 1px dotted; /* outline doesn't occupy space like border does */
    }
    
.page-break  {
    clear: left;
    display:block;
    page-break-after:always;
    }
    
.label span.student {
  font-weight: bold;
  font-variant: small-caps
}

.label span.assignment {
  font-style: italic;
}

.label span.url {
  font-style: normal;
  text-decoration: underline
}
EOT
,       // end of defaults
        
      ), Yii::app()->params->toArray()), $default);
  }
  
  public static function generateAckKey($id)
  {
    // string replacement is here to avoid percent encoding -- see http://en.wikipedia.org/wiki/Percent-encoding#Types_of_URI_characters
    return str_replace(array('/', '='), array('_', '-'), base64_encode(json_encode(array('id'=>$id, 'hash'=>self::hash($id)))));
  }
  
  public static function idFromAckKey($key)
  {
    $v=json_decode(base64_decode(str_replace(array('_', '-'), array('/', '='), $key)));
    
    if(!is_object($v))
      return false;
      
    if(self::hash($v->id)!=$v->hash)
      return false;
    
    return $v->id;
  }
  
  public static function hash($message_id, $length=6)
  {
    return substr(md5($message_id . Helpers::getYiiParam('key')),0, $length);
  }

  public static function encrypt($string)
  {
    $key = Helpers::getYiiParam('key');
    return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $string, MCRYPT_MODE_CBC, md5(sha1($key))));
  }

  public static function decrypt($string)
  {
    $key = Helpers::getYiiParam('key');
    return rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($encrypted), MCRYPT_MODE_CBC, md5(sha1($key))), "\0");
  }

  public static function object2array($object, &$array, $properties=array())
  {
    foreach ($properties as $property)
    {
      $array[$property] = $object->$property;
    }
  }

  public static function array2object($array, $object, $properties=array())
  {
    foreach ($properties as $property)
    {
      if(isset($array[$property]))
      {
        $object->$property = $array[$property];
      }
    }
  }
  
  public static function object2object($source, $target, $properties=array())
  {
    foreach ($properties as $property)
    {
      $target->$property = $source->$property;
    }
  }

  public static function normalizeCode(&$code)
  {
    if(strlen($code)==9)
    {
      // we add the missing minus signs
      $code = implode('-', array(substr($code, 0, 3), substr($code, 3, 3), substr($code, 6, 3)));
    }
  }

  public static function getHostName()
  {
    if($name = self::getYiiParam('sslServerName'))
    {
      return $name;
    }
    return 'http://' . Helpers::getYiiParam('siteUrl');
  }


}
