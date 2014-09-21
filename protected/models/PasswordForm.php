<?php
/**
 * PasswordForm class file.
 *
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 * @author Loris Tissino <loris.tissino@gmail.com>
 * @copyright Copyright &copy; 2014 Loris Tissino
 * @since 1.7.5
 */
/**
 * PasswordForm class.
 * PasswordForm is the data structure for allowing the user to generate 
 * a new password and crypt it
 * 
 * @package application.forms
 * 
 */
class PasswordForm extends CFormModel {
  public $cost;
  public $password;
  
  /**
   * Declares the validation rules.
   */
  public function rules()
  {
    return array(
      // password is required
      array('cost, password', 'required'),
      array('cost', 'numerical', 'integerOnly'=>true, 'min'=>2, 'max'=>20),
      // password must be of min 8 chars
      array('password', 'length', 'min'=>8),
    );
  }
}
