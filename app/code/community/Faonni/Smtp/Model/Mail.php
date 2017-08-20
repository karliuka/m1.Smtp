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
class Faonni_Smtp_Model_Mail
	extends Zend_Mail
{
    /**
     * Image pattern
     *
     * @var string
     */
    protected $_pattern = "#<img[^<>]+?src=['\"]([a-zA-Z0-9\.\/_:]+?)['\"][^<>]*?\>#si";
    
    /**
     * Store urls
     *
     * @var array
     */
    protected $_storeUrls;    
    
    /**
     * Sets the HTML body for the message
     *
     * @param  string    $html
     * @param  string    $charset
     * @param  string    $encoding
     * @return Zend_Mail Provides fluent interface
     */
    public function setBodyHtml($html, $charset = null, $encoding = Zend_Mime::ENCODING_QUOTEDPRINTABLE)
    {
        if (Mage::helper('faonni_smtp')->isImageAttachment()) {
            $html = $this->_prepareBodyHtml($html);
        }
        return parent::setBodyHtml($html, $charset, $encoding);
    }
    
    /**
     * Prepare body html
     *
     * @param string $html
     * @return string
     */
    protected function _prepareBodyHtml($html)
    {
        if (false !== preg_match_all($this->_pattern, $html, $matches, PREG_SET_ORDER)) {
        
            $this->setType(Zend_Mime::MULTIPART_RELATED);
            
            $images = array();
            foreach ($matches as $match) {
                list($matchString, $src) = $match;
                
                $filename = Mage::getBaseDir() . 
                    DIRECTORY_SEPARATOR . 
                    str_replace($this->_getStoreUrls(), '', $src);

                $cid = 'cid_' . md5_file($filename);
                if (isset($images[$cid])) {
                    continue;
                }

                if (is_readable($filename)) {							 
                    $this->_createImageAttachment($filename, $cid);
                    $html = str_replace($src,  'cid:' . $cid,  $html);
                    $images[$cid] = $src;
                }                
            }            
        }
        return $html;
    }
    
    /**
     * Creates a Zend_Mime_Part image attachment
     *
     * @param  string $filename
     * @param  string $cid
     * @return void
     */
    protected function _createImageAttachment($filename, $cid)
    {
        $ttachment = $this->createAttachment(
            file_get_contents($filename)
        );  
        $ttachment->type = $this->_getMimeType($filename);  
        $ttachment->disposition = Zend_Mime::DISPOSITION_INLINE;  
        $ttachment->encoding = Zend_Mime::ENCODING_BASE64;  
        $ttachment->id = $cid;
    }
    
    /**
     * Retrieve store urls
     *
     * @return array
     */
    protected function _getStoreUrls()
    {
        if (null !== $this->_storeUrls) {
            return $this->_storeUrls;
        }
        
        $this->_storeUrls = Mage::register('faonni_smtp_store_urls');
        if (null !== $this->_storeUrls) {
            return $this->_storeUrls;
        }
        
        $this->_storeUrls = $this->_buildStoreUrls();
        Mage::register('faonni_smtp_store_urls', $this->_storeUrls);
        
        return $this->_storeUrls;
    }
    
    /**
     * Build store urls
     *
     * @return array
     */
    protected function _buildStoreUrls()
    {
        $storeUrls = array();
        foreach (Mage::app()->getStores() as $store) {
            foreach (array('unsecure', 'secure') as $scheme) {
                $node = "web/{$scheme}/base_url";
                $url = (string)Mage::getConfig()->getNode($node, 'store', $store->getCode());
                if (!in_array($url, $this->_storeUrls)) {
                    $storeUrls[] = $url;
                }
            }
        }        
        return $storeUrls;
    }     
    
    /**
     * Attempt to get the content-type of a file based on the extension
	 *
     * @param  string $path
     * @return string
     */
    protected function _getMimeType($path)
    {
        switch (strtolower(substr(strrchr($path, '.'), 1))) {
            case 'jpg':
            case 'jpe':
            case 'jpeg':
                $content_type = 'image/jpeg';
                break;
            case 'png':
                $content_type = 'image/png';
                break;
            case 'gif':
                $content_type = 'image/gif';
                break;                
            case 'svg':
                $content_type = 'image/svg+xml';
                break;
            default:
                $content_type = 'binary/octet-stream';
                break;
        }
        return $content_type;
    }     
} 
