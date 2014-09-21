<?php
/**
 * Mailer class file.
 *
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 * @author Loris Tissino <loris.tissino@gmail.com>
 * @copyright Copyright &copy; 2014 Loris Tissino
 * @since 1.4.2
 * 
 * @package application.components
 * 
 */
class Mailer
{
  public static function b64($text)
  {
    return (quoted_printable_encode($text)==$text) ? $text : '=?UTF-8?B?'.base64_encode($text).'?=';
  }
  
  public static function mail($to, $subject, $plaintext_body, $html_body='', $options=array())
  {
    
    if(!Helpers::getYiiParam('sendEmails'))
    {
      return true; // we won't send emails if it is not enabled, but we won't fail!
    }
    
    $from=Helpers::getOption('from', $options, Helpers::getYiiParam('botEmail'));
    if($from==Helpers::getYiiParam('botEmail'))
    {
      $sender = Yii::app()->name;
    }
    else
    {
      $sender = $from;
    }
    
    $replyto=Helpers::getOption('replyto', $options, $from);
    $title=$subject;
    $subject=self::b64($subject);
    
    if(is_array($to))
    {
      // we expect an array to be like array('some@example.com'=>'John Doe')
      $address=array_pop(array_keys($to));
      $name=$to[$address];
    }
    else
    {
      $name=$address=$to;
    }
    
    $addressee=self::b64($name) ." <" . $address .">";
    
    $headers=array(
       'From: ' . self::b64($sender) . " <{$from}>",
       "Reply-To: {$replyto}",
       'MIME-Version: 1.0',
       "X-SWU-Version: " . SWU::RELEASE
    );
    
    foreach(array(
      'message_id'=>'Message',
      'originating_IP'=>'Originating-IP', // in case X-Originating-IP is not set from the server
      ) as $key=>$value)
    {
      if($info = Helpers::getOption($key, $options, false))
      {
        $headers[] = 'X-SWU-' . $value . ': ' . $info;
      }
    }

    $ack = Helpers::getOption('ack', $options, false);
    $ack_link = $ack ? Yii::app()->getController()->createAbsoluteSslUrl('message/ack', array('k'=>Helpers::generateAckKey($ack['message_id']))) : false;
    $ack_image = Yii::app()->getController()->createAbsoluteSslUrl('site/image', array('name'=>'swu', 'k'=>Helpers::generateAckKey(Helpers::getOption('message_id', $options))));
    
    $htt = new MailTemplate();
    $htt->findTemplateAndMakeReplacements('standard_head', array(
      'title' => $subject,
      'ack_link' => $ack_link,
      'ack_image' => $ack_image,
      ));
    list($plaintext_head, $html_head) = array($htt->getSubtemplateField('plaintext_body'), $htt->getSubtemplateField('html_body'));
    
    $htt->findTemplateAndMakeReplacements('standard_tail', array(
      'ack_link' => $ack_link,
      'ack_image' => $ack_image,
      ));
    list($plaintext_tail, $html_tail) = array($htt->getSubtemplateField('plaintext_body'), $htt->getSubtemplateField('html_body'));
      
    if ($html_body)
    {
      //create a boundary string. It must be unique 
      //so we use the MD5 algorithm to generate a random hash 
      $random_hash = md5(date('r', time())); 
      //add boundary string and mime type specification 
      $headers[] = "Content-Type: multipart/mixed; boundary=\"PHP-mixed-{$random_hash}\"";
      
      $html = quoted_printable_encode($html_head . $html_body . $html_tail);
      
    }
    
    $plaintext_body=quoted_printable_encode($plaintext_body);
    
    
    if ($html_body)
    {
       $body = <<<EOT
--PHP-mixed-$random_hash  
Content-Type: multipart/alternative; boundary="PHP-alt-$random_hash" 

--PHP-alt-$random_hash  
Content-Type: text/plain; charset="UTF-8" 
Content-Transfer-Encoding: quoted-printable

$plaintext_body

--PHP-alt-$random_hash  
Content-Type: text/html; charset="UTF-8" 
Content-Transfer-Encoding: quoted-printable

$html 

EOT;

    }
    else
    {
      $headers[] = "Content-type: text/plain; charset=UTF-8";
      $headers[] = "Content-Transfer-Encoding: quoted-printable";
      $body = $plaintext_body;
    }
    
    Yii::log('sending message to ' . $addressee, 'info', 'components.Mailer');
    
    try {
       if(mail($addressee,$subject,$body,implode("\r\n", array_merge($headers, Helpers::getYiiParam('customMailHeaders')))."\r\n"))
       {
         return true;
       }
    }
    catch (Exception $e)
    {
      Yii::log('failed sending message to ' . $addressee . ' ' . $e->getMessage(), 'error', 'components.Mailer');
      return false;
    }
    return false;
  }

}
