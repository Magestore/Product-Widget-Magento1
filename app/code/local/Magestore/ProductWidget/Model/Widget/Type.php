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
class Magestore_ProductWidget_Model_Widget_Type extends Mage_Core_Model_Abstract
{
	const TYPE_PRODUCT = 'product';
	const TYPE_PRODUCT_SLIDER = 'productslider';
    const TYPE_CONTENT = 'content';
    const TYPE_IMAGE = 'image';
    public function _construct()
    {
        parent::_construct();
    }

    public function getOptionArray(){
    	return array(
            self::TYPE_PRODUCT => Mage::helper('productwidget')->__('Product'),
            self::TYPE_IMAGE => Mage::helper('productwidget')->__('Image'),
    		// self::TYPE_PRODUCT_SLIDER => Mage::helper('productwidget')->__('Product Slider'),
            // self::TYPE_CONTENT => Mage::helper('productwidget')->__('Content'),
            );
    }

    public function validate($type){
        $array = $this->getOptionArray();
        if(array_key_exists($type, $array)){
            return true;
        }
        return false;
    }
}