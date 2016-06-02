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
 * ProductWidget Status Model
 * 
 * @category    Magestore
 * @package     Magestore_ProductWidget
 * @author      Magestore Developer
 */
class Magestore_ProductWidget_Model_ButtonLink extends Varien_Object
{
   const PRODUCT_PAGE    = 'product';
   const CART_PAGE    = 'cart';
   const CHECKOUT_PAGE    = 'checkout';

    /**
     * get model option as array
     *
     * @return array
     */
    static public function getOptionArray()
    {
        return array(
            self::PRODUCT_PAGE    => Mage::helper('productwidget')->__('Product Page'),
            self::CART_PAGE   => Mage::helper('productwidget')->__('Shopping Cart Page'),
            self::CHECKOUT_PAGE   => Mage::helper('productwidget')->__('Checkout Page')
        );
    }
    
    /**
     * get model option hash as array
     *
     * @return array
     */
    static public function getOptionHash()
    {
        $options = array();
        foreach (self::getOptionArray() as $value => $label) {
            $options[] = array(
                'value'    => $value,
                'label'    => $label
            );
        }
        return $options;
    }
}