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
class Faonni_Smtp_Helper_Data 
	extends Mage_Core_Helper_Abstract
{
    /**
     * Enabled config path
     */
    const XML_SMTP_ENABLED = 'system/smtp/enabled';
    	
    /**
     * Host config path
     */
    const XML_SMTP_HOST = 'system/smtp/host';
    
    /**
     * Port config path
     */
    const XML_SMTP_PORT = 'system/smtp/port';
    
    /**
     * Auth config path
     */
    const XML_SMTP_AUTH = 'system/smtp/auth';
     
    /**
     * Ssl config path
     */
    const XML_SMTP_SSL = 'system/smtp/ssl';
    
    /**
     * Username config path
     */
    const XML_SMTP_USER = 'system/smtp/user';
    
    /**
     * Password config path
     */
    const XML_SMTP_PASS = 'system/smtp/pass';
    
    /**
     * Image attachment config path
     */
    const XML_SMTP_IMAGE_ATTACHMENT = 'system/smtp/image_attachment';    
        
    /**
     * Check Smtp Transport functionality should be enabled
     *
     * @return bool
     */    	
    public function isEnabled()
    {
        return Mage::getStoreConfig(self::XML_SMTP_ENABLED);
    }
    
    /**
     * Check Image attachment functionality should be enabled
     *
     * @return bool
     */    	
    public function isImageAttachment()
    {
        return Mage::getStoreConfig(self::XML_SMTP_IMAGE_ATTACHMENT);
    }    
    
    /**
     * Retrieve configure smtp settings
     *
     * @return array
     */
    public function getConfig()
    {
		return array(
			'port'     => Mage::getStoreConfig(self::XML_SMTP_PORT),
			'auth'     => Mage::getStoreConfig(self::XML_SMTP_AUTH),
			'ssl'      => Mage::getStoreConfig(self::XML_SMTP_SSL),
			'username' => Mage::getStoreConfig(self::XML_SMTP_USER),
			'password' => Mage::getStoreConfig(self::XML_SMTP_PASS)
		);        
    } 
    
    /**
     * Retrieve configure smtp host
     *
     * @return string
     */    
    public function getHost()
    {
        return Mage::getStoreConfig(self::XML_SMTP_HOST);
    }
    
    /**
     * Retrieve configure smtp port
     *
     * @return string
     */  
    public function getPort()
    {
        return Mage::getStoreConfig(self::XML_SMTP_PORT);
    }
}
