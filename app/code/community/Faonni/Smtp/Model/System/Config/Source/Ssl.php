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
class Faonni_Smtp_Model_System_Config_Source_Ssl
{
    public function toOptionArray()
    {
        return array(
            array('value' => 'ssl', 'label' => Mage::helper('adminhtml')->__('SSL')),
            array('value' => 'tls', 'label' => Mage::helper('adminhtml')->__('TLS')),
        );
    }
}
