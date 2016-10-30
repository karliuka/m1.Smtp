<?php
/**
 * Faonni
 *  
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade module to newer
 * versions in the future.
 * 
 * @package     Faonni_Smtp
 * @copyright   Copyright (c) 2015 Karliuka Vitalii(karliuka.vitalii@gmail.com) 
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*/
class Faonni_Smtp_Model_Core_Email_Template 
	extends Mage_Core_Model_Email_Template
{
    /**
     * Sends this email using the given transport or a previously
     * set DefaultTransport or the internal mail function if no
     * default transport had been set.
     * 
     * @return string	 
     */    
	public function send($email, $name = null, array $variables = array())
    {
        $smtp = Mage::helper('faonni_smtp');
		
        if ($smtp->isEnabled() !== true) 
		{
            return parent::send($email, $name, $variables);
        }

        if (!$this->isValidForSend()) 
		{
            Mage::logException(new Exception('This letter cannot be sent.'));
            return false;
        }

        $emails = array_values((array)$email);
		
        $names = is_array($name) ? $name : (array)$name;
        $names = array_values($names);
		
        foreach ($emails as $key => $email) 
		{
            if (!isset($names[$key])) 
			{
                $names[$key] = substr($email, 0, strpos($email, '@'));
            }
        }

        $variables['email'] = reset($emails);
        $variables['name'] = reset($names);

        ini_set('SMTP', Mage::getStoreConfig('system/smtp/host'));
        ini_set('smtp_port', Mage::getStoreConfig('system/smtp/port'));

        $mail = $this->getMail();

        $setReturnPath = Mage::getStoreConfig(self::XML_PATH_SENDING_SET_RETURN_PATH);
		
        switch ($setReturnPath) 
		{
            case 1:
                $returnPathEmail = $this->getSenderEmail();
                break;
            case 2:
                $returnPathEmail = Mage::getStoreConfig(self::XML_PATH_SENDING_RETURN_PATH_EMAIL);
                break;
            default:
                $returnPathEmail = null;
                break;
        }

        if ($smtp->isEnabled() == true) 
		{
            $config = array(
                'auth' => $smtp->getConfigAuth(),
                'ssl' => $smtp->getConfigSsl(),
                'username' => $smtp->getConfigUsername(),
                'password' => $smtp->getConfigPassword(),
                'name' => $smtp->getConfigName(),
                'port' => $smtp->getConfigPort(),
            );

            $transport = new Zend_Mail_Transport_Smtp($smtp->getConfigHost(), $config);

            $mail->setDefaultTransport($transport);
        }

        if ($returnPathEmail !== null and $smtp->isEnabled() == false) 
		{
            $mailTransport = new Zend_Mail_Transport_Sendmail("-f".$returnPathEmail);
            Zend_Mail::setDefaultTransport($mailTransport);
        }

        foreach ($emails as $key => $email) 
		{
            $mail->addTo($email, '=?utf-8?B?' . base64_encode($names[$key]) . '?=');
        }

        $this->setUseAbsoluteLinks(true);
        $text = $this->getProcessedTemplate($variables, true);

        if($this->isPlain()) $mail->setBodyText($text);
        else 
		{
			preg_match_all("#(<img[^<>]+?src=['\"]([a-zA-Z0-9\.\/_:]+?)['\"][^<>]*?\>)#siue", $text, $out, PREG_SET_ORDER);
			if (is_array($out))
			{
				//$cache = array();
				
				foreach ($out as $image)
				{
					$filename = str_replace(Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB), '', $image[2]);
					
					if (is_readable($filename)) 
					{	
						$mail->setType(Zend_Mime::MULTIPART_RELATED);    
						$at = $mail->createAttachment(file_get_contents($filename));  
						$at->type = $this->getMimeType($filename);  
						$at->disposition = Zend_Mime::DISPOSITION_INLINE;  
						$at->encoding = Zend_Mime::ENCODING_BASE64;  
						$at->id = 'cid_'.md5_file($filename);
						$text = str_replace($image[2],  'cid:' . $at->id,  $text);    
					}
				}
			}
			 $mail->setBodyHTML($text);
        }

        $mail->setSubject('=?utf-8?B?' . base64_encode($this->getProcessedTemplateSubject($variables)) . '?=');
        $mail->setFrom($this->getSenderEmail(), $this->getSenderName());

        try {
            $mail->send();
            $this->_mail = null;
        }
		
        catch (Exception $e) 
		{
            $this->_mail = null;
            Mage::logException($e);
            return false;
        }
        return true;
    }

    /**
     * Attempt to get the content-type of a file based on the extension
     * @param  string $path
     * @return string
    */
    public static function getMimeType($path)
    {
        $ext = substr(strrchr($path, '.'), 1);

        if(!$ext) {
            // shortcut
            return 'binary/octet-stream';
        }
        switch (strtolower($ext)) {
            case 'bmp':
                $content_type = 'image/bitmap';
                break;
            case 'gif':
                $content_type = 'image/gif';
                break;
            case 'iff':
                $content_type = 'image/iff';
                break;
            case 'jb2':
                $content_type = 'image/jb2';
                break;
            case 'jpg':
            case 'jpe':
            case 'jpeg':
                $content_type = 'image/jpeg';
                break;
            case 'jpx':
                $content_type = 'image/jpx';
                break;
            case 'png':
                $content_type = 'image/png';
                break;
            case 'tif':
            case 'tiff':
                $content_type = 'image/tiff';
                break;
            case 'wbmp':
                $content_type = 'image/vnd.wap.wbmp';
                break;
            case 'xbm':
                $content_type = 'image/xbm';
                break;
            default:
                $content_type = 'binary/octet-stream';
                break;
        }
        return $content_type;
    }    
}
