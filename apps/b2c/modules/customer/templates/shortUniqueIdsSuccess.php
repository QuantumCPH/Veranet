<?php
use_helper('I18N');
use_helper('Number');
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
echo __('Due to a technical problem, you cannot access the %1% data base currently. We do apologise. Please try later.',array('%1%'=>sfConfig::get('app_site_title')));
?>
