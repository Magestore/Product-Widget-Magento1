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
 * Productwidget Edit Form Content Tab Block
 * 
 * @category    Magestore
 * @package     Magestore_ProductWidget
 * @author      Magestore Developer
 */
class Magestore_ProductWidget_Block_Adminhtml_Widget_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare tab form's information
     *
     * @return Magestore_ProductWidget_Block_Adminhtml_Productwidget_Edit_Tab_Form
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        
        
        $fieldset = $form->addFieldset('widget_form', array(
            'legend'=>Mage::helper('productwidget')->__('Widget Information')
        ));

        $fieldset->addField('widget_type', 'select', array(
            'label'        => Mage::helper('productwidget')->__('Widget Type'),
            'class'        => 'required-entry',
            'required'    => true,
            'name'        => 'widget_type',
            'values'        => Mage::getSingleton('productwidget/widget_type')->getOptionArray()
        ));

//        $websites = array();
//        foreach (Mage::app()->getWebsites() as $website) {
//            $websites[$website->getId()] = $website->getName();
//        }
//        $fieldset->addField('website_id', 'select', array(
//            'label'        => Mage::helper('productwidget')->__('Website ID'),
//            'name'        => 'website_id',
//            'values'    => $websites,
//            'required' => true
//        ));
//        $url = $this->getUrl('*/productwidget_widget/new');
        
        $js = "
        <script>
            var continueWidget = function(){
                var store_id = j('#store_id').val();
                var widget_type = j('#widget_type').val();
                window.setLocation('$url'+'store_id/'+store_id+'/widget_type/'+widget_type);
            }
        </script>
        ";

        if (!Mage::app()->isSingleStoreMode()) {
            $field =$fieldset->addField('store_id', 'select', array(
                'name'      => 'store_id',
                'label'     => Mage::helper('cms')->__('Store View'),
                'title'     => Mage::helper('cms')->__('Store View'),
                'required'  => true,
                'values'    => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, false),
                'note'      => Mage::helper('productwidget')->__('Customer will be redirected to this store when clicking on the widget or the button')
            ));
            $renderer = $this->getLayout()->createBlock('adminhtml/store_switcher_form_renderer_fieldset_element');
            $field->setRenderer($renderer);
        }
        else {
            $fieldset->addField('store_id', 'hidden', array(
                'name'      => 'store_id',
                'value'     => Mage::app()->getStore(true)->getId()
            ));
            $model->setStoreId(Mage::app()->getStore(true)->getId());
        }

        $fieldset->addField('button', 'note', array(
            'label' => Mage::helper('productwidget')->__(''),
            'name' => 'button',
            'text' =>'<button type="button" class="scalable save" onclick="continueWidget()"><span>' . Mage::helper("productwidget")->__("Continue") . '</span></button>' . $js,
        ));

        return parent::_prepareForm();
    }
}