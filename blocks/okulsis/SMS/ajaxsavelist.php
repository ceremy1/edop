<?php
/**
 * Created by PhpStorm.
 * User: Çaglar
 * Date: 28.12.2016
 * Time: 12:56
 */
define('AJAX_SCRIPT', true);
global $CFG,$DB,$USER;
require_once ('../../../config.php');
require_once "locallib.php";
$context = context_system::instance();
require_capability('block/okulsis:sendsms',$context);
$name =required_param('name',PARAM_TEXT);
if(!$DB->record_exists('block_okulsis_sms_savebasket',array('name'=>$name))){
    $sql="SELECT ownerid,
          GROUP_CONCAT(userid) AS ids
          FROM {block_okulsis_sms_basket} 
          WHERE ownerid=$USER->id 
          GROUP BY ownerid";
    $DB->execute('SET SESSION group_concat_max_len = 20000',array());
    $rs=$DB->get_record_sql($sql,array());
    if(!empty($rs->ids)){
        $record =new stdClass();
        $record->name =$name;
        $record->ids=$rs->ids;
        $record->date= time();  
        if($DB->insert_record('block_okulsis_sms_savebasket',$record)>0){
            echo 4;
        }else{
            echo 3;
        }
    }else{
        echo 2;
    }
}else{
    echo 1;
}
