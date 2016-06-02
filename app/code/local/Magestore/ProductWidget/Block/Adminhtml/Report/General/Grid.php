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
class Magestore_ProductWidget_Block_Adminhtml_Report_General_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('generalreportGrid');
        $this->setDefaultSort('created_time');
        $this->setDefaultDir('ASC');
        // $this->setUseAjax(true);
        $this->setSaveParametersInSession(true);
    }
    
    /**
     * prepare collection for block to display
     *
     * @return Magestore_ProductWidget_Block_Adminhtml_Productwidget_Grid
     */
    protected function _prepareCollection()
    {
        if(Mage::getSingleton('adminhtml/session')->getData('filter_param')){
            $collection = Mage::getModel('productwidget/action')->getCollection();
            if($filter = Mage::getSingleton('adminhtml/session')->getData('filter_param')){
                $collection->addFilter($filter);
            }
            $collection->getSelect()->joinLeft(array('w' => $collection->getTable('productwidget/widget')), 'main_table.widget_id = w.widget_id', array('widget_name'));
        }
        //join to get widget name
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
    
    /**
     * prepare columns for this grid
     *
     * @return Magestore_ProductWidget_Block_Adminhtml_Productwidget_Grid
     */
    protected function _prepareColumns()
    {
//        $this->addColumn('action_id', array(
//            'header'    => Mage::helper('productwidget')->__('ID'),
//            'align'     =>'right',
//            'width'     => '50px',
//            'index'     => 'action_id',
//        ));

//
//        $this->addColumn('domain', array(
//            'header'    => Mage::helper('productwidget')->__('Domain'),
//            'width'     => '300px',
//            'index'     => 'domain',
//            'type'      => 'text',
//        ));
        if(Mage::getSingleton('adminhtml/session')->getData('filter_param')){
            $this->addColumn('created_time', array(
                'header'    => Mage::helper('productwidget')->__('Period'),
                'width'     => '150px',
                'index'     => 'new_created_time',
                'type'      => 'text',
                'align'     => 'center'
            ));
            $this->addColumn('widget_name', array(
                'header'    => Mage::helper('productwidget')->__('Widget Name'),
                'width'     => '200px',
                'index'     => 'widget_name',
                'type'      => 'text',
            ));
//            $this->addColumn('action', array(
//                'header'    => Mage::helper('productwidget')->__('Action'),
//                'width'     => '100px',
//                'index'     => 'action',
//                'type'      => 'options',
//                'options'   => Mage::getSingleton('productwidget/action')->getOptionArray()
//            ));
             $this->addColumn('total', array(
                'header'    => Mage::helper('productwidget')->__('Number of Action'),
                'width'     => '200px',
                'index'     => 'sum_total',
                'type'      => 'number',
                'align'     => 'left'
                ));
        }else{
//           $this->addColumn('ip_address', array(
//            'header'    => Mage::helper('productwidget')->__('IP Address'),
//            'width'     => '150px',
//            'index'     => 'ip_address',
//            'align'     => 'center'
//            ));
//           $this->addColumn('total', array(
//            'header'    => Mage::helper('productwidget')->__('Number of Actions'),
//            'width'     => '100px',
//            'index'     => 'total',
//            'type'      => 'number',
//            'align'     => 'center'
//            ));
//           $this->addColumn('created_time', array(
//            'header'    => Mage::helper('productwidget')->__('Action Time'),
//            'width'     => '200px',
//            'index'     => 'created_time',
//            'type'      => 'datetime',
//            ));
        }

        $this->addExportType('*/*/exportCsv', Mage::helper('productwidget')->__('CSV'));
        $this->addExportType('*/*/exportXml', Mage::helper('productwidget')->__('XML'));

        return parent::_prepareColumns();
    }

 // var showAddWidgetForm = function(){
 //            j.post('$url',
 //                 {
 //                    form_key : FORM_KEY
 //                 }, 
 //                 function(data, textStatus, xhr) {
 //                     TINY.box.show('');
 //                     j('#tinycontent').html(data);
 //                });
 //        };

    protected function _afterToHtml($html) {
        $url = $this->getUrl('*/*/showGenerateCode');
        $html .= "<script>
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
    protected function _prepareMassaction()
    {
        return $this;
    }
    
    /**
     * get url for each row in grid
     *
     * @return string
     */
    public function getRowUrl($row)
    {
    }

    //  public function getGridUrl()
    // {
    //     return $this->getUrl('*/*/generalGrid', array('_current'=> true));
    // }
}