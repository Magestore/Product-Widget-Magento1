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
 * Productwidget Edit Block
 *
 * @category     Magestore
 * @package     Magestore_ProductWidget
 * @author      Magestore Developer
 */
class Magestore_ProductWidget_Block_Adminhtml_Widget_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct() {
        parent::__construct();

        $this->_objectId = 'id';
        $this->_blockGroup = 'productwidget';
        $this->_controller = 'adminhtml_widget';
        $this->_updateButton('save', 'label', Mage::helper('productwidget')->__('Save Widget'));
        $this->_updateButton('save', 'onclick', 'saveAndNothing();');
        $this->_updateButton('delete', 'label', Mage::helper('productwidget')->__('Delete Widget'));

        $this->_addButton('saveandduplicate', array(
            'label' => Mage::helper('adminhtml')->__('Save And Duplicate'),
            'onclick' => 'saveAndDuplicate()',
            'class' => 'add',
        ), -100);
        $this->_addButton('saveandgenerate', array(
            'label' => Mage::helper('adminhtml')->__('Save And Generate Code'),
            'onclick' => 'saveAndGenerate()',
            'class' => 'save',
        ), -100);
        $this->_addButton('saveandcontinue', array(
            'label' => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick' => 'saveAndContinueEdit()',
            'class' => 'save',
        ), -200);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('widget_content') == null)
                    tinyMCE.execCommand('mceAddControl', false, 'widget_content');
                else
                    tinyMCE.execCommand('mceRemoveControl', false, 'widget_content');
            }
            function saveAndNothing(){
                $('widget_name').addClassName('required-entry');
                if(widgetForm.validate())widgetForm.submit();
            }
            function saveAndContinueEdit(){
                $('widget_name').addClassName('required-entry');
                if(widgetForm.validate()){
                    $('widgetform').action+='back/edit/';
                    widgetform.submit();
                }
            }
            function saveAndGenerate(){
                $('widget_name').addClassName('required-entry');
                if(widgetForm.validate()){
                    $('widgetform').action+='back/generate/';
                    widgetform.submit();
                }
            }
            function saveAndDuplicate(){
                $('widget_name').addClassName('required-entry');
                if(widgetForm.validate()){
                    $('widgetform').action+='duplicate/1';
                    widgetform.submit();
                }
            }
        ";

        $widget = Mage::registry('productwidget_data');
        if (!$widget || !$widget->getWidgetType()) {
            $this->_removeButton('save');
            $this->_removeButton('delete');
            $this->_removeButton('saveandcontinue');
            $this->_removeButton('saveandduplicate');
            $this->_removeButton('saveandgenerate');
        }
    }

    protected function _prepareLayout() {
        parent::_prepareLayout();
        $widget = Mage::registry('productwidget_data');
        $type = $widget->getWidgetType();
        if (!$widget || !$type) {
            $this->setChild('form', $this->getLayout()->createBlock('productwidget/adminhtml_widget_edit_form'));
        } else {
            $block = $this->getLayout()->createBlock('productwidget/adminhtml_widget_form');
            $block->setChild('review', $this->getLayout()->createBlock('core/template')->setTemplate('productwidget/form/common/preview.phtml'));
            $this->setChild('form', $block);
        }
    }

    /**
     * get text to show in header when edit an item
     *
     * @return string
     */
    public function getHeaderText() {
        if (Mage::registry('productwidget_data')
            && Mage::registry('productwidget_data')->getId()
        ) {
            return Mage::helper('productwidget')->__("Edit Item '%s'",
                $this->htmlEscape(Mage::registry('productwidget_data')->getWidgetName())
            );
        }
        return Mage::helper('productwidget')->__('Add Widget');
    }
}