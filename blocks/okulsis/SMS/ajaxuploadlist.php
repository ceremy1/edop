<?php
/**
 * Created by PhpStorm.
 * User: Çaglar
 * Date: 29.12.2016
 * Time: 20:07
 */
global $CFG,$DB,$USER;
require_once ('../../../config.php');
require_once "locallib.php";
define('AJAX_SCRIPT', true);
$context = context_system::instance();
require_capability('block/okulsis:sendsms',$context);
$id = required_param('id',PARAM_INT);
sepetbosalt();
$ids =$DB->get_field('block_okulsis_sms_savebasket','ids',array('id'=>$id));
$rs=explode(',',$ids);
$N = count($rs);
$records=array();
for ($a = 0; $a < $N; $a++) {
    if($rs[$a] > 0 ) {
        $userid = $rs[$a];
        $record[$a] = new stdClass();
        $record[$a]->userid = $userid;
        $record[$a]->ownerid = $USER->id;
        $records[] = $record[$a];
    }
}
$DB->insert_records('block_okulsis_sms_basket', $records);
echo'başarılı';

