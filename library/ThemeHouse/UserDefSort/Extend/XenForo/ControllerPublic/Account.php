<?php

/**
 *
 * @see XenForo_ControllerPublic_Account
 */
class ThemeHouse_UserDefSort_Extend_XenForo_ControllerPublic_Account extends XFCP_ThemeHouse_UserDefSort_Extend_XenForo_ControllerPublic_Account
{

    /**
     *
     * @see XenForo_ControllerPublic_Account::actionPreferences()
     */
    public function actionPreferences()
    {
        $response = parent::actionPreferences();
        //$response->subView->params['prefixes'] = $this->_getPrefixModel()->getAllPrefixes();
        if ($response instanceof XenForo_ControllerResponse_View) {
            $visitor = XenForo_Visitor::getInstance()->toArray();
            $sortOptions = unserialize($visitor['default_sort_options_th']);
            if (! empty($sortOptions)) {
                $response->subView->params['order'] = $sortOptions['order'];
                $response->subView->params['direction'] = $sortOptions['direction'];
                //$response->subView->params['prefix_id'] = $sortOptions['prefix_id'];
            }
        }
        return $response;
    }
    
    /**
     *
     * @see XenForo_ControllerPublic_Account::actionPreferencesSave()
     */
    public function actionPreferencesSave()
    {
    	$GLOBALS['XenForo_ControllerPublic_Account'] = $this;
    
    	return parent::actionPreferencesSave();
    } /* END actionPreferencesSave */ /* END actionPreferencesSave */ /* END actionPreferencesSave */
    
    /**
     * @return XenForo_Model_ThreadPrefix
     */
    protected function _getPrefixModel()
    {
    	return $this->getModelFromCache('XenForo_Model_ThreadPrefix');
    } /* END _getPrefixModel */
}