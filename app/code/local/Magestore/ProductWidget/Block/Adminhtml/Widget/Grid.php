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
 * Productwidget Grid Block
 *
 * @category    Magestore
 * @package     Magestore_ProductWidget
 * @author      Magestore Developer
 */
class Magestore_ProductWidget_Block_Adminhtml_Widget_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct() {
        parent::__construct();
        $this->setId('productwidgetGrid');
        $this->setDefaultSort('widget_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    /**
     * prepare collection for block to display
     *
     * @return Magestore_ProductWidget_Block_Adminhtml_Productwidget_Grid
     */
    protected function _prepareCollection() {
        $collection = Mage::getModel('productwidget/widget')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare columns for this grid
     *
     * @return Magestore_ProductWidget_Block_Adminhtml_Productwidget_Grid
     */
    protected function _prepareColumns() {
        $this->addColumn('widget_id', array(
            'header' => Mage::helper('productwidget')->__('ID'),
            'align' => 'right',
            'width' => '50px',
            'index' => 'widget_id',
        ));

        $this->addColumn('widget_name', array(
            'header' => Mage::helper('productwidget')->__('Widget Name'),
            'width' => '200',
            'index' => 'widget_name',
            'type' => 'text',
        ));

        $this->addColumn('widget_type', array(
            'header' => Mage::helper('productwidget')->__('Widget Type'),
            'width' => '100',
            'index' => 'widget_type',
            'type' => 'options',
            'options' => Mage::getSingleton('productwidget/widget_type')->getOptionArray()
        ));


        $this->addColumn('store_id', array(
            'header' => Mage::helper('productwidget')->__('Store ID'),
            'width' => '200',
            'index' => 'store_id',
            'type' => 'options',
            'options' => Mage::getSingleton('adminhtml/system_store')->getStoreOptionHash()
        ));

        $this->addColumn('views', array(
            'header' => Mage::helper('productwidget')->__('Lifetime View'),
            'width' => '20px',
            'index' => 'views',
            'type' => 'number',
        ));

        $this->addColumn('clicks', array(
            'header' => Mage::helper('productwidget')->__('Lifetime Click'),
            'width' => '20px',
            'index' => 'clicks',
            'type' => 'number',
        ));

        $this->addColumn('total', array(
            'header' => Mage::helper('productwidget')->__('Lifetime Orders'),
            'width' => '20px',
            'index' => 'total',
            'type' => 'number',
        ));

        $this->addColumn('created_time', array(
            'header' => Mage::helper('productwidget')->__('Created time'),
            'width' => '150px',
            'index' => 'created_time',
            'type' => 'datetime',
            'gmtoffset' => true
        ));

        $this->addColumn('status', array(
            'header' => Mage::helper('productwidget')->__('Status'),
            'align' => 'left',
            'width' => '80px',
            'index' => 'status',
            'type' => 'options',
            'options' => Mage::getSingleton('productwidget/status')->getOptionArray()
        ));


        $this->addColumn('action',
            array(
                'header' => Mage::helper('productwidget')->__('Action'),
                'width' => '100',
                'type' => 'action',
                'getter' => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('productwidget')->__('Edit'),
                        'url' => array('base' => '*/*/edit'),
                        'field' => 'id'
                    )),
                'filter' => false,
                'sortable' => false,
                'index' => 'stores',
                'is_system' => true,
            ));

        $this->addColumn('generate', array(
            'header' => Mage::helper('productwidget')->__('Generate Code'),
            'width' => '100',
            'type' => 'action',
            'getter' => 'getId',
            'actions' => array(
                array(
                    'caption' => Mage::helper('productwidget')->__('Generate'),
                    'onclick' => 'return showGenerateCode(this);',
                    'field' => 'id'
                )),
            'filter' => false,
            'sortable' => false,
            'index' => 'stores',
            'is_system' => true,
        ));

        // $this->addExportType('*/*/exportCsv', Mage::helper('productwidget')->__('CSV'));
        // $this->addExportType('*/*/exportXml', Mage::helper('productwidget')->__('XML'));

        return parent::_prepareColumns();
    }

    protected function _afterToHtml($html) {
        $url = $this->getUrl('*/*/showGenerateCode');
        $html .= "<script>
            var hasFlash = false;
            try {
                hasFlash = Boolean(new ActiveXObject('ShockwaveFlash.ShockwaveFlash'));
            } catch(exception) {
                hasFlash = ('undefined' != typeof navigator.mimeTypes['application/x-shockwave-flash']);
            }
            var showGenerateCode = function(e){
                var id = e.up('tr').down().down().value;
                new Ajax.Request('$url',{
                    method : 'post',
                    parameters : {
                        form_key : FORM_KEY,
                        id : id
                    },
                    onComplete: function(xhr){
                        TINY.box.show('');
                        $('tinycontent').innerHTML = xhr.responseText;
                        // clipboard text, click to save, click save. copy button.
                        $('copy-button').dataset.clipboardText= $('clipboard-text').value;
                        var client = new ZeroClipboard(document.getElementById('copy-button'));
                        client.on('ready',function(){
                            client.on('aftercopy',function(){
                                j('#copied').fadeIn();
                                j('#copy-button').text('Copied to clipboard');
                            });
                        });
                        if(!hasFlash)
                            j('#copy-button').hide();
                    }
                });
            };
        </script>";
        return parent::_afterToHtml($html);
    }

    /**
     * prepare mass action for this grid
     *
     * @return Magestore_ProductWidget_Block_Adminhtml_Productwidget_Grid
     */
    protected function _prepareMassaction() {
        $this->setMassactionIdField('widget_id');
        $this->getMassactionBlock()->setFormFieldName('productwidget');

        $this->getMassactionBlock()->addItem('delete', array(
            'label' => Mage::helper('productwidget')->__('Delete'),
            'url' => $this->getUrl('*/*/massDelete'),
            'confirm' => Mage::helper('productwidget')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('productwidget/status')->getOptionArray();

        array_unshift($statuses, array('label' => '', 'value' => ''));
        $this->getMassactionBlock()->addItem('status', array(
            'label' => Mage::helper('productwidget')->__('Change status'),
            'url' => $this->getUrl('*/*/massStatus', array('_current' => true)),
            'additional' => array(
                'visibility' => array(
                    'name' => 'status',
                    'type' => 'select',
                    'class' => 'required-entry',
                    'label' => Mage::helper('productwidget')->__('Status'),
                    'values' => $statuses
                ))
        ));
        return $this;
    }

    /**
     * get url for each row in grid
     *
     * @return string
     */
    public function getRowUrl($row) {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
}