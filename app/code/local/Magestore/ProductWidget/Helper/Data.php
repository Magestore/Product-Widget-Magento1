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
 * ProductWidget Helper
 * 
 * @category    Magestore
 * @package     Magestore_ProductWidget
 * @author      Magestore Developer
 */
class Magestore_ProductWidget_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * get redirect url depends on code and store
     * @param $code
     * @param $storeId
     * @return string
     */
    public function getRedirectUrl($code, $storeId){
    	$url = '';
    	switch ($code) {
    		case Magestore_ProductWidget_Model_ButtonLink::CART_PAGE :
    			$url =  Mage::app()->getStore($storeId)->getUrl('checkout/cart');
    			break;
    		case Magestore_ProductWidget_Model_ButtonLink::CHECKOUT_PAGE:
    			$url =  Mage::app()->getStore($storeId)->getUrl('checkout/onepage');
    			break;
    		default:
    			break;
    	}
    	return $url;
    }

    /**
     * remove key 'form_key' and 'id' to generate `extra` for widget.
     * @param $data
     * @return mixed
     */
    public function removeFormKey($data){
        if(array_key_exists('form_key', $data)){
            unset($data['form_key']);
        }
        if(array_key_exists('id', $data)){
            unset($data['id']);
        }
        if(array_key_exists('widget_id', $data)){
            unset($data['widget_id']);
        }
        return $data;
    }

    /**
     * Add cookie to check sales.
     * $key  : rd, pid, wid, domain,
     * @param $data
     */
    public function addCookie($data){
        $cookie = Mage::getSingleton('core/cookie');
        foreach ($data as $key => $value) {
            $cookie->set($key,$value);
        }        
    }

    /**
     * Remove protocol in URL
     * @param $url
     * @return string
     */
    public function removeProtocol($url){
        return substr($url, strpos($url, '//'));
    }

    /**
     * Use to encode widget ID
     * @param string $string
     * @return string
     */
    public function encode($string = ''){
        if($string){ 
            return base64_encode($string . 'M'.'a'.'g'.'e'.'s'.'t'.'o'.'r'.'e');
        }
        return '';
    }

    /**
     * Use to decode widget ID
     * @param string $string
     * @return string
     */
    public function decode($string = ''){
        if($string){  
            return substr(base64_decode($string),0,-9);
        }
        return '';
    }

    /**
     * UPDATE all widget information as views, clicks, sales.
     * @return bool
     */
    public function updateAllWidgetInformation(){
        $widgets = Mage::getResourceSingleton('productwidget/widget_collection');
        $widgetResource = Mage::getResourceSingleton('productwidget/widget');
        foreach ($widgets as $widget) {
            $widgetResource->updateInformation($widget);
        }
        return true;
    }

    public function limitString($string, $limit = 100) {
        // Return early if the string is already shorter than the limit
        if (strlen($string) < $limit) {
            return $string;
        }
        $regex = "/(.{1,$limit})\b/";
        preg_match($regex, $string, $matches);
        return $matches[1] .'...';
    }

}