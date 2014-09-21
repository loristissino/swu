<?php
/**
 * UserIdentity class file.
 *
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 * @author Loris Tissino <loris.tissino@gmail.com>
 * @copyright Copyright &copy; 2014 Loris Tissino
 * @since 1.0
 */
/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 * 
 * @package application.components
 * 
 */
class UserIdentity extends CUserIdentity
{
  /**
   * Authenticates a user.
   * The example implementation makes sure if the username and password
   * are both 'demo'.
   * In practical applications, this should be changed to authenticate
   * against some persistent user identity storage (e.g. database).
   * @return boolean whether authentication succeeds.
   */
  public function authenticate()
  {
    $users=Helpers::getYiiParam('admins');
    if(!isset($users[$this->username]))
      $this->errorCode=self::ERROR_USERNAME_INVALID;
    elseif(crypt($this->password, $users[$this->username])!=$users[$this->username])
      $this->errorCode=self::ERROR_PASSWORD_INVALID;
    else
      $this->errorCode=self::ERROR_NONE;
    return !$this->errorCode;
  }
  
  /**
   * @return string a BlowFish password string with random salt, to be stored in the db
   */
  public static function createPassword($password_given, $cost) 
  {
      
    $salt = sprintf('$2a$%02d$', $cost);
    
    $r = openssl_random_pseudo_bytes(22);
    
    for($i=0; $i<22; $i++)
    {
      $salt.=substr('./0123456789ABCDEFGHIJKLMNOPQRSTUWWXYZabcdefghijklmnopqrstuvwxyz', floor(hexdec(bin2hex($r[$i]))/4), 1);
    }
    
    return crypt($password_given, $salt);
  }
  
}
