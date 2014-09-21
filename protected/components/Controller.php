<?php
/**
 * Controller class file.
 *
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 * @author Loris Tissino <loris.tissino@gmail.com>
 * @copyright Copyright &copy; 2014 Loris Tissino
 * @since 1.0
 */
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 * 
 * @package application.components
 * 
 */
class Controller extends CController
{
  /**
   * @var string the default layout for the controller view. Defaults to '//layouts/column1',
   * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
   */
  public $layout='//layouts/column1';
  /**
   * @var array context menu items. This property will be assigned to {@link CMenu::items}.
   */
  public $menu=array();
  /**
   * @var array the breadcrumbs of the current page. The value of this property will
   * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
   * for more details on how to specify this property.
   */
  public $breadcrumbs=array();
  
  public function filterHttps( $filterChain ) {
        $filter = new HttpsFilter;
        $filter->filter( $filterChain );
    }
  
  public function filters()
  {
    return array(
      'https', // Force https for every page
    );
  }
  
  public function redirect($url,$terminate=true,$statusCode=302)
  {
    // we must take care of altervista's redirection
    if(is_array($url))
    {
        $route=isset($url[0]) ? $url[0] : '';
        $url=$this->createAbsoluteSslUrl($route,array_splice($url,1));
    }
    else
    {
        if(!in_array(substr($url,0,7), array('http://', 'https:/')))
        {
          $redirection = Helpers::getYiiParam('redirection_url_replace');
          $url = $redirection['to'] . $url;
        }
    }
    Yii::app()->getRequest()->redirect($url,$terminate,$statusCode);
  }
  
  public function createAbsoluteSslUrl($route, $params=array(), $schema='', $ampersand='&')
  {
    return $this->_replaceSchemaAndHost($this->createAbsoluteUrl($route, $params, $schema, $ampersand));
  }
  
  private function _replaceSchemaAndHost($url)
  {
    $redirection = Helpers::getYiiParam('redirection_url_replace');
    return str_replace($redirection['from'], $redirection['to'], $url);
  }

  public function setAllFlashes($flashes)
  {
    foreach(array('success', 'error') as $type)
    {
      if(isset($flashes[$type]) and sizeof($flashes[$type]))
      {
        Yii::app()->getUser()->setFlash('flash-'.$type, implode('<br />', $flashes[$type]));
      }
    }
  }
  
  public function createIcon($name, $alt='', $htmlOptions=array(), $extension='.png')
  {
    // some defaults
    foreach(array(
      'title'=>$alt,
      'width'=>16,
      'height'=>16,
    ) as $key=>$value)
    {
      if(!isset($htmlOptions[$key]))
      {
        $htmlOptions[$key]=$value;
      }
    }
    return CHtml::image(Yii::app()->request->baseUrl.'/images/' . $name . $extension, $alt, $htmlOptions);
  }
  /*
  public function renderAssignment(File $file, $row)
  {
    return $this->renderPartial('../exercise/_info', array('exercise'=>$file->exercise),true);
  }
  
  public function renderStudent(File $file, $row)
  {
    return $this->renderPartial('../student/_info', array('exercise'=>$file->exercise),true);
  }
  */
  public function renderFileContent(File $file, $row)
  {
    return $this->renderPartial('../file/_content', array('file'=>$file, 'row'=>$row),true);
  }
  
  public function renderPartialIfAvailable($view, $data=NULL, $return=false, $processOutput=false)
  {
    if ($this->getViewFile($view))
    {
      return $this->renderPartial($view, $data, $return, $processOutput);
    }
  }
  
    /**
   * Sends a Content-Disposition HTTP header.
   * @param string $filename the filename being sent
   */  
  public function sendDispositionHeader($filename)
  {
    header(sprintf('Content-Disposition: attachment; filename="%s"', $filename));
  }  

  /**
   * Serves a file via HTTP.
   * @param string $type the Internet Media Type (MIME) of the file
   * @param string $file the file to send
   */  
  public function serveFile($type, $file)
  {
    $this->_serve($type, $file, true);
  }
  
  /**
   * Serves something via HTTP.
   * @param string $type the Internet Media Type (MIME) of the content
   * @param string $content the content to send
   * @param boolean $is_file whether the content is a file
   */  
  private function _serve($type, $content, $is_file=false)
  {
    header("Content-Type: " . $type);
    if ($is_file)
    {
      readfile($content);
    }
    else
    {
      echo $content;
    }
    Yii::app()->end();
  }

  
}
