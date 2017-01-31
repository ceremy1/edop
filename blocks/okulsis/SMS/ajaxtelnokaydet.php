<?php
/**
 * Created by PhpStorm.
 * User: Çaglar
 * Date: 23.01.2017
 * Time: 20:26
 */
define('AJAX_SCRIPT', true);
global $USER,$DB;
require_once ('../../../config.php');
require_once "locallib.php";
$context = context_system::instance();
require_capability('block/okulsis:sendsms',$context);
$telno = required_param('telno',PARAM_TEXT);
$record = new stdClass();
$record->userid = -1;
$record->ownerid= $USER->id;
$record->tel = $telno;
if(!$DB->record_exists('block_okulsis_sms_basket',array('tel'=>$telno))){
if(($DB->insert_record('block_okulsis_sms_basket',$record,true,false) > 0)){
    echo 1;
}
}else{
    echo 2;
}

        


