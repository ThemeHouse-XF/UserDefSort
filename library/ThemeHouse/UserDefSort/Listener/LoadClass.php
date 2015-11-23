<?php

class ThemeHouse_UserDefSort_Listener_LoadClass extends ThemeHouse_Listener_LoadClass
{

    protected function _getExtendedClasses()
    {
        return array(
            'ThemeHouse_UserDefSort' => array(
                'datawriter' => array(
                    'XenForo_DataWriter_User'
                ), /* END 'datawriter' */
                'controller' => array(
                    'XenForo_ControllerPublic_Forum',
                    'XenForo_ControllerPublic_Account'
                ), /* END 'controller' */
            ), /* END 'ThemeHouse_UserDefSort' */
        );
    } /* END _getExtendedClasses */

    public static function loadClassDataWriter($class, array &$extend)
    {
        $extend = self::createAndRun('ThemeHouse_UserDefSort_Listener_LoadClass', $class, $extend, 'datawriter');
    } /* END loadClassDataWriter */

    public static function loadClassController($class, array &$extend)
    {
        $extend = self::createAndRun('ThemeHouse_UserDefSort_Listener_LoadClass', $class, $extend, 'controller');
    } /* END loadClassController */
}