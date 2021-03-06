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
class Faonni_Smtp_Model_Adminhtml_System_Config_Source_Auth
{
    /**
     * Return array of options as value-label pairs
     *
     * @return array
     */   	
    public function toOptionArray()
    {
        $helper = Mage::helper('faonni_smtp');
        return array(
            array('value' => 'login', 'label' => $helper->__('Login/Password')),
        );
    }
}
