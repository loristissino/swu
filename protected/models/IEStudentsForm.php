<?php
/**
 * IEStudentsForm class file.
 *
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 * @author Loris Tissino <loris.tissino@gmail.com>
 * @copyright Copyright &copy; 2014 Loris Tissino
 * @since 1.8.14
 */
 
/** IEStudentsForm class.
 * IEStudentsForm is the data structure for keeping
 * import/export students form data. It is used by the 'import' and 'export' actions of 'StudentController'.
 * 
 * @package application.forms
 * 
 */
class IEStudentsForm extends CFormModel
{
  
  /** 
   * @var string $content represents the content of the text area 
   */
  public $content;

  /**
   * Declares the validation rules.
   */
  public function rules()
  {
    return array(
      // content is required
      array('content', 'required'),
    );
  }

  /**
   * Declares customized attribute labels.
   * If not declared here, an attribute would have a label that is
   * the same as its name with the first letter in upper case.
   */
  public function attributeLabels()
  {
    return array(
      'content'=>Yii::t('swu', 'Content'),
    );
  }
  
  public function loadStudents()
  {
    $this->content = '';
    // not yet implemented
  }
}
