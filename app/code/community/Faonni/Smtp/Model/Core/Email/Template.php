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
 * @copyright   Copyright (c) 2017 Karliuka Vitalii(karliuka.vitalii@gmail.com) 
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Faonni_Smtp_Model_Core_Email_Template 
	extends Mage_Core_Model_Email_Template
{
    /**
     * Retrieve mail object instance
     *
     * @return Zend_Mail
     */
    public function getMail()
    {
        if (is_null($this->_mail)) {
            $this->_mail = new Faonni_Smtp_Model_Mail('utf-8');
        }
        return $this->_mail;
    }
    
    /**
     * Send mail to recipient
     *
     * @param array|string $email E-mail(s)
     * @param array|string|null $name receiver name(s)
     * @param array $variables template variables
     * @return bool
     **/ 
	public function send($email, $name=null, array $variables=array())
    {
        $helper = Mage::helper('faonni_smtp');
		
        if (!$helper->isEnabled()) {
            return parent::send($email, $name, $variables);
        }

        if (!$this->isValidForSend()) {
            Mage::logException(new Exception('This letter cannot be sent.'));
            return false;
        }

        $emails = array_values((array)$email);
		
        $names = is_array($name) ? $name : (array)$name;
        $names = array_values($names);
		
        foreach ($emails as $key => $email) {
            if (!isset($names[$key])) {
                $names[$key] = substr($email, 0, strpos($email, '@'));
            }
        }

        $variables['email'] = reset($emails);
        $variables['name'] = reset($names);

        ini_set('SMTP', $helper->getHost());
        ini_set('smtp_port', $helper->getPort());

        $mail = $this->getMail();

		$transport = new Faonni_Smtp_Model_Transport(
			$helper->getHost(), 
			$helper->getConfig()
		);
				
        $setReturnPath = Mage::getStoreConfig(
			self::XML_PATH_SENDING_SET_RETURN_PATH
		);		
        switch ($setReturnPath) {
            case 1:
                $returnPathEmail = $this->getSenderEmail();
                break;
            case 2:
                $returnPathEmail = Mage::getStoreConfig(
					self::XML_PATH_SENDING_RETURN_PATH_EMAIL
				);
                break;
            default:
                $returnPathEmail = null;
                break;
        }
	
        if ($returnPathEmail !== null) {
            $mail->setReturnPath($returnPathEmail);
        }
        
		$mail->setDefaultTransport($transport);
		
        foreach ($emails as $key => $email) {
            $mail->addTo(
				$email, '=?utf-8?B?' . 
				base64_encode($names[$key]) . 
				'?='
			);
        }

        $this->setUseAbsoluteLinks(true);
        $text = $this->getProcessedTemplate($variables, true);

        if($this->isPlain()) {
			$mail->setBodyText($text);
		} else {
			$mail->setBodyHTML($text);
        }

        $mail->setSubject(
			'=?utf-8?B?' . 
			base64_encode($this->getProcessedTemplateSubject($variables)) . 
			'?='
		);
		
        $mail->setFrom(
			$this->getSenderEmail(), 
			$this->getSenderName()
		);

        try {
            $mail->send();
            $this->_mail = null;
        }		
        catch (Exception $e) {
            $this->_mail = null;
            Mage::logException($e);
            return false;
        }
		
        return true;
    }  
}
