<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.



require_once(dirname(__FILE__).'/../../config.php');


/* SMS_NetGsm Block
 * SMS_NetGsm block eklentisi tek yollu istenen kullanıcı rollerine sms göndermeye yarar
 * @package blocks
 * @author: Çağlar Mersinli
 * @date: 03.07.2016
*/
/**
 * @param $to
 * @param $message
 * @param $header
 * @return mixed|string
 */
function send_sms_netgsm($to, $message,$header) {
	global $CFG;
	/**
	 *
	 if($CFG->block_sms_api_method==1){

	if($CFG->block_sms_api_onoff==0){
		return "33";
		
	}
    Kullanıcı Numaraları
    $numbers = '';
    foreach($to as $num){
        if($numbers == '') {
            $numbers =  $num;
        }
        else {
            $numbers .=  ','.$num;
        }
    }

    // Username.
    $username = $CFG->block_sms_api_username;
    // Password
    $password = $CFG->block_sms_api_password;
   
    
    $header=str_replace("'","",$header);
	$header = html_entity_decode($header, ENT_COMPAT, "UTF-8");
	$header = rawurlencode($header); 
    
    $message = str_replace("'","",$message);
	$message = html_entity_decode($message, ENT_COMPAT, "UTF-8"); 
    $message = rawurlencode($message); 
    $url = "https://api.netgsm.com.tr/bulkhttppost.asp?usercode=".urlencode($username)."&password=".urlencode($password)."&gsmno=".urlencode($numbers)."&message=".$message."&msgheader=".$header."&dil=TR";
	$ch = curl_init(); 
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
     $output=curl_exec($ch);
    curl_close($ch);
	
    return $output;
	}
	*/
	 if($CFG->block_sms_api_method==0){
		global $CFG;
	if($CFG->block_sms_api_onoff==0){
		return "33";
		
	}
		foreach ($message as $msg) {
			$result = ltrim(rtrim(trim($msg, " ' ")));
			$lastresult=html_entity_decode($result,ENT_COMPAT, "UTF-8");
			$resultmesage[] = $lastresult;
		}


	$combine =array_combine($to,$resultmesage);

// Username.
    $username = $CFG->block_sms_api_username;
    // Password
    $password = $CFG->block_sms_api_password;
   
    
    $header=str_replace("'","",$header);
	$header = html_entity_decode($header, ENT_COMPAT, "UTF-8");
	$header = rawurlencode($header);
	$xmlmesaj="";
		 foreach ($combine as $tel=>$mes){
			 $xmlmesaj .= "<mp><msg><![CDATA[".$mes."]]></msg><no>".$tel."</no></mp>";

		 }


   $url='http://api.netgsm.com.tr/xmlbulkhttppost.asp';
	$xml='<?xml version="1.0" encoding="UTF-8"?>
<mainbody>
	<header>
		<company dil="TR">NETGSM</company>
        <usercode>'.$username.'</usercode>
        <password>'.$password.'</password>
		<startdate></startdate>
		<stopdate></stopdate>
	    <type>n:n</type>
        <msgheader>'.$header.'</msgheader>
        </header>
		<body>'
		.$xmlmesaj.
		'</body>
</mainbody>';
		// return $xml;
	$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml"));
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
		$result = curl_exec($ch);
		return $result;

		
	}

 }
function block_sms_rapor($bulk,$v){
	 global $CFG;
	 // Username.
    $username = $CFG->block_sms_api_username;
    // Password
    $password = $CFG->block_sms_api_password;
	
	sleep($CFG->block_sms_api_time);
	$url = "https://api.netgsm.com.tr/httpbulkrapor.asp?usercode=".$username."&password=".$password."&bulkid=".$bulk."&type=0&status=100&version=".$v;
	
	$ch = curl_init(); 
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
     $output=curl_exec($ch);
    curl_close($ch);
	$bulkarray= explode("<br>", $output,-1);
	// foreach ($bulkarray as $item){
		// $arraysatir[]=$item;
	// }
	
	return $bulkarray;
	
}
function block_sms_bakiye(){
	 global $CFG;
	 // Username.
    $username = $CFG->block_sms_api_username;
    // Password
    $password = $CFG->block_sms_api_password;
	
	sleep($CFG->block_sms_api_time);
	$url = "http://api.netgsm.com.tr/get_kredi.asp?usercode=".$username."&password=".$password;
	
	$ch = curl_init(); 
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
     $output=curl_exec($ch);
    curl_close($ch);
	$bulkarray= explode(" ", $output);
	
	return $bulkarray[1];
	
}
function get_bulkrapor(){
	global $CFG;
	$username = $CFG->block_sms_api_username;
    // Password
    $password = $CFG->block_sms_api_password;
	 
	$url = "https://api.netgsm.com.tr/httpbulkjob.asp?usercode=".$username."&password=".$password."&type=1&version=1&page=1";
	$ch = curl_init(); 
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
	 curl_setopt($ch,CURLOPT_BINARYTRANSFER,true);
     $output=curl_exec($ch);
    curl_close($ch);
	
	return $output;
	 
	 
 }
function block_sms_durum($durumid) {
	if($durumid==0){
	$message='<span class="label label-success">İletilmeyi Bekliyor..</span>';
	}
	if($durumid==1){
	$message='<span class="label label-success">İletildi</span>';
	}
	if($durumid==2){
	$message='<span class="label label-warning">Zaman Aşımı</span>';
	}
	if($durumid==3){
	$message='<span class="label label-important">Hatalı veya Kısıtlı Numara</span>';
	}
	if($durumid==4){
	$message='<span class="label label-important">Operatöre Gönderilemedi</span>';
	}
	if($durumid==11){
	$message='<span class="label label-important">Operatör Kabul etmedi</span>';
	}
	if($durumid==12){
	$message='<span class="label label-important">Gönderim Hatası</span>';
	}
	if($durumid==13){
	$message='<span class="label label-warning">Mükerrer Gönderim</span>';
	}
	if($durumid==103){
	$message='<span class="label label-warning">Tüm Görev Başarısız</span>';
	}
	return $message;

}
function block_sms_operator($durumid) {
	global $CFG;
	if($durumid==10){
	$message='<img src="'.$CFG->wwwroot.'/blocks/sms/pic/vodafone.png"></img>';
	}
	if($durumid==20){
	$message='<img src="'.$CFG->wwwroot.'/blocks/sms/pic/avea.png"></img>';
	}
	if($durumid==30){
	$message='<img src="'.$CFG->wwwroot.'/blocks/sms/pic/turkcell.png"></img>';
	}
	if($durumid==40){
	$message='<span class="label label-info">Diğer</span>';
	}
	if($durumid==50){
	$message='<img src="'.$CFG->wwwroot.'/blocks/sms/pic/ttnet.png"></img>';
	}
	if($durumid==60){
	$message='<span class="label label-info">Diğer</span>';
	}
	if($durumid==70){
	$message='<span class="label label-info">Diğer</span>';
	}
	return $message;

}
function block_sms_hata($durumid) {
	if($durumid==0){
	$message='<span class="label label-success">Hata Yok</span>';
	}
	if($durumid==101){
	$message='<span class="label label-warning">Mesaj kutusu Dolu</span>';
	}
	if($durumid==102){
	$message='<span class="label label-warning">kapalı yada kapsama dışında</span>';
	}
	if($durumid==103){
	$message='<span class="label label-warning">Meşgul</span>';
	}
	if($durumid==104){
	$message='<span class="label label-warning">Hat aktif değil</span>';
	}
	if($durumid==105){
	$message='<span class="label label-warning">Hatalı Numara</span>';
	}
	if($durumid==106){
	$message='<span class="label label-warning">SMS red ,Karaliste</span>';
	}
	if($durumid==111){
	$message='<span class="label label-warning">Zaman Aşımı</span>';
	}
	if($durumid==112){
	$message='<span class="label label-warning">SMS gönderimine kapalı</span>';
	}
	if($durumid==113){
	$message='<span class="label label-warning">Mobil Cihaz Desteklemiyor</span>';
	}
	if($durumid==114){
	$message='<span class="label label-warning">Yönlendirme Başarısız</span>';
	}
	if($durumid==115){
	$message='<span class="label label-warning">Çağrı Yasaklandı</span>';
	}
	if($durumid==116){
	$message='<span class="label label-important">Hatalı No</span>';
	}
	if($durumid==117){
	$message='<span class="label label-warning">Yasadışı Abone</span>';
	}
	if($durumid==119){
	$message='<span class="label label-important">Sistem Hatası</span>';
	}
	
	return $message;

}
function block_sms_explodelesson(){
	global $CFG,$USER,$DB;
	$out=array();
	if(is_siteadmin()){
		$lesseon = explode(",",$CFG->block_sms_api_ders);
		foreach ($lesseon as $key => $value){
			$out[$value]= $value;

		}
	}else{

		$rs=$DB->get_records_menu('block_sms_settings',array('ogretmen_id'=>$USER->id,'yetki_tur'=>1),'yetkisi','id,yetkisi');
		foreach ($rs as $key => $value){
			$out[$value]= $value;

		}
	}


 return $out;
}
function block_sms_gonderen(){
	global $DB;
	$sql="SELECT gonderen_id,gonderen_ad,gonderen_soyad FROM {block_sms_kaydet} GROUP BY gonderen_id";
	$out=array();
	$rs = $DB->get_records_sql($sql, array());

		foreach ($rs as $log) {
			if(!empty($log->gonderen_id)) {
			$out[$log->gonderen_id] = $log->gonderen_ad . " " . $log->gonderen_soyad;
		}
	   }

	return $out;
}
function block_sms_explodeilksablon(){
	global $CFG;
	$ilksablon = explode("|",$CFG->block_sms_api_ilksablon);
	foreach ($ilksablon as $key => $value){
		$out[$value]= $value;

	}
	return $out;
}
function block_sms_explodesonsablon(){
	global $CFG;
	$sonsablon = explode("|",$CFG->block_sms_api_sonsablon);
	foreach ($sonsablon as $key => $value){
		$out[$value]= $value;

	}
	return $out;
}
function quizwithoutattempsql($qid){
	global $DB;
	//course id bulalım 
	
	$courseid=$DB->get_field("quiz","course",array("id"=>$qid), $strictness=IGNORE_MISSING);
// sql oluşturalım

	$sql="SELECT DISTINCT
   user2.id ,
   user2.firstname,
   user2.lastname ,
   user2.phone1,
   coh.name as sinif
  FROM {user_enrolments} AS ue
  JOIN  {enrol} AS e ON e.id = ue.enrolid
  JOIN  {course} AS c ON c.id = e.courseid
  JOIN  {user} AS user2 ON user2 .id = ue.userid
  LEFT JOIN {cohort_members} co ON co.userid = user2.id
  LEFT JOIN {cohort} coh ON coh.id = co.cohortid
  WHERE c.id=$courseid AND user2.deleted=0 AND user2.suspended=0 AND e.status=0  
  AND ue.userid
  NOT IN (SELECT qa.userid FROM  {quiz_attempts} AS qa
  JOIN  {quiz} AS q ON qa.quiz = q.id
  JOIN  {course} AS c ON q.course = c.id
  WHERE c.id = $courseid AND q.id=$qid AND qa.state='finished')
  AND ue.userid
  IN (SELECT DISTINCT u.id 
  FROM {user} u
  JOIN {user_enrolments} ue ON ue.userid = u.id
  JOIN {enrol} e ON e.id = ue.enrolid
  JOIN {role_assignments} ra ON ra.userid = u.id
  JOIN {context} ct ON ct.id = ra.contextid AND ct.contextlevel = 50
  JOIN {course} c ON c.id = ct.instanceid AND e.courseid = c.id
  JOIN {role} r ON r.id = ra.roleid AND r.archetype = 'student'
  WHERE e.status = 0 AND u.suspended = 0 AND u.deleted = 0
  AND (ue.timeend = 0 OR ue.timeend > NOW()) AND ue.status = 0
  AND c.id=$courseid)
  AND co.cohortid
IN (SELECT DISTINCT MIN(c.cohortid) FROM {cohort_members} c WHERE c.userid=user2.id)
  ";

	return $sql;
	
}
function block_sms_nisan($id){
	global $DB;
	$sql= "SELECT DISTINCT
   user2.id ,
   user2.firstname,
   user2.lastname ,
   user2.phone1,
   coh.name as sinif,
   COUNT(n.nisan_id) AS adet
   FROM {block_nisan_rozet_atama} n
   LEFT JOIN {user}	 user2 ON user2.id = n.ogrid
   LEFT JOIN {cohort_members} co ON co.userid = user2.id
   LEFT JOIN {cohort} coh ON coh.id = co.cohortid 
   WHERE n.nisan_id = $id
   AND co.cohortid
IN (SELECT DISTINCT MIN(c.cohortid) FROM {cohort_members} c WHERE c.userid=user2.id)
   GROUP BY n.ogrid
	";
	return $sql;
}
function block_sms_rozet($id){
	global $DB;
	$sql= "SELECT DISTINCT
   user2.id ,
   user2.firstname,
   user2.lastname ,
   user2.phone1,
   coh.name as sinif
   FROM {badge_issued} bi
   LEFT JOIN {user}	 user2 ON user2.id = bi.userid
   LEFT JOIN {cohort_members} co ON co.userid = user2.id
   LEFT JOIN {cohort} coh ON coh.id = co.cohortid 
   WHERE bi.badgeid = $id
   AND co.cohortid
IN (SELECT DISTINCT MIN(c.cohortid) FROM {cohort_members} c WHERE c.userid=user2.id)
   GROUP BY bi.userid
	";
	return $sql;
}
function getmsgheader(){
	global $CFG;
	$usercode = $CFG->block_sms_api_username;
	$password = $CFG->block_sms_api_password;
	$url ="https://api.netgsm.com.tr/get_msgheader.asp?usercode=".urlencode($usercode)."&password=".urldecode($password);
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
	$output=curl_exec($ch);
	curl_close($ch);
	if($output!=30){
		$msgheaders = explode("<br>", $output,-1);

		foreach ($msgheaders as $item) {
			$msgheader[$item] = $item;
		}
	}else{
		$msgheader[]="Alınamadı";
	}
	return $msgheader;

}
function getmsgheaderdb(){
	global $CFG,$USER,$DB;
	$msgheader=array();
	if(is_siteadmin()) {

		$usercode = $CFG->block_sms_api_username;
		$password = $CFG->block_sms_api_password;
		$url = "https://api.netgsm.com.tr/get_msgheader.asp?usercode=" . urlencode($usercode) . "&password=" . urldecode($password);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$output = curl_exec($ch);
		curl_close($ch);
		if ($output != 30) {
			$msgheaders = explode("<br>", $output, -1);

			foreach ($msgheaders as $item) {
				$msgheader[$item] = $item;
			}
		} else {
			$msgheader[] = "Alınamadı";
		}
	}else{
		$rs=$DB->get_records_menu('block_sms_settings',array('ogretmen_id'=>$USER->id,'yetki_tur'=>2),'yetkisi','id,yetkisi');
		foreach ($rs as $key => $value){
			$msgheader[$value]= $value;

		}

	}
	return $msgheader;

}
function deloldmessage(){
	global $DB,$CFG;

	if($CFG->block_sms_api_oldmessage ==0){
		$date = strtotime("-1 month");


	}
	else if($CFG->block_sms_api_oldmessage ==1){
		$date = strtotime("-3 month");


	}
	else if($CFG->block_sms_api_oldmessage ==2){

		$date = strtotime("-6 month");

	}
	else if($CFG->block_sms_api_oldmessage ==3){

		$date = strtotime("-1 year");

	}

	$select="tarih <= $date ";
	$DB->delete_records_select('block_sms_kaydet',$select );

return true;

}
function selectcourse(){
	global $DB;
	$sql="SELECT id,fullname from {course}";
	$count  =  $DB->record_exists_sql($sql, array ($params=null));
	if($count >= 1) {
		$rs = $DB->get_recordset_sql($sql, array(), null, null);
     $html='<option value="-1">Kurs Seçiniz:</option>';
		foreach ($rs as $log){
			$html .='<option value="'.$log->id.'">'.$log->fullname.'</option>';
           }
      $rs->close();


	}else{
     $html='<option value="-2">Seçilecek Kurs Bulunamadı!</option>';
		
	}
	return $html;
}
function selectnisan(){
	global $DB;
	$rs = $DB->get_records('block_nisan_rozet_nisan',array(),null,'id,name',null,null);
	if($rs){
		$html='<option value="-1">Nişan Seçiniz:</option>';
		foreach ($rs as $log){
			$html .='<option value="'.$log->id.'">'.$log->name.'</option>';
		}
	}else{
		$html ='<option value="-2">Seçilecek Nişan Bulunamadı!</option>';
	}
	return $html;
}
function selectrozet(){
	global $DB;
	$rs = $DB->get_records('badge',array(),null,'id,name',null,null);
	if($rs){
		$html='<option value="-1">Rozet Seçiniz:</option>';
		foreach ($rs as $log){
			$html .='<option value="'.$log->id.'">'.$log->name.'</option>';
		}
	}else{
		$html ='<option value="-2">Seçilecek Rozet Bulunamadı!</option>';
	}
	return $html;
}
function ogretmensec(){
	global $DB;
	$sql="SELECT DISTINCT u.id,u.firstname,u.lastname 
  FROM {user} u
  JOIN {role_assignments} ra ON ra.userid = u.id
  JOIN {role} r ON r.id = ra.roleid AND r.archetype = 'coursecreator'
  WHERE u.suspended = 0 AND u.deleted = 0
 ";
	$rs = $DB->get_recordset_sql($sql, array(), null, null);
	if ($rs->valid()) {
		$html='<option value="-1">Öğretmen Seçiniz:</option>';
		foreach ($rs as $log){
			$html .='<option value="'.$log->id.'">'.$log->firstname.' '.$log->lastname.'</option>';
		}


	}else{
		$html='<option value="-2">Seçilecek Öğretmen Bulunamadı!</option>';
	}



	return $html;
}
function quiz_not($qid,$from,$to){
	global $DB;
	//course id bulalım 

	$courseid=$DB->get_field("quiz","course",array("id"=>$qid), $strictness=IGNORE_MISSING);
	// sql oluşturalım

	$sql="SELECT DISTINCT
   user2.id ,
   user2.firstname,
   user2.lastname ,
   user2.phone1,
   coh.name AS sinif
  FROM {user_enrolments} AS ue
  JOIN  {enrol} AS e ON e.id = ue.enrolid
  JOIN  {course} AS c ON c.id = e.courseid
  JOIN  {user} AS user2 ON user2 .id = ue.userid
  LEFT JOIN {cohort_members} co ON co.userid = user2.id
  LEFT JOIN {cohort} coh ON coh.id = co.cohortid
  WHERE c.id=$courseid AND user2.deleted=0 AND user2.suspended=0 AND e.status=0  
  AND ue.userid
   IN (SELECT qg.userid FROM  {quiz_grades} AS qg
  JOIN  {quiz} AS q ON qg.quiz = q.id
  JOIN  {course} AS c ON q.course = c.id
  WHERE c.id = $courseid AND q.id=$qid  AND (qg.grade BETWEEN $from AND $to ))
  AND ue.userid
  IN (SELECT DISTINCT u.id 
  FROM {user} u
  JOIN {user_enrolments} ue ON ue.userid = u.id
  JOIN {enrol} e ON e.id = ue.enrolid
  JOIN {role_assignments} ra ON ra.userid = u.id
  JOIN {context} ct ON ct.id = ra.contextid AND ct.contextlevel = 50
  JOIN {course} c ON c.id = ct.instanceid AND e.courseid = c.id
  JOIN {role} r ON r.id = ra.roleid AND r.archetype = 'student'
  WHERE e.status = 0 AND u.suspended = 0 AND u.deleted = 0
  AND (ue.timeend = 0 OR ue.timeend > NOW()) AND ue.status = 0
  AND c.id=$courseid)";

	return $sql;

}
/**
 * @param $bid is course section id 
 * @return string $sql 
 */
function block_sms_ortalama($bid){
	global $DB;
	//course id bulalım 
	$courseid = $DB->get_field("course_sections","course",array("id"=>$bid), $strictness=IGNORE_MISSING);
	$sql1="SELECT COUNT(q.id) AS id 
    FROM {course_modules} cm
    JOIN {modules} m ON m.id= cm.module
    LEFT JOIN {quiz} q ON q.id = cm.instance
    WHERE m.name = 'quiz' AND cm.section=?";
	$quizsayisi=$DB->count_records_sql($sql1,array($bid));
	$sql="
SELECT DISTINCT
  user2.id ,
  user2.firstname,
  user2.lastname ,
  user2.phone1,
  coh.name AS sinif,
  (CASE WHEN ISNULL(SUM(qg.grade)) THEN 0 ELSE SUM(qg.grade)/$quizsayisi END) AS puan

FROM {user_enrolments} AS ue
  JOIN  {enrol} AS e ON e.id = ue.enrolid
  JOIN  {course} AS c ON c.id = e.courseid
  JOIN {course_modules} AS cm ON cm.course=c.id
  JOIN  {user} AS user2 ON user2.id = ue.userid
  JOIN {modules} AS m ON m.id=cm.module
  JOIN {quiz} AS q ON q.course = c.id AND q.id=cm.instance
  LEFT JOIN  {quiz_grades} AS qg ON  qg.quiz=q.id AND qg.userid=ue.userid
  LEFT JOIN {cohort_members} co ON co.userid = user2.id
  LEFT JOIN {cohort} coh ON coh.id = co.cohortid
WHERE c.id=$courseid AND m.name = 'quiz' AND cm.section=$bid AND user2.deleted=0 AND user2.suspended=0 AND e.status=0
       AND ue.userid
          IN (SELECT DISTINCT u.id
              FROM {user} u
                JOIN {user_enrolments} ue ON ue.userid = u.id
                JOIN {enrol} e ON e.id = ue.enrolid
                JOIN {role_assignments} ra ON ra.userid = u.id
                JOIN {context} ct ON ct.id = ra.contextid AND ct.contextlevel = 50
                JOIN {course} c ON c.id = ct.instanceid AND e.courseid = c.id
                JOIN {role} r ON r.id = ra.roleid AND r.archetype = 'student'
              WHERE e.status = 0 AND u.suspended = 0 AND u.deleted = 0
                    AND (ue.timeend = 0 OR ue.timeend > NOW()) AND ue.status = 0
                    AND c.id=$courseid)
                    AND co.cohortid
IN (SELECT DISTINCT MIN(c.cohortid) FROM {cohort_members} c WHERE c.userid=user2.id)
GROUP BY user2.id";
	return $sql;

}
function mesajupdate($allid,$tarih,$mesaj){
global $DB;
	//$allid="125,234,45";
//allid parçalayalım
	$rs = explode(',',$allid);
	foreach ($rs as $key=>$value){
		$record=new stdClass();
		$record->mesaj=$mesaj;
		$record->tarih=$tarih;
		$record->id=$value;
		$DB->update_record('block_sms_kaydet',$record);
		/*$sql="UPDATE FROM {block_sms_kaydet} SET mesaj=".$mesaj.",tarih=".$tarih." WHERE id=".$value;
		$DB->execute($sql,null);*/
	}




	//return $rs[0];



}
function gonderilenmesajpasif($tel,$date){
	global $DB;
foreach ($tel as $log){
	$sql="UPDATE {block_sms_kaydet} SET gonderildimi=1 WHERE tel=? AND tarih=?";
	try{
		$DB->execute($sql,array('tel'=>$log,'tarih'=>$date));
	}catch (Exception $e){
	$e->getMessage();
	}

}
}
function block_sms_tableolusturma($sql){
	global $DB,$CFG;
	$table = new html_table();
	$table->attributes = array("name" => "userlist");
	$table->attributes = array("id" => "userlist");
	$table->attributes = array("class" => "table table-striped table-bordered table-condensed");
	$table->data  = array();
	$count  =  $DB->record_exists_sql($sql, array ($params=null));
	if($count >= 1) {
		$table->head  = array(get_string('serial_no', 'block_sms'),
				get_string('name', 'block_sms'),
				get_string('lastname', 'block_sms'),
				"Sınıf",
				get_string('cell_no', 'block_sms'),
				"<label class='checkbox'><input type='checkbox' class='usercheckboxall' id='selectall' name='' value=''/></label>");

		$table->size  = array('10%','20%','20%','15%','25%','5%');
		$table->align  = array('center', 'left', 'center', 'center');
		$rs = $DB->get_recordset_sql($sql, array(), null, null);
		$i=0;
		//echo quiz_not($id,$from,$to);
		foreach ($rs as $log) {
			if($log->id !=1){
				// $fullname = $log->firstname;
				$row = array();
				$row[] = ++$i;
				$row[] = $log->firstname;
				$row[] = $log->lastname;
				$row[] = $log->sinif;
				if (!empty($log->phone1)){
					$row[] = $log->phone1;
				}else{

					$row[] ="<a target='_blank' href='".$CFG->wwwroot."/user/editadvanced.php?id=".$log->id."' class='btn btn-primary'>Ekle</a>";
				}
				$row[] = "<input type='checkbox' class='usercheckbox' name='user[]' value='$log->id'/>";
				$table->data[] = $row;
			}
		}
		$rs->close();
	}
	else {
		$row = array();
		$row[] = "<div id='load-users' style='border: 1px solid;margin: 10px 0px;padding:15px 10px 15px 50px;background-repeat: no-repeat;background-position: 10px center;color: #00529B;background-image: url(".'pic/info.png'."); background-color: #BDE5F8;border-color: #3b8eb5;'>Kayıt Bulunamadı!</div>";
		$table->data[] = $row;
	}

	return $table;
}
function block_sms_tableolusturmaortalama($sql,$from,$to){
	global $DB,$CFG;
	$table = new html_table();
	$table->attributes = array("name" => "userlist");
	$table->attributes = array("id" => "userlist");
	$table->attributes = array("class" => "table table-striped table-bordered table-condensed");
	$table->data  = array();
	$count  =  $DB->record_exists_sql($sql, array ($params=null));
	if($count >= 1) {
		$table->head  = array(get_string('serial_no', 'block_sms'),
				get_string('name', 'block_sms'),
				get_string('lastname', 'block_sms'),
				"Sınıf",
				get_string('cell_no', 'block_sms'),
				"<label class='checkbox'><input type='checkbox' class='usercheckboxall' id='selectall' name='' value=''/></label>");

		$table->size  = array('10%','20%','20%','15%','25%','5%');
		$table->align  = array('center', 'left', 'center', 'center');
		$rs = $DB->get_recordset_sql($sql, array(), null, null);
		$i=0;
		//echo quiz_not($id,$from,$to);
		foreach ($rs as $log) {
			if($log->id !=1 && $log->puan >= $from && $log->puan <= $to){
				// $fullname = $log->firstname;
				$row = array();
				$row[] = ++$i;
				$row[] = $log->firstname;
				$row[] = $log->lastname;
				$row[] = $log->sinif;
				if (!empty($log->phone1)){
					$row[] = $log->phone1;
				}else{

					$row[] ="<a target='_blank' href='".$CFG->wwwroot."/user/editadvanced.php?id=".$log->id."' class='btn btn-primary'>Ekle</a>";
				}
				$row[] = "<input type='checkbox' class='usercheckbox' name='user[]' value='$log->id'/>";
				$table->data[] = $row;
			}
		}
		$rs->close();
	}
	else {
		$row = array();
		$row[] = "<div id='load-users' style='border: 1px solid;margin: 10px 0px;padding:15px 10px 15px 50px;background-repeat: no-repeat;background-position: 10px center;color: #00529B;background-image: url(".'pic/info.png'."); background-color: #BDE5F8;border-color: #3b8eb5;'>Kayıt Bulunamadı!</div>";
		$table->data[] = $row;
	}

	return $table;
}
function block_sms_nisantablo($sql){
	global $DB,$CFG;
	$table = new html_table();
	$table->attributes = array("name" => "userlist");
	$table->attributes = array("id" => "userlist");
	$table->attributes = array("class" => "table table-striped table-bordered table-condensed");
	$table->data  = array();
	$count  =  $DB->record_exists_sql($sql, array ($params=null));
	if($count >= 1) {
		$table->head  = array(get_string('serial_no', 'block_sms'),
				get_string('name', 'block_sms'),
				get_string('lastname', 'block_sms'),
				"Sınıf",
				get_string('cell_no', 'block_sms'),"Adet",
				"<div class='text-center'><input type='checkbox' class='usercheckboxall' id='selectall' name='' value=''/></div>");

		$table->size  = array('5%','20%','20%','20%','20%','5%','5%');
		$table->align  = array('center', 'center', 'center', 'center','center','center','center');
		$rs = $DB->get_recordset_sql($sql, array(), null, null);
		$i=0;
		foreach ($rs as $log) {
			if($log->id !=1){
				$row = array();
				$row[] = ++$i;
				$row[] = $log->firstname;
				$row[] = $log->lastname;
				$row[] = $log->sinif;
				if (!empty($log->phone1)){
					$row[] = $log->phone1;
				}else{

					$row[] ="<a target='_blank' href='".$CFG->wwwroot."/user/editadvanced.php?id=".$log->id."' class='btn btn-primary'>Ekle</a>";
				}
				$row[] = $log->adet;
				$row[] = "<input type='checkbox' class='usercheckbox' name='user[]' value='$log->id'/>";
				$table->data[] = $row;
			}
		}
		$rs->close();
	}
	else {
		$row = array();
		$row[] = "<div id='load-users' style='border: 1px solid;margin: 10px 0px;padding:15px 10px 15px 50px;background-repeat: no-repeat;background-position: 10px center;color: #00529B;background-image: url(".'pic/info.png'."); background-color: #BDE5F8;border-color: #3b8eb5;'>Kayıt Bulunamadı!</div>";
		$table->data[] = $row;
	}

	return $table;
}
function block_sms_rozettablo($sql){
	global $DB,$CFG;
	$table = new html_table();
	$table->attributes = array("name" => "userlist");
	$table->attributes = array("id" => "userlist");
	$table->attributes = array("class" => "table table-striped table-bordered table-condensed");
	$table->data  = array();
	$count  =  $DB->record_exists_sql($sql, array ($params=null));
	if($count >= 1) {
		$table->head  = array(get_string('serial_no', 'block_sms'),
				get_string('name', 'block_sms'),
				get_string('lastname', 'block_sms'),
				"Sınıf",
				get_string('cell_no', 'block_sms'),
				"<div class='text-center'><input type='checkbox' class='usercheckboxall' id='selectall' name='' value=''/></div>");

		$table->size  = array('5%','20%','20%','20%','20%','5%');
		$table->align  = array('center', 'center', 'center','center','center','center');
		$rs = $DB->get_recordset_sql($sql, array(), null, null);
		$i=0;
		foreach ($rs as $log) {
			if($log->id !=1){
				$row = array();
				$row[] = ++$i;
				$row[] = $log->firstname;
				$row[] = $log->lastname;
				$row[] = $log->sinif;
				if (!empty($log->phone1)){
					$row[] = $log->phone1;
				}else{

					$row[] ="<a target='_blank' href='".$CFG->wwwroot."/user/editadvanced.php?id=".$log->id."' class='btn btn-primary'>Ekle</a>";
				}
				$row[] = "<input type='checkbox' class='usercheckbox' name='user[]' value='$log->id'/>";
				$table->data[] = $row;
			}
		}
		$rs->close();
	}
	else {
		$row = array();
		$row[] = "<div id='load-users' style='border: 1px solid;margin: 10px 0px;padding:15px 10px 15px 50px;background-repeat: no-repeat;background-position: 10px center;color: #00529B;background-image: url(".'pic/info.png'."); background-color: #BDE5F8;border-color: #3b8eb5;'>Kayıt Bulunamadı!</div>";
		$table->data[] = $row;
	}

	return $table;
}