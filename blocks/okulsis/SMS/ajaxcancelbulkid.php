<?php
/**
 * Created by PhpStorm.
 * User: Çaglar
 * Date: 02.01.2017
 * Time: 19:56
 */
define('AJAX_SCRIPT', true);
global $CFG,$DB;
require_once ('../../../config.php');
require_once "locallib.php";
$context = context_system::instance();
require_capability('block/okulsis:sendsms',$context);
$bulkid = required_param('id',PARAM_INT);
$username =$CFG->block_okulsis_sms_username;
$password =$CFG->block_okulsis_sms_password;
$url="https://api.netgsm.com.tr/gorevislem.asp";
$xml='<?xml version="1.0" encoding="UTF-8"?>
     <mainbody>
    <header>
        <company>Netgsm</company>
        <usercode>'.$username.'</usercode>
        <password>'.$password.'</password>
        <gorevid>'.$bulkid.'</gorevid>
        <type>0</type>
        </header>
    </mainbody>';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml"));
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
$result =curl_exec($ch);
$res = explode(" ",$result);
if($res[0] == '00'){
    $DB->delete_records('block_okulsis_sms_rapor',array('bulkid'=>$bulkid,'future'=>1));
    echo('<div class="text-center"><img class="img-responsive" style="width:64px; height:64px;" src="/blocks/okulsis/pic/success.png"><h5>&nbsp;&nbsp;İleri Tarihli SMS başarıyla iptal edildi</h5></div>');
    }else{
    echo('<div class="text-center"><img class="img-responsive" style="width:64px; height:64px;" src="/blocks/okulsis/pic/fail.png"><h5>&nbsp;&nbsp;'.cancelbulkid($res[0]).'</h5></div>');
    if($res[0] == '60'){
        $DB->delete_records('block_okulsis_sms_rapor',array('bulkid'=>$bulkid,'future'=>1));
    }
}

function cancelbulkid($error){
    switch ($error){
        case '30':
            return 'Geçersiz kullanıcı adı , şifre veya kullanıcınızın API erişim izninin olmadığını gösterir.';
            break;
        case '40':
            return 'API ile hesap erişim izninin olmadığını veya IP sınırlamanız olduğunu ifade eder.';
            break;
        case '60':
            return 'Gönderdiğiniz görevidye ait kayıt olmadığını ifade eder.';
            break;
        case '70':
            return 'Hatalı sorgulama. Gönderdiğiniz parametrelerden birisi hatalı veya zorunlu alanlardan birinin eksik olduğunu ifade eder.';
            break;
        default:
            return  'Hata Oluştu SMS servis sağlayıcınıza başvurun ';
            break;
           }

}






