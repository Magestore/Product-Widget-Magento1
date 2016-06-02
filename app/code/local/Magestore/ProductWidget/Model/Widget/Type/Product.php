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
class Magestore_ProductWidget_Model_Widget_Type_Product extends Magestore_ProductWidget_Model_Widget_Type
{
	public function getWidgetLink($widget,$product = null){
        $param = array(
            'wid' => $widget->getId(),
            'rd' => $widget->getExtra('widget_link')
        );
        if($param['rd'] != 'custom'){
            $param['pid'] = $product->getId();
        }else{
            $param['customlink'] = base64_encode($widget->getExtra('widget_link_custom'));
        }
        return  Mage::getUrl('productwidget/widget/index', $param);
    }

    public function getButtonLink($widget, $product = null){
        $param = array(
            'wid' => $widget->getId(),
            'pid' => $product->getId(),
            'rd' => $widget->getExtra('button_link')
        );
        return  Mage::getUrl('productwidget/widget/index', $param);
    }

    public function validateProduct($widget){
        $widgetSku = $widget->getProductSku();
        $skus = explode(',', $widgetSku);
        foreach ($skus as $sku) {
            if(!$sku)continue;
            $id = Mage::getModel('catalog/product')
                ->setStoreId($widget->getData('store_id'))
                ->getIdBySku($sku);
            if(!$id)
                return false;
        }
        return true;
    }
}