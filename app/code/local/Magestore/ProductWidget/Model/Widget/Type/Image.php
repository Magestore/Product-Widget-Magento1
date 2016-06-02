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
class Magestore_ProductWidget_Model_Widget_Type_Image extends Magestore_ProductWidget_Model_Widget_Type
{
	public function getWidgetLink($widget){
        $param = array(
            'wid' => $widget->getId(),
            'rd' => $widget->getExtra('widget_link')
        );
        if($param['rd'] != 'custom'){
            $param['pid'] = $this->getProduct($widget)->getId();
        }else{
            $param['customlink'] = base64_encode($widget->getExtra('widget_link_custom'));
        }
        return  Mage::getUrl('productwidget/widget/index', $param);
    }

    public function getProduct($widget){
        $sku = $widget->getProductSku();
        if(!$sku){
            $sku = $widget->getExtra('product_sku');
        }
        if($sku){
            return Mage::getModel('catalog/product')
                ->setStoreId($widget->getData('store_id'))
                ->loadByAttribute('sku',$sku);
        }
        return null;
    }

    public function validateProduct($widget){
        $product = $this->getProduct($widget);
//        \zend_debug::dump($product);
        if($product === null){
            return true;
        }
        if(!$product || !$product->getId())
            return false;
        return true;
    }
}