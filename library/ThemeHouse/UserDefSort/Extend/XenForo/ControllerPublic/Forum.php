<?php

/**
 *
 * @see XenForo_ControllerPublic_Forum
 */
class ThemeHouse_UserDefSort_Extend_XenForo_ControllerPublic_Forum extends XFCP_ThemeHouse_UserDefSort_Extend_XenForo_ControllerPublic_Forum
{

    /**
     *
     * @see XenForo_ControllerPublic_Forum::actionForum()
     */
    public function actionForum()
    {
        if (! XenForo_Visitor::getUserId()) {
            return parent::actionForum();
        }
        $sortOptions = array(
            'order' => $this->_input->filterSingle('order', XenForo_Input::STRING),
            'direction' => $this->_input->filterSingle('direction', XenForo_Input::STRING)
        // 'prefix_id' => $this->_input->filterSingle('prefix_id', XenForo_Input::STRING)
                );
        // if ($sortOptions['order'] || $sortOptions['direction'] || $sortOptions['prefix_id']) {
        if ($sortOptions['order'] || $sortOptions['direction']) {
            if (XenForo_Application::get('options')->th_uesrDefaultSort_updateInForums) {
                $this->updateUserSortPreferences($sortOptions);
            }
            return parent::actionForum();
        } else {
            $visitor = XenForo_Visitor::getInstance()->toArray();
            $oldOptions = unserialize($visitor['default_sort_options_th']);
            if ($visitor['override_sort_th'] && ! empty($oldOptions)) {
                foreach ($oldOptions as $key => $item) {
                    $this->_request->setParam($key, $item);
                }
            }
            return parent::actionForum();
        }
    } /* END actionForum */

    /**
     * Checks and updates the user sort options.
     *
     *
     * @param array $sortOptions            
     */
    public function updateUserSortPreferences(array $sortOptions)
    {
        $isDelete = 0;
        $update = 0;
        $defaultOptions = $this->_getDefaultUserSortOptions();
        $visitor = XenForo_Visitor::getInstance()->toArray();
        if ($visitor['override_sort_th']) {
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
                /* @var $dw XenForo_DataWriter_User */
                $dw = XenForo_DataWriter::create('XenForo_DataWriter_User');
                $dw->setExistingData(XenForo_Visitor::getUserId());
                if ($isDelete == 3) {
                    $dw->set('default_sort_options_th', array());
                } else {
                    $dw->set('default_sort_options_th', $sortOptions);
                }
                $dw->save();
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
            'direction' => 'desc'
        // 'prefix_id' => '0'
                );
    } /* END _getDefaultUserSortOptions */
}