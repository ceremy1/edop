<?php
/**
 * Created by PhpStorm.
 * User: Çaglar
 * Date: 30.12.2016
 * Time: 22:50
 */
define('AJAX_SCRIPT', true);
global $CFG,$DB,$USER;
require_once ('../../../config.php');
require_once "locallib.php";
$context = context_system::instance();
require_capability('block/okulsis:sendsms',$context);
$filtre=required_param('filtre',PARAM_TEXT);
if($filtre == 'yetkiveral'){
    $bolum = required_param('bolum',PARAM_TEXT);
    $kurum = required_param('kurum',PARAM_TEXT);
    $ogretmen_id = required_param('ogretmen_id',PARAM_INT);
    if($DB->record_exists('block_okulsis_sms_setting',array('ogretmen_id'=>$ogretmen_id,'bolum'=>$bolum,'kurum'=>$kurum,'yetki_tur'=>1))){
        $DB->delete_records('block_okulsis_sms_setting',array('ogretmen_id'=>$ogretmen_id,'bolum'=>$bolum,'kurum'=>$kurum,'yetki_tur'=>1));
    }else{
        $record =new stdClass();
        $record->ogretmen_id =$ogretmen_id;
        $record->bolum=$bolum;
        $record->kurum=$kurum;
        $record->yetki_tur=1;
        $DB->insert_record('block_okulsis_sms_setting',$record);
    }
}else if ($filtre == 'yetkitesti'){
    $userid=$USER->id;
       if($DB->record_exists('block_okulsis_sms_basket',array('ownerid'=>$USER->id))){
           echo 1;
       }else{
           echo 2;
       }
       
    
    
}

