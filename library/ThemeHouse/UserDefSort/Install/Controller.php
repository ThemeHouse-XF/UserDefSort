<?php

class ThemeHouse_UserDefSort_Install_Controller extends ThemeHouse_Install
{
    
    protected $_resourceManagerUrl = 'http://xenforo.com/community/resources/user-default-sort-options-by-th.3682/';
    
    
    protected function _getTableChanges()
    {
    	return array(
    			'xf_user_option' => array(
    			        'override_sort_th' => 'BOOLEAN NOT NULL DEFAULT 1',
    					'default_sort_options_th' => 'TINYBLOB DEFAULT NULL', /* END 'default_sort_options_th' */
    			), /* END 'xf_user_option' */
    	);
    } /* END _getTableChanges */


    protected function _postInstall()
    {
        $addOn = $this->getModelFromCache('XenForo_Model_AddOn')->getAddOnById('YoYo_');
        if ($addOn) {
            $db->query("
                UPDATE xf_user_option
                    SET override_sort_th=override_sort_waindigo, default_sort_options_th=default_sort_options_waindigo");
        }
    }
    
}