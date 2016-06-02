<?php
/**
 * Magestore
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the Magestore.com license that is
 * available through the world-wide-web at this URL:
 * http://www.magestore.com/license-agreement.html
 * 
 * DISCLAIMER
 * 
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 * 
 * @category    Magestore
 * @package     Magestore_ProductWidget
 * @copyright   Copyright (c) 2015 Magestore (http://www.magestore.com/)
 * @license     http://www.magestore.com/license-agreement.html
 */

/**
 * ProductWidget Helper
 * 
 * @category    Magestore
 * @package     Magestore_ProductWidget
 * @author      Magestore Developer
 */
class Magestore_ProductWidget_Helper_Config extends Mage_Core_Helper_Abstract
{
    public function getClickTime($store = null){
        return (int)Mage::getStoreConfig('productwidget/general/click_time',$store);
    }

    public function getGeneralConfig($field,$store = null){
        return Mage::getStoreConfig("productwidget/general/$field",$store);
    }

    public function getStyleConfig($field,$store = null){
        return Mage::getStoreConfig("productwidget/style/$field",$store);
    }

    public function isEnabled($store = null){
    	return $this->getGeneralConfig('enable',$store);
    }

    public function deleteAllActionRecord($store = null){
    	return $this->getGeneralConfig('delete_action_record',$store);
    }

    public function getLimitDescription($store = null){
        return $this->getStyleConfig('limit_description',$store);
    }
}