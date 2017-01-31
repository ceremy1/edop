<?php
/**
 * Created by PhpStorm.
 * User: Çaglar
 * Date: 01.01.2017
 * Time: 15:02
 */
//TODO:veli adı için ayarlara seçilimli menu koy catagori field id vd..... ceksin 
define('AJAX_SCRIPT', true);
global $CFG,$DB,$USER;
require_once ('../../../config.php');
require_once "locallib.php";
$context = context_system::instance();
require_capability('block/okulsis:sendsms',$context);
$filtre      =required_param('filtre',PARAM_TEXT);
if($filtre == 'confirm'){
    $isim = okulsis_getpersonlink($USER->id);
  $sayi = $DB->count_records('block_okulsis_sms_basket',array('ownerid'=>$USER->id));
    echo ("Sayın,".$isim." <strong>".$sayi."</strong> tane kullanıcıya mesaj göndermek üzeresiniz,Onaylıyor musunuz?");
}else if($filtre == 'post'){
    $ilksablon   =required_param('ilksablon',PARAM_TEXT);
    $sonsablon   =required_param('sonsablon',PARAM_TEXT);
    $mesaj       =required_param('mesaj',PARAM_TEXT);
    $veliadi     =required_param('veliadi',PARAM_TEXT);
    $msgheader   =required_param('msgheader',PARAM_TEXT);
    $tarih       =required_param('tarih',PARAM_TEXT);
    $datenetgsm  =required_param('datenetgsm',PARAM_TEXT);
    //netgsm servisi sms gönderme motoru
    if($CFG->block_okulsis_sms_api == 0){
        if($CFG->block_okulsis_sms_onoff == 1){
            // Username.
            $username = trim($CFG->block_okulsis_sms_username);
            // Password
            $password = trim($CFG->block_okulsis_sms_password);
            $sql="SELECT s.id,s.userid,u.phone1,d.data AS veliadi,s.tel
                  FROM {block_okulsis_sms_basket}  s
                  LEFT JOIN {user} u ON u.id=s.userid
                  LEFT JOIN {user_info_field} f ON f.shortname='VeliAd'
                  LEFT JOIN {user_info_data} d ON d.fieldid=f.id AND d.userid=s.userid
                  WHERE s.ownerid=$USER->id";
            $rs=$DB->get_records_sql($sql,array());
            $xmlmesaj="";
            foreach ($rs as $log){
                if(!empty($log->phone1)){

                  if(empty($log->veliadi)){
                      $xmlmesaj .= "<mp><msg><![CDATA[".replaceSpace($ilksablon.$mesaj." ".$sonsablon)."]]></msg><no>".$log->phone1."</no></mp>";
                  }else{
                      if($veliadi == 'on'){
                          $xmlmesaj .= "<mp><msg><![CDATA[Sayın ".$log->veliadi.";".replaceSpace($ilksablon.$mesaj." ".$sonsablon)."]]></msg><no>".$log->phone1."</no></mp>";
                      }else{
                          $xmlmesaj .= "<mp><msg><![CDATA[".replaceSpace($ilksablon.$mesaj." ".$sonsablon)."]]></msg><no>".$log->phone1."</no></mp>";
                      }
                  }
                }
                if($log->userid = -1){
                    $xmlmesaj .= "<mp><msg><![CDATA[".replaceSpace($ilksablon.$mesaj." ".$sonsablon)."]]></msg><no>".$log->tel."</no></mp>";
                }
            }
            //echo($xmlmesaj);
            $url='http://api.netgsm.com.tr/xmlbulkhttppost.asp';

       $xml='<?xml version="1.0" encoding="UTF-8"?>
                <mainbody>
	            <header>
		        <company dil="TR">NETGSM</company>
                <usercode>'.$username.'</usercode>
                <password>'.$password.'</password>';
       if($tarih == 'future'){

       $xml .='<startdate>'.$datenetgsm.'</startdate>';
       }else{
       $xml .='<startdate></startdate>';
       }
       $xml .='<stopdate></stopdate>
	           <type>n:n</type>
               <msgheader>'.$msgheader.'</msgheader>
               </header>
		       <body>'.$xmlmesaj.'</body>
               </mainbody>';
           // echo($xml);
       //hadi yollayalım
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,$url);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml"));
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
            $result = curl_exec($ch);
            $res = explode(" ",$result);
            $record =new stdClass();
            $record->gonderen_id = $USER->id;
            $record->code =$res[0];
            if($res[0] =="00" || $res[0] =="01" || $res[0] =="02"){
                $bulkid =$res[1];
                $record->bulkid=$bulkid;
                echo('<div class="text-center"><img class="img-responsive" style="width:64px; height:64px;" src="/blocks/okulsis/pic/success.png"><h5>&nbsp;&nbsp;SMS Başarıyla Servis Sağlayıcınıza İletildi.Ayrıntılar için Sms Raporlarına Bakınız</h5></div>');
            }else{
                $record->bulkid=null;
                echo('<div class="text-center"><img class="img-responsive" style="width:64px; height:64px;" src="/blocks/okulsis/pic/fail.png"><h5>&nbsp;&nbsp;!!SMS Gönderme BAŞARISIZ!!.Ayrıntılar için Sms Raporlarına Bakınız</h5></div>');
            }
            
            ($tarih == 'future') ? $record->future =1 : $record->future =0;
            $record->date=time();
            $DB->insert_record('block_okulsis_sms_rapor',$record,false);
        }else{
            echo("Mesaj Servisi Devredışı Bırakılmış Sistem Yöneticisine Başvurun");
        }
    }
}


