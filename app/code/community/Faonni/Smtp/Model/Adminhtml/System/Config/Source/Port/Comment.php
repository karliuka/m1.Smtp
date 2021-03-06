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
class Faonni_Smtp_Model_Adminhtml_System_Config_Source_Port_Comment 
	extends Mage_Core_Model_Config_Data
{
    /**
     * Retrieve element comment by element value
     *
     * @param Mage_Core_Model_Config_Element $element
     * @param string $currentValue
     * @return string
     */	
    public function getCommentText(Mage_Core_Model_Config_Element $element, $currentValue)
    {
        return Mage::helper('faonni_smtp')->__('Usually is 25, 587 or 465. Please consult with your service provider.');
    }
} 
