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
 * Productwidget Adminhtml Block
 * 
 * @category    Magestore
 * @package     Magestore_ProductWidget
 * @author      Magestore Developer
 */
class Magestore_ProductWidget_Block_Adminhtml_Widget_Form extends Mage_Adminhtml_Block_Template
{
    public function __construct()
    {
       parent::__construct();
       $widget = Mage::registry('productwidget_data');
       $widgetType = Mage::getSingleton('productwidget/widget_type');
       $type = $widget->getWidgetType();

       if($type && $widgetType->validate($type)){
           $this->setTemplate("productwidget/form/$type.phtml");
       }else{
            Mage::app()->getResponse()->setRedirect(Mage::helper('adminhtml')->getUrl("*/productwidget_widget/new"));
       }
    }

    public function getWidgetTypes(){
    	$typeArray = Mage::getSingleton('productwidget/widget_type')->getOptionArray();
    	return $typeArray;
    }

    public function getButtonLinks(){
    	return Mage::getSingleton('productwidget/buttonLink')->getOptionArray();
    }

    public function getWebsiteIds(){
        return Mage::getSingleton('adminhtml/system_config_source_website')->toOptionArray();
    }

    public function getFormUrl(){
        return $this->getUrl('adminhtml/productwidget_widget/save');
    }
    
    public function getCurrentData(){
        $currentWidget = Mage::registry('productwidget_data');
        $data = $currentWidget->getData('extra');
        if(!$data){
            return '';
        }
        return $data;
    }
}