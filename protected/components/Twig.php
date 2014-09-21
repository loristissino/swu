<?php
/**
 * Twig class file.
 *
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 * @author Loris Tissino <loris.tissino@gmail.com>
 * @copyright Copyright &copy; 2014 Loris Tissino
 * @since 1.4.2
 * 
 * @package application.components
 */
require_once dirname(__FILE__).'/../../protected/vendor/autoload.php';

class Twig
{
  private $_loader;
  private $_twig;
  
  public function __construct()
  {
    $this->_loader = new Twig_Loader_String();
    $this->_twig = new Twig_Environment($this->_loader);
  }
  
  public function render($text, $replacements)
  {
    return $this->_twig->render($text, $replacements);
  }
}

