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
 * Productwidget Edit Tabs Block
 * 
 * @category    Magestore
 * @package     Magestore_ProductWidget
 * @author      Magestore Developer
 */
class Magestore_ProductWidget_Block_Adminhtml_Widget_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $widget = Mage::registry('productwidget_data');
        if(!$widget || !$widget->getWidgetType()){
            $this->setId('widget_tabs');
            $this->setDestElementId('edit_form');
            $this->setTitle(Mage::helper('productwidget')->__('Widget Information'));
        }
    }
    
    /**
     * prepare before render block to html
     *
     * @return Magestore_ProductWidget_Block_Adminhtml_Productwidget_Edit_Tabs
     */
    protected function _beforeToHtml()
    {
       $widget = Mage::registry('productwidget_data');
       if(!$widget || !$widget->getWidgetType()){
            $this->addTab('form_section', array(
                'label'     => Mage::helper('productwidget')->__('Widget Information'),
                'title'     => Mage::helper('productwidget')->__('Widget Information'),
                'content'   => $this->getLayout()
                ->createBlock('productwidget/adminhtml_widget_edit_tab_form')
                ->toHtml(),
            ));
        }

       
        return parent::_beforeToHtml();
    }
}