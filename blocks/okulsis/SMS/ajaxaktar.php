<?php
/**
 * Created by PhpStorm.
 * User: Çaglar
 * Date: 24.12.2016
 * Time: 12:46
 */
define('AJAX_SCRIPT', true);

global $CFG,$DB,$USER;
require_once ('../../../config.php');
require_once "locallib.php";
$context = context_system::instance();
require_capability('block/okulsis:sendsms',$context);
$filtre = required_param('filtre',PARAM_TEXT);
if($filtre == 'aktar'){
    $ids = optional_param_array('ids', null, PARAM_INT);
    if(empty($ids)){
        echo 1;
    }else{
        $N = count($ids);
        $records=array();
        for ($a = 0; $a < $N; $a++) {
            $id = $ids[$a];
            $telno = $DB->get_field('user','phone1',array('id'=>$id));
            if(!empty($telno)){
                $record[$a] = new stdClass();
                if(!$DB->record_exists('block_okulsis_sms_basket',array('userid'=>$id,'ownerid'=>$USER->id))){
                    $record[$a]->userid = $id;
                    $record[$a]->ownerid =$USER->id;
                    $records[]=$record[$a];
                }

            }
        }
        $DB->insert_records('block_okulsis_sms_basket', $records);

    }
}else if($filtre == 'sil'){
    $id=optional_param('id',null,PARAM_INT);
    if(empty($id)){
        //toplu silme
        if($DB->record_exists('block_okulsis_sms_basket',array('ownerid'=>$USER->id))){
            $DB->delete_records('block_okulsis_sms_basket',array('ownerid'=>$USER->id));
        }else{
            echo 1;
        }

    }else{
        //tekil silme 
        $DB->delete_records('block_okulsis_sms_basket',array('id'=>$id,'ownerid'=>$USER->id));
    }

}


