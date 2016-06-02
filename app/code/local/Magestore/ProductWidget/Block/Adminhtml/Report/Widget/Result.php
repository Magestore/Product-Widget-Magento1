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

class Magestore_ProductWidget_Block_Adminhtml_Report_Widget_Result extends Mage_Adminhtml_Block_Template
{
   public function __construct(){
        return parent::__construct();
   }

   public function getWidgetReport(){
   		$filter= Mage::getSingleton('adminhtml/session')->getData('filter_param');
   		if(!$filter){
   			return '';
   		}
         $data =  Mage::getResourceSingleton('productwidget/action')->getVCSReport('widget_id',$filter);
         Mage::register('report_data',$data);
         return json_encode($data);
   }

   public function getWidgetRateReport(){
      $data = Mage::registry('report_data');
      if($data){
         // $data[0][1] = "CLICK/VIEW";
         // $data[0][2] = "SALES/VIEW";
         // $data[0][3] = "SALES/CLICK";
         foreach ($data as $key => $value) {
            // if($key==0)continue;
            $data[$key][1] = $value[1] ? $value[2] ? (float)number_format($value[2]/$value[1],2) :0 :0;
            $data[$key][2] = $value[3] ? $value[1] ? (float)number_format($value[3]/$value[1],2) :0 :0;
            $data[$key][3] = $value[3] ? $value[2] ? (float)number_format($value[3]/$value[2],2) :0 :0;
         }
         return json_encode($data);
      }
      return '';
   }

   public function getFilterParams(){
      $filter= Mage::getSingleton('adminhtml/session')->getData('filter_param');
      if(!$filter)
         return array();
      return $filter;
   }

}
