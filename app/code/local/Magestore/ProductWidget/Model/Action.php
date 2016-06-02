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
 * Productwidget Model
 * 
 * @category    Magestore
 * @package     Magestore_ProductWidget
 * @author      Magestore Developer
 */
class Magestore_ProductWidget_Model_Action extends Mage_Core_Model_Abstract
{
	const ACTION_VIEW = 'view';
	const ACTION_CLICK = 'click';
	const ACTION_BUY = 'buy';
    public function _construct()
    {
        parent::_construct();
        $this->_init('productwidget/action');
    }

    public function getOptionArray(){
    	$helper = Mage::helper('productwidget');
    	return array(
    		self::ACTION_VIEW => $helper->__('View'),
    		self::ACTION_CLICK => $helper->__('Click'),
    		self::ACTION_BUY => $helper->__('Buy')
    	);
    }

    public function getDomainOptionArray(){
        $collection = $this->getCollection()->addFieldToSelect('domain');
        $collection->getSelect()->distinct(true)->order('domain');
        $array = array();
        foreach ($collection as $row) {
            if($domain = $row->getDomain()){
                $array[$domain] = $domain;
            }
        }
        return $array;
    }
}