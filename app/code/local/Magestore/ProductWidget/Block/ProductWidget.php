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
 * Productwidget Block
 * 
 * @category    Magestore
 * @package     Magestore_ProductWidget
 * @author      Magestore Developer
 */
class Magestore_ProductWidget_Block_ProductWidget extends Mage_Core_Block_Template
{
    /**
     * prepare block's layout
     *
     * @return Magestore_ProductWidget_Block_Productwidget
     */
    public function _prepareLayout()
    {
       	parent::_prepareLayout();
    }

    public function getWidgetHtml(){
    	$widget = $this->getWidget();
    	return $widget->getHtml();
    }

    public function getWidget(){
    	if(!$this->getData('widget')){
            $widget = Mage::registry('current_widget');
    		$this->setData('widget',$widget);
    	}
    	return $this->getData('widget');
    }
}