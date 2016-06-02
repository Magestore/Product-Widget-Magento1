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

class Magestore_ProductWidget_Block_Adminhtml_Report_General_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * Add fieldset with general report fields
     *
     * @return Mage_Adminhtml_Block_Report_Filter_Form
     */
    protected function _prepareForm()
    {
        $actionUrl = $this->getUrl('*/*/general');
        $form = new Varien_Data_Form(
            array('id' => 'filter_form', 'action' => $actionUrl, 'method' => 'get')
        );
        $htmlIdPrefix = 'general_report_';
        $form->setHtmlIdPrefix($htmlIdPrefix);
        $fieldset = $form->addFieldset('base_fieldset', array('legend'=>Mage::helper('productwidget')->__('Filter')));


        $value = Mage::getSingleton('adminhtml/session')->getData('filter_param');

        $fieldset->addField('period_type', 'select', array(
            'name' => 'period_type',
            'options' => array(
                'day'   => Mage::helper('reports')->__('Day'),
                'month' => Mage::helper('reports')->__('Month'),
                'year'  => Mage::helper('reports')->__('Year')
            ),
            'label' => Mage::helper('reports')->__('Period'),
            'title' => Mage::helper('reports')->__('Period')
        ));

        $fieldset->addField('action', 'select', array(
            'name' => 'action',
            'options' => Mage::getSingleton('productwidget/action')->getOptionArray(),
            'label' => Mage::helper('reports')->__('Action'),
            'title' => Mage::helper('reports')->__('Action')
        ));

        $fieldset->addField('from', 'date', array(
            'name'      => 'from',
            'format' => 'yyyy-MM-dd',
            'image'     => $this->getSkinUrl('images/grid-cal.gif'),
            'label'     => Mage::helper('reports')->__('From'),
            'title'     => Mage::helper('reports')->__('From'),
            'required'  => true
        ));

        $fieldset->addField('to', 'date', array(
            'name'      => 'to',
            'format' => 'yyyy-MM-dd',
            'image'     => $this->getSkinUrl('images/grid-cal.gif'),
            'label'     => Mage::helper('reports')->__('To'),
            'title'     => Mage::helper('reports')->__('To'),
            'required'  => true
        ));
        $form->setValues($value);
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Initialize form fileds values
     * Method will be called after prepareForm and can be used for field values initialization
     *
     * @return Mage_Adminhtml_Block_Widget_Form
     */
    protected function _initFormValues()
    {
        // $data = $this->getFilterData()->getData();
        // foreach ($data as $key => $value) {
        //     if (is_array($value) && isset($value[0])) {
        //         $data[$key] = explode(',', $value[0]);
        //     }
        // }
        // $this->getForm()->addValues($data);
        return parent::_initFormValues();
    }
}
