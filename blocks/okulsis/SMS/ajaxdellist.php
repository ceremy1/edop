<?php
/**
 * Created by PhpStorm.
 * User: Çaglar
 * Date: 28.12.2016
 * Time: 14:58
 */
define('AJAX_SCRIPT', true);
global $CFG,$DB;
require_once ('../../../config.php');
require_once "locallib.php";
$context = context_system::instance();
require_capability('block/okulsis:sendsms',$context);
$id = required_param('id',PARAM_INT);
$DB->delete_records('block_okulsis_sms_savebasket',array('id'=>$id));