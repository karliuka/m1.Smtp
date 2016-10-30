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
class Faonni_Smtp_Helper_Data 
	extends Mage_Core_Helper_Abstract
{
    public function isEnabled()
    {
        return (bool) Mage::getStoreConfig('system/smtp_email/is_enabled');
    }

    public function getConfigUsername()
    {
        return Mage::getStoreConfig('system/smtp_email/username');
    }

    public function getConfigPassword()
    {
        return Mage::getStoreConfig('system/smtp_email/password');
    }

    public function getConfigAuth()
    {
        return Mage::getStoreConfig('system/smtp_email/auth');
    }

    public function getConfigSsl()
    {
        return Mage::getStoreConfig('system/smtp_email/ssl');
    }

    public function getConfigHost()
    {
        return Mage::getStoreConfig('system/smtp_email/host');
    }

    public function getConfigName()
    {
        return Mage::getStoreConfig('system/smtp_email/name');
    }

    public function getConfigPort()
    {
        return Mage::getStoreConfig('system/smtp_email/port');
    }
}
