<?php

/**
 *
 * @see XenForo_DataWriter_User
 */
class ThemeHouse_UserDefSort_Extend_XenForo_DataWriter_User extends XFCP_ThemeHouse_UserDefSort_Extend_XenForo_DataWriter_User
{

    /**
     *
     * @see XenForo_DataWriter_User::_getFields()
     */
    protected function _getFields()
    {
        $fields = parent::_getFields();
        $fields['xf_user_option']['override_sort_th'] = array(
            'type' => self::TYPE_BOOLEAN,
            'default' => 1
        );
        $fields['xf_user_option']['default_sort_options_th'] = array(
            'type' => self::TYPE_SERIALIZED,
            'default' => ''
        );
        return $fields;
    } /* END _getFields */

    /**
     *
     * @see XenForo_DataWriter_User::_preSave()
     */
    protected function _preSave()
    {
        if (isset($GLOBALS['XenForo_ControllerPublic_Account'])) {
            /* @var $input XenForo_ControllerPublic_Account */
            $input = $GLOBALS['XenForo_ControllerPublic_Account'];
            $sortOptions = array(
                'override_sort_th' => $input->getInput()->filterSingle('override_sort_th', XenForo_Input::BOOLEAN),
                'order' => $input->getInput()->filterSingle('order', XenForo_Input::STRING),
                'direction' => $input->getInput()->filterSingle('direction', XenForo_Input::STRING),
                //'prefix_id' => $input->getInput()->filterSingle('prefix_id', XenForo_Input::STRING)
            );
            //if ($sortOptions['order'] || $sortOptions['direction'] || $sortOptions['prefix_id']) {
            if ($sortOptions['override_sort_th']) {
                $this->updateUserSortPreferences($sortOptions);
            } else {
                $this->set('default_sort_options_th', array());
                $this->set('override_sort_th', 0);
            }
        }
        
        parent::_preSave();
    } /* END _preSave */

    /**
     * Checks and updates the user sort options.
     *
     * @param array $sortOptions            
     */
    public function updateUserSortPreferences(array $sortOptions)
    {
        unset($sortOptions['override_sort_th']);
        $isDelete = 0;
        $update = 0;
        $defaultOptions = $this->_getDefaultUserSortOptions();
        $visitor = XenForo_Visitor::getInstance()->toArray();
        $oldOptions = unserialize($visitor['default_sort_options_th']);
        if (! empty($oldOptions)) {
            foreach ($sortOptions as $key => $item) {
                if ($item == $defaultOptions[$key]) {
                    $isDelete ++;
                }
                if ($item != $oldOptions[$key]) {
                    $update ++;
                }
            }
        } else {
            foreach ($sortOptions as $key => $item) {
                if ($item != $defaultOptions[$key]) {
                    $update ++;
                }
            }
        }
        
        if ($update || $isDelete == 3) {
            if ($isDelete == 3) {
                $this->set('default_sort_options_th', array());
            } else {
                $this->set('default_sort_options_th', $sortOptions);
                $this->set('override_sort_th', 1);
            }
        }
    } /* END updateUserSortPreferences */

    /**
     * Returns the default XenForo sort options for threads.
     */
    protected function _getDefaultUserSortOptions()
    {
        return array(
            'order' => 'last_post_date',
            'direction' => 'desc',
            //'prefix_id' => '0'
        );
    } /* END _getDefaultUserSortOptions */
}