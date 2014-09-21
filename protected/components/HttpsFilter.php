<?php
/**
 * HttpsFilter class file.
 *
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 * @author Loris Tissino <loris.tissino@gmail.com>
 * @copyright Copyright &copy; 2014 Loris Tissino
 * @since 1.4.1
 */
/**
 * A customized filter class, that checks whether there is an active HTTPS connection.
 * Based on an example available at see http://www.jaburo.net/?p=40
 *
 * @package application.components
 * 
 */

class HttpsFilter extends CFilter {
    protected function preFilter( $filterChain ) {
      
      if(!Helpers::getYiiParam('sslServerName'))
      {
        return true;
      }
      if(!isset($_SERVER['HTTP_X_FORWARDED_HOST']) || (isset($_SERVER['HTTP_X_FORWARDED_HOST']) && $_SERVER['HTTP_X_FORWARDED_HOST']!=Helpers::getYiiParam('sslServerName')))
      {
/*
 *      if ( !Yii::app()->getRequest()->isSecureConnection ) {
 *      This was the original check -- but it doesn't work with altervista...
 */
        # Redirect to the secure version of the page.
        $url = 'https://' .
        Helpers::getYiiParam('sslServerName') .
        Yii::app()->getRequest()->requestUri;

        // die("Not a secure connection, redirecting to: " . $url);

        Yii::app()->request->redirect($url);
            
            
        return false;
      }
      return true;
    }
}
