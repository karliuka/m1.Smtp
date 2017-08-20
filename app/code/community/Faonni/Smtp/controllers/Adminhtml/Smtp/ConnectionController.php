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
class Faonni_Smtp_Adminhtml_Smtp_ConnectionController 
	extends Mage_Adminhtml_Controller_Action
{
    /**
     * test Action
     */		
	public function testAction() 
	{
        if (!$this->_validateFormKey() || 
				!$this->getRequest()->isPost() ||
					!$this->getRequest()->isXmlHttpRequest()
		) {
           return $this->_redirect('*/*/');
        }
        
        $request = $this->getRequest();		
		$result = array(
            'valid'   => false,
            'message' => $this->__('Connection Failed')
        );

		$password = $request->getParam('pass');
		/* if an obscured value came */
		if (preg_match('#^\*+$#', $password)) {
			$password = Mage::getStoreConfig(Faonni_Smtp_Helper_Data::XML_SMTP_PASS);
		} 
		/* if an encrypted value came */
		elseif (base64_encode(base64_decode($password)) === $password) {			
			$decrypted = Mage::helper('core')->decrypt($password);
			if (ctype_print($decrypted)) {
				$password = $decrypted;
			}
		} 

		$config = array(
			'port'     => $request->getParam('port'),
			'auth'     => $request->getParam('auth'),
			'ssl'      => $request->getParam('ssl'),					
			'username' => $request->getParam('user'),
			'password' => $password			
		);               

		try {
			$transport = new Faonni_Smtp_Model_Transport(
				$request->getParam('host'), 
				$config
			); 
			            
            if ($transport->testConnection()) {
				$result['valid'] = true;
				$result['message'] = $this->__('Connection Successful');				
			}
        } catch (Exception $e) {
            $result['message'] = $e->getMessage();
        } 
        
		$this->getResponse()->setBody(json_encode($result));
	}
	
    /**
     * Check for is allowed
     *
     * @return boolean
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('system/config/system');
    }	
}
