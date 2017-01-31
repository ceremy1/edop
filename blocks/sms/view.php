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

/* SMS_NetGsm Block
 * SMS_NetGsm block eklentisi tek yollu istenen kullanıcı rollerine sms göndermeye yarar
 * @package blocks
 * @author: Çağlar Mersinli
 * @date: 03.07.2016
*/

require_once('../../config.php');
require_once('sms_form.php');
require_once("lib.php");
// Global değişkenler
global $DB, $OUTPUT, $PAGE, $CFG, $USER,$COURSE;

// eklenti değişkenleri
$viewpage = required_param('viewpage', PARAM_INT);
$rem = optional_param('rem', null, PARAM_RAW);
$edit = optional_param('edit', null, PARAM_RAW);
$delete = optional_param('delete', null,PARAM_TEXT);
$id = optional_param('id', null, PARAM_INT);
$tarih=optional_param('tarih',null,PARAM_INT);
$mesaj=optional_param('mesaj',null,PARAM_RAW);
$allid=optional_param('allid',null,PARAM_TEXT);
// sayfa ayarları
if($viewpage==6 || $viewpage==5 ){
    $PAGE->set_pagelayout('admin');
}else if ($viewpage==4){
    $PAGE->set_pagelayout('report');
}else{
    $PAGE->set_pagelayout('standard');
}

$PAGE->set_title(get_string("pluginname", 'block_sms'));
$PAGE->set_heading('SMS_NetGsm');
$pageurl = new moodle_url('/blocks/sms/view.php',array('viewpage' => $viewpage));
//$PAGE->set_url(new moodle_url('/path/to/your/file.php', array('key' => 'value', 'id' => 3)));
require_login();
$PAGE->set_url($pageurl);
require_capability('block/sms:viewpages', context_system::instance());
echo $OUTPUT->header();




/* if($viewpage == 2 ) {
    $form = new sms_send();
    $form->display();
    $table=$form->display_report();
    $a= html_writer::table($table);
    echo "<form action='' method='post' name='tests' id='smssendform'><div id='table-change'>".$a."</div>
    <input type='submit' onclick='return onay();' style='float:right;'name='submit' id='smssend' value='Sms Gönder'/>
    <input type='hidden' name='viewpage' id='viewpage' value='$viewpage'/>
         </form>";
    if(isset($_REQUEST['submit'])) {
        $msg=$_REQUEST['msg']; // SMS Meassage alındı
        $user = $_REQUEST['user']; // User ID alındı
		$msgheader =$_REQUEST['msgheader']; // sms hader  alındı
        if(empty($user)) {
            echo('<div class="alert alert-warning"><i class="fa fa-exclamation-triangle  fa-2x pull-left"></i>Kullanıcı Seçmediniz!</div>');
        }
        else {
            $N = count($user);
        }
       	 
        global $DB, $CFG;
        $table = new html_table();
        $table->head  = array(get_string('serial_no', 'block_sms'), get_string('name', 'block_sms'), get_string('lastname', 'block_sms'), get_string('usernumber', 'block_sms'),get_string('status', 'block_sms'));
		$table->attributes['class']="table table-striped table-bordered table-condensed";
		
        // $table->size  = array('10%', '20%', '30%', '20%','10%');
        // $table->align  = array('center', 'left', 'center', 'center');
        // $table->width = '100%';
        
// NetGsm API çağırdık
         if($CFG->block_sms_api == 0) {
            $number = $name = array();
            for($a=0; $a< $N;$a++) {
                $id = $user[$a];
                $sql = 'SELECT usr.firstname, usr.id, usr.lastname, usr.email,usr.phone1 FROM {user} usr WHERE usr.id =?';
                $rs2 = $DB->get_record_sql($sql, array($id));
                $no = $rs2->phone1;
                if (!empty($no)) {
                    $number[] = $no;
                    $name[] = $rs2->firstname;
					$lastname[] = $rs2->lastname;
                }

            }
            $res = send_sms_netgsm($number, $msg,$msgheader);
			$res = explode(" ",$res);
			
          if ($res[0]=="00"){
			 $bulkid= $res[1];
			
		     $rapor = block_sms_rapor($bulkid,1);
		  
           if(!empty($rapor)){

            foreach($rapor as $key_item => $rapor_item){
				
				foreach( explode(" ",$rapor_item) as $k=> $item){
					$sutun[$k]=$item;
				}
                $row = array();
                $row[] = $key_item + 1;
                $row[] = $name[$key_item];
				$row[] = $lastname[$key_item];
                $row[] = $sutun[0];
				$durum=block_sms_durum($sutun[1]);
                $row[] = $durum;
                $table->data[] = $row;
				
            }
        echo '<div class="row-fluid"><div class="alert alert-success text-center span12"><i class="fa fa-check-square-o  fa-2x "></i><h3>SMS Siteme Aktarıldı</h3></div></div>';
		echo '<br/>';
		echo '<div class="alert alert-info text-center"><h4>RAPOR GÖSTERİMİ</h4></div>';
		
        echo html_writer::table($table);
		
		if($CFG->block_sms_api_bakiye==1){
			$bakiye = block_sms_bakiye();
		echo '<div class=row-fluid"><div class="span10"></div><div class="span2 pull-right"><h5>Bakiye:&nbsp;<span class="badge badge-info">'.$bakiye.'</span></h5></div></div>';
		echo '<div class=row-fluid"><div class="span10"></div><div class="span2 "><a href="'.$CFG->wwwroot.'/blocks/sms/view.php?viewpage=4" class="btn btn-success">Rapor Arşivi&nbsp;<i class="fa fa-chevron-circle-right pull-right"></i></a></div></div>';
		
		}
		   }else{
			   
			   echo '<div class="alert alert-error alert-block">
    <h4>Rapor Alınamadı!</h4>
	Bu durumun birçok sebebi olabilir.Olası Nedenler;
    <br>
    <ul>
    <li>Netgsm serverlerında hata olabilir</li>
    <li>Ayarlar kısmından bekleme süresi çok düşük olabilir ,Bu hatayla karşılaşıyorsanız bekleme süresini arttırmanız tavsiye edilir</li>
    <li>Yazılımcıyla iletişme geçebilirsiniz. <a class="btn btn-success" href="http://mersinlihoca.com/mycustompages/iletisim.php">İLETİŞİM</a> 
    </li>
    </ul>
    </div>';
		   }
		 }
		 if($res[0]!="00"){
			 if($res[0]=="20"){
				 $error="Mesaj metninde ki problemden(Boş Olması vs..) dolayı gönderilemediğini veya standart maksimum mesaj karakter sayısını geçtiğini ifade eder. 
               (Standart maksimum karakter sayısı 917 dir. Eğer mesajınız türkçe karakter içeriyorsa Türkçe Karakter normal karektere göre daha fazla yer kaplar)";
			 }
			  if($res[0]=="30"){
				 $error="Geçersiz kullanıcı adı , şifre veya kullanıcınızın API erişim izninin olmadığını gösterir.
Ayrıca eğer API erişiminizde IP sınırlaması yaptıysanız ve sınırladığınız ip dışında gönderim sağlıyorsanız 30 hata kodunu alırsınız. API erişim izninizi veya IP sınırlamanızı , web arayüzümüzden; sağ üst köşede bulunan ayarlar> API işlemleri menüsunden kontrol edebilirsiniz.";
			 }
			 if($res[0]=="40"){
				 $error="Mesaj başlığınızın (gönderici adınızın) sistemde tanımlı olmadığını ifade eder. Gönderici adlarınızı API ile sorgulayarak kontrol edebilirsiniz.";
			 }
			 if($res[0]=="70"){
				 $error="Hatalı sorgulama. Gönderdiğiniz parametrelerden birisi hatalı veya zorunlu alanlardan birinin eksik olduğunu ifade eder.";
			 }
			 if($res[0]=="33"){
				 $error="SMS servisi şuan Deaktif durumda.Sadece yönetici, ayarlardan servisi aktif hale getirebilir ";
			 }
			  
			echo '<div class="alert alert-block alert-error">
           <h4>HATA!</h4>'.$error.'</div>';
			}
		 
		
		 }
		
    }

}*/

if($viewpage == 3) {
    $form = new template_form();
    if($rem) {
        if($delete) {
            global  $DB;
            $DB->delete_records('block_sms_template', array('id' => $delete));
            //redirect($CFG->wwroot.'/blocks/sms/view.php?viewpage=3');
        }
        else {
              echo $OUTPUT->confirm(get_string('askfordelete', 'block_sms'), '/blocks/sms/view.php?viewpage=3&rem=rem&delete='.$id, '/blocks/sms/view.php?viewpage=3');
        }
   }
    // mesaj şablonu düzenleyek
    if($edit) {
        $get_template = $DB->get_record('block_sms_template', array('id'=>$id), '*');
        $form = new template_form();
        $form->set_data($get_template);
        
        
    }
    $toform['viewpage'] = $viewpage;
    $form->set_data($toform);
	$form->get_data();
    $form->display();
    $table=$form->display_report();
    echo html_writer::table($table);
	echo '<div><a href="'.$CFG->wwwroot.'/blocks/sms/view.php?viewpage=5" class="btn btn-success"><i class="fa fa-chevron-circle-left pull-left" ></i>&nbsp;SMS KAYDET</a></div>';
   
   
}
else if($viewpage == 4) {

        $form = new template_rapor();
        $form->display();
    if (is_siteadmin()) {
        $raportable = mb_convert_encoding(get_bulkrapor(), "utf-8", "iso-8859-9");

        echo '<div class="spotlight spotlight-v1 arrow-bottom" style="text-align: center;"><h4>RAPOR ARŞİVİ</h4></div>';
        if ($raportable == 100 || $raportable == 101 || $raportable == 70 || $raportable == 65 || $raportable == 60) {
            echo '<div class="alert alert-warning"><i class="fa fa-exclamation-triangle fa-2x pull-left"></i>Rapor Alınamadı!</div>';
        } else {
            echo html_writer::div($raportable, '', array('id' => 'rapor'));
        }
        echo '<div class="panel panel-info"> 
  <div class="panel-heading">
    <h4 class="panel-title">GÖREVID SORGULAMA</h4>
  </div>
  <div class="panel-body">
  <form action="" method="post" name="tests" id="bulkrapor">
	  <div class="input-prepend">
      <span class="add-on">GÖREVID</span>
     <input class="span6"  type="text" maxlength="8" name="gorevid" required>
	 <input type="hidden" name="viewpage" id="viewpage" value="' . $viewpage . '"/>
	 </div>
	   <div >
       <button type="submit" class="btn btn-primary">Sorgula</button>
        </div>
	  </form>
	  </div>
      </div>
	  ';
        if ($_POST) {
            $gid = $_POST['gorevid']; //görevid alındı
            if (!empty($gid)) {
                $gorevrapor = block_sms_rapor($gid, 2);

                if (!empty($gorevrapor)) {
                    global $DB, $CFG;
                    $table = new html_table();
                    $table->head = array(get_string('serial_no', 'block_sms'), 'Numara', 'Durum', 'Operatör', 'Tarih', 'Hata');
                    // $table->attributes['class']="table table-striped table-bordered table-condensed";
                    $table->attributes = array("id" => "table_rapor");
                    $table->attributes = array("class" => "table table-striped table-bordered table-condensed");
                    // $table->size  = array('10%', '20%', '30%', '20%','10%');
                    // $table->align  = array('center', 'left', 'center', 'center');
                    // $table->width = '100%';
                    foreach ($gorevrapor as $key_item => $rapor_item) {

                        foreach (explode(" ", $rapor_item) as $k => $item) {
                            $sutun[$k] = $item;
                        }
                        $row = array();
                        $row[] = $key_item + 1;
                        $row[] = $sutun[0];
                        $row[] = block_sms_durum($sutun[1]);
                        $row[] = block_sms_operator($sutun[2]);
                        $row[] = $sutun[4];
                        $row[] = block_sms_hata($sutun[6]);
                        $table->data[] = $row;

                    }
                    echo '<div class="spotlight spotlight-v1 arrow-bottom" style="text-align: center;"><h5>DETAYLI RAPOR</h5></div>';
                    echo html_writer::table($table);
                    echo '<div><a href="' . $CFG->wwwroot .
                            '/blocks/sms/view.php?viewpage=5" class="btn btn-success"><i class="fa fa-chevron-circle-left pull-left" ></i>&nbsp;SMS Anasayfa</a></div>';

                } else {
                    echo '<div class="alert alert-warning"><i class="fa fa-exclamation-triangle fa-2x pull-left"></i>Rapor Alınamadı!</div>';
                }

            } else {
                echo '<div class="alert alert-warning"><i class="fa fa-exclamation-triangle fa-2x pull-left"></i>
  Görevid kısmını doldurmalısınız!
</div>';
            }

        }

    }else{

        echo('<div id="msgsuccess" class="alert alert-danger text-center"><i class="fa fa-exclamation-triangle  fa-2x "></i>&nbsp;&nbsp;BU SAYFAYI GÖRÜNTÜLEME YETKİNİZ YOK!</div>');



    }
}
else if ($viewpage==5){
    global $CFG,$USER;
        $form = new sms_kaydet();
        $form->display();


    
 echo "<form action='' method='post' name='tests' id='smssendform'>
<input type='submit' onclick='return onay();' style='float:right;'
         name='submit' id='smssend' value='Sms Kaydet'/>
<div id='table-change'></div>
         <input type='submit' onclick='return onay();' style='float:right;'
         name='submit' id='smssend' value='Sms Kaydet'/>
         <input type='hidden' name='viewpage' id='viewpage' value='$viewpage'/>
         </form>";
    if(isset($_REQUEST['submit'])) {
        $msg = $_REQUEST['msg']; // SMS Meassage alındı
        if(isset($_REQUEST['user'])){
            $user = $_REQUEST['user']; // User ID alındı
        }else{
            $user="";
        }

       // $msgheader =str_replace("'","",$_REQUEST['msgheader']); // sms hader  alındı
        $date =$_REQUEST['dateal']; //tarih aldık
        $ders =$_REQUEST['dersal']; //dersi aldık
        //$sinif =$_REQUEST['sinifal']; //sınıf aldık
        if(empty($user)) {
            echo('<div id="msgsuccess" class="alert alert-danger text-center"><i class="fa fa-exclamation-triangle  fa-2x "></i>&nbsp;&nbsp;Kullanıcı Seçmediniz!</div>');
        }
        else if(empty($msg)){
            echo('<div id="msgsuccess" class="alert alert-danger text-center"><i class="fa fa-exclamation-triangle  fa-2x "></i>&nbsp;&nbsp;Mesaj Yazmalısınız!</div>');

        }
        elseif(empty($ders)){
            echo('<div id="msgsuccess" class="alert alert-danger text-center"><i class="fa fa-exclamation-triangle  fa-2x "></i>&nbsp;&nbsp;Ders Seçimi Yapmalısınız</div>');
        }
        else {
            $N = count($user);
        }
        if (!empty($N)) {
            global $DB;
            $records=array();
            for ($a = 0; $a < $N; $a++) {

                $id = $user[$a];
                $sql = 'SELECT usr.firstname, usr.id, usr.lastname,usr.phone1,coh.name AS sinif
                       FROM {user} usr 
                       LEFT JOIN {cohort_members} co ON co.userid = usr.id
                       LEFT JOIN {cohort} coh ON coh.id = co.cohortid 
                       WHERE usr.id =?';
                $rs2 = $DB->get_record_sql($sql, array($id));
                $no = $rs2->phone1;
                if (!empty($no)) {
                    $record[$a] = new stdClass();
                    $record[$a]->tarih = $date;
                    $record[$a]->ad = $rs2->firstname;
                    $record[$a]->soyad = $rs2->lastname;
                    $record[$a]->ders = $ders;
                    $record[$a]->mesaj = $msg;
                    $record[$a]->tel = $no;
                    if(empty($rs2->sinif)){
                        $record[$a]->sinif = " ";
                    }else {
                        $record[$a]->sinif = $rs2->sinif;
                    }
                    $record[$a]->ogrenci_id=$rs2->id;
                    $record[$a]->gonderen_ad=$USER->firstname;
                    $record[$a]->gonderen_soyad=$USER->lastname;
                    $record[$a]->gonderen_id=$USER->id;
                    $records[]=$record[$a];
                }

            }
            try {
                $DB->insert_records('block_sms_kaydet', $records);
                echo '<div id="msgsuccess" class="row-fluid"><div class="alert alert-success text-center "><i class="fa fa-check fa-2x"></i>Mesajınız Başarılı Bir Şekilde Kaydedildi</div></div>';
            } catch (Exception $e) {
               
                echo '<div id="msgsuccess" class="row-fluid"><div class="alert alert-danger text-center "><i class="fa fa-check fa-2x"></i>'.$e->getMessage().'</div></div>';
            }
           
        }

    }
}
else if ($viewpage==6){
    
        global $CFG;
        echo '<ul id="myTab" class="nav nav-tabs" role="tablist">
<li class="active">
<a data-toggle="tab" role="tab" href="#Mgonderme">Mesaj Listeleme & Gönderme</a>
</li>
<li class="">
<a data-toggle="tab" role="tab" href="#Myonetim">Mesaj Yönetimi</a>
</li>
<li class="">
<a data-toggle="tab" role="tab" href="#Mnetgsm">NetGsm Gönderilme Durumu</a>
</li>
</ul>';
        echo '<div id="myTabContent" class="tab-content">
<div id="Mgonderme" class="tab-pane fade active in">';
        $form = new admin_panel();
        $form->display();
       
            echo "<form action='' method='post' name='tests' id='smssendform'>
    <div id='admin_listener'></div>
    <a href='#modalmesaj' id= 'showbutton'class='btn btn-info' data-toggle='modal'>LİSTELE</a>
    <input type='submit' onclick='return onay1();' style='float:right;'name='submit' id='showbutton' value='NetGsm ile Gönder'/>
    <input type='hidden' name='viewpage' id='viewpage' value='$viewpage'/>
         </form>";
       
        if (isset($_REQUEST['submit'])) {
            if (!empty($_REQUEST['mesajlist'])) {
                $msg = $_REQUEST['mesajlist']; // SMS Meassage alındı
            } else {
                $msg = null;
            }

            if (!empty($_REQUEST['tel'])) {
                $tel = $_REQUEST['tel']; // User ID alındı
            } else {
                $tel = null;
            }
            $date = $_REQUEST['gondermetarihi']; //tarih aldık
            $msgheader = $_REQUEST['msgheader']; // sms hader  alındı
            if (empty($tel)) {
                echo('<div id="erormessage" class="alert alert-danger"><i class="fa fa-exclamation-triangle  fa-2x pull-left"></i>Seçim Yapmadınız!</div>');

            }
            if (empty($msg)) {
                echo('<div id="erormessage" class="alert alert-danger"><i class="fa fa-exclamation-triangle  fa-2x pull-left"></i>Mesaj Bulunamadı!</div>');

            } else {
                $N = count($tel);
            }
            if ($CFG->block_sms_api == 0 && !empty($tel) && !empty($msg)) {

                $res = send_sms_netgsm($tel, $msg, $msgheader);
                // print_r($res);
                $res = explode(" ", $res);
                if ($res[0] == "00") {
                    $bulkid = $res[1];
                     gonderilenmesajpasif($tel,$date);
                    $rapor = block_sms_rapor($bulkid, 2);

                    if (!empty($rapor)) {

                        global $DB, $CFG;
                        $table = new html_table();
                        $table->head = array(get_string('serial_no', 'block_sms'), 'Numara', 'Durum', 'Operatör', 'Tarih', 'Hata');
                        // $table->attributes['class']="table table-striped table-bordered table-condensed";
                        $table->attributes = array("id" => "table_rapor");
                        $table->attributes = array("class" => "table table-striped table-bordered table-condensed");
                        // $table->size  = array('10%', '20%', '30%', '20%','10%');
                        // $table->align  = array('center', 'left', 'center', 'center');
                        // $table->width = '100%';
                        foreach ($rapor as $key_item => $rapor_item) {

                            foreach (explode(" ", $rapor_item) as $k => $item) {
                                $sutun[$k] = $item;
                            }
                            $row = array();
                            $row[] = $key_item + 1;
                            $row[] = $sutun[0];
                            $row[] = block_sms_durum($sutun[1]);
                            $row[] = block_sms_operator($sutun[2]);
                            $row[] = $sutun[4];
                            $row[] = block_sms_hata($sutun[6]);
                            $table->data[] = $row;

                        }
                        echo '<div class="spotlight spotlight-v1 arrow-bottom" style="text-align: center;"><h5>DETAYLI RAPOR</h5></div>';
                        echo html_writer::table($table);
                        echo '<div><a href="' . $CFG->wwwroot .
                                '/blocks/sms/view.php?viewpage=5" class="btn btn-success"><i class="fa fa-chevron-circle-left pull-left" ></i>&nbsp;Sms Kaydet</a></div>';

                        if ($CFG->block_sms_api_bakiye == 1) {
                            $bakiye = block_sms_bakiye();
                            echo '<div class=row-fluid"><div class="span10"></div><div class="span2 pull-right"><h5>Bakiye:&nbsp;<span class="badge badge-info">' .
                                    $bakiye . '</span></h5></div></div>';
                            echo '<div class=row-fluid"><div class="span10"></div><div class="span2 "><a href="' . $CFG->wwwroot .
                                    '/blocks/sms/view.php?viewpage=4" class="btn btn-success">Rapor Arşivi&nbsp;<i class="fa fa-chevron-circle-right pull-right"></i></a></div></div>';

                        }
                    } else {

                        echo '<div class="alert alert-error alert-block">
    <h4>Rapor Alınamadı!</h4>
	Bu durumun birçok sebebi olabilir.Olası Nedenler;
    <br>
    <ul>
    <li>Netgsm serverlerında hata olabilir</li>
    <li>Ayarlar kısmından bekleme süresi çok düşük olabilir ,Bu hatayla karşılaşıyorsanız bekleme süresini arttırmanız tavsiye edilir</li>
    <li>Yazılımcıyla iletişme geçebilirsiniz. <a class="btn btn-success" href="http://mersinlihoca.com/mycustompages/iletisim.php">İLETİŞİM</a> 
    </li>
    </ul>
    </div>';
                    }
                }
                if ($res[0] != "00") {
                    if ($res[0] == "20") {
                        $error = "Mesaj metninde ki problemden(Boş Olması vs..) dolayı gönderilemediğini veya standart maksimum mesaj karakter sayısını geçtiğini ifade eder. 
               (Standart maksimum karakter sayısı 917 dir. Eğer mesajınız türkçe karakter içeriyorsa Türkçe Karakter normal karektere göre daha fazla yer kaplar)";
                    }
                    if ($res[0] == "30") {
                        $error = "Geçersiz kullanıcı adı , şifre veya kullanıcınızın API erişim izninin olmadığını gösterir.
Ayrıca eğer API erişiminizde IP sınırlaması yaptıysanız ve sınırladığınız ip dışında gönderim sağlıyorsanız 30 hata kodunu alırsınız. API erişim izninizi veya IP sınırlamanızı , web arayüzümüzden; sağ üst köşede bulunan ayarlar> API işlemleri menüsunden kontrol edebilirsiniz.";
                    }
                    if ($res[0] == "40") {
                        $error =
                                "Mesaj başlığınızın (gönderici adınızın) sistemde tanımlı olmadığını ifade eder. Gönderici adlarınızı API ile sorgulayarak kontrol edebilirsiniz.";
                    }
                    if ($res[0] == "70") {
                        $error =
                                "Hatalı sorgulama. Gönderdiğiniz parametrelerden birisi hatalı veya zorunlu alanlardan birinin eksik olduğunu ifade eder.";
                    }
                    if ($res[0] == "33") {
                        $error = "SMS servisi şuan Deaktif durumda.Sadece yönetici, ayarlardan servisi aktif hale getirebilir ";
                    }

                    echo '<div class="alert alert-block alert-error">
           <h4>HATA!</h4>' . $error . '</div>';
                }

            }

        }
        echo '</div>
<div id="Myonetim" class="tab-pane fade  ">';
        $form = new template_yonetim();
        $form->display();
        // print_r(mesajupdate($allid,$tarih,$mesaj));

        echo '</div>
<div id="Mnetgsm" class="tab-pane fade  ">';
$form=new template_yonetim_netgsm();
    $form->display();

echo'</div>
</div>';
}
else if($viewpage == 7){
    $form = new template_yonetim_edit();

    if($edit) {
        $form = new template_yonetim_edit(null, array('sms_body' => $mesaj, 'gettarih' => $tarih));
        $toform['viewpage'] = $viewpage;
        $toform['id'] = $allid;
        $form->set_data($toform);
        $form->get_data();

    }

    if($rem) {
        if($delete) {
            global  $DB;
            $rs = explode(',',$delete);
            foreach ($rs as $value){
                $DB->delete_records('block_sms_kaydet', array('id' => $value));

            }
            redirect(new moodle_url('/blocks/sms/view.php?viewpage=6'));
        }
        else {
            echo $OUTPUT->confirm('Mesajı Silmek İstediğinize Emin misiniz?', '/blocks/sms/view.php?viewpage=7&rem=rem&delete='.$allid, '/blocks/sms/view.php?viewpage=6');
        }
    }
    if(!$rem){
        $form->display();
    }

}
else if($viewpage == 8){
    if(has_capability('block/sms:managesetting', context_system::instance())) {
        echo '<p class="headline headline-v3"><i class="fa fa-cogs" aria-hidden="true"></i> EKLENTİ AYARLARI</p>';
        //panel başlangıç
        echo'<div class="panel panel-primary">
      <div class="panel-heading">
        <h3 class="panel-title">Öğretmene Ders Atama 
        <small>* Eğer Genel Ayarlardan Ders İsimleri Değiştirilirse Burası Tekrar Ayarlanmalı</small></h3>
      </div>
      <div class="panel-body">
      
     <div class="row-fluid">
     <div class="span4">';
        //öğretmen seçim select
        echo '<select id="ogretmen_sec">
      '.ogretmensec().'
</select>';
        echo '</div>
     <div class="span8">
     <form action=""  method="post" name="yetkiform" id="yetkiformid">
     <div id="dersdoldur"></div>
</div>
    </div>
     <div class="row-fluid">
     <input type="hidden" name="viewpage" id="viewpage" value="'.$viewpage.'"/>
     <input id="btn_yetkikaydet" type="submit" name="submit" class="btn btn-success pull-right" value="Kaydet"/>
     </form>
     </div>
     ';

        if(isset($_REQUEST['submit'])) {
            if(isset($_REQUEST['ogretmen_id'])){
                $ogretmen_id = $_REQUEST['ogretmen_id'];
                $DB->delete_records('block_sms_settings',array('ogretmen_id'=>$ogretmen_id,'yetki_tur'=>1));
            }else{
                $ogretmen_id="";
            }
            if(isset($_REQUEST['dersler'])){
                $dersler = $_REQUEST['dersler']; // dersler alındı
            }else{
                $dersler="";
            }
             if(empty( $ogretmen_id)){
                echo('<div id="msgsuccess" class="alert alert-danger text-center"><i class="fa fa-exclamation-triangle  fa-2x "></i>&nbsp;&nbsp;Öğretmen Seçmelisiniz!</div>');
            }else if(empty($dersler)) {
                 echo('<div id="msgsuccess" class="alert alert-warning text-center"><i class="fa fa-exclamation-triangle  fa-2x ">
                  </i>&nbsp;&nbsp;Ders İşaretlemesi Yapmadınız Eğer zaten Ders atamalarını silmek istiyorsanız Bu uyarıyı Dikkate Almayın (Tüm Dersler Silindi)</div>');
             }

            else{
                $N=count($dersler);
            }
            if(!empty($N)){
                global $DB;
                $records=array();
                for($a=0;$a < $N;$a++){
                    $ders=$dersler[$a];
                     $sql="SELECT u.id,u.firstname,u.lastname FROM {user} u WHERE u.id=?";
                    $rs=$DB->get_record_sql($sql,Array($ogretmen_id));
                    $record[$a] = new stdClass();
                    $record[$a]->ogretmen_id=$ogretmen_id;
                    $record[$a]->ogretmen_ad=$rs->firstname;
                    $record[$a]->ogretmen_soyad=$rs->lastname;
                    $record[$a]->yetkisi=$ders;
                    $record[$a]->yetki_tur=1;
                    $records[]=$record[$a];
                }
                try {

                    $DB->insert_records('block_sms_settings', $records);
                    echo '<div id="msgsuccess" class="row-fluid"><div class="alert alert-success text-center "><i class="fa fa-check fa-2x"></i> Başarılı Bir Şekilde Atama Yapıldı</div></div>';
                }
                catch (Exception $e){
                    echo '<div id="msgsuccess" class="row-fluid"><div class="alert alert-danger text-center "><i class="fa fa-check fa-2x"></i>'.$e->getMessage().'</div></div>';
                }

            }

           

        }
        //body kapanacak
        echo '</div></div>';

        //panel bitiş
        echo'<div class="panel panel-info">
      <div class="panel-heading">
        <h3 class="panel-title">Öğretmene Mesaj Başlığı Atama
        <small>* Eğer Netgsm Ayarlardan Mesaj Başlığı Değiştirilirse Burası Tekrar Ayarlanmalı</small></h3>
      </div>
      <div class="panel-body">
      
     <div class="row-fluid">
     <div class="span4">';
        //öğretmen seçim select
        echo '<select id="ogretmenheader_sec">
      '.ogretmensec().'
</select>';
        echo '</div>
     <div class="span8">
     <form action=""  method="post" name="yetkiformmesajbasligi" id="yetkiformmesajbasligi">
     <div id="headerdoldur"></div>
</div>
    </div>
     <div class="row-fluid">
     <input type="hidden" name="viewpage" id="viewpage" value="'.$viewpage.'"/>
     <input id="btn_headerkaydet" type="submit" name="headersubmit" class="btn btn-success pull-right" value="Kaydet"/>
     </form>
     </div>
     ';
        //request burda
        if(isset($_REQUEST['headersubmit'])) {
            if(isset($_REQUEST['ogretmen_id'])){
                $ogretmen_id = $_REQUEST['ogretmen_id'];
                $DB->delete_records('block_sms_settings',array('ogretmen_id'=>$ogretmen_id,'yetki_tur'=>2));
            }else{
                $ogretmen_id="";
            }
            if(isset($_REQUEST['headers'])){
                $headers = $_REQUEST['headers']; // dersler alındı
            }else{
                $headers="";
            }
            if(empty( $ogretmen_id)){
                echo('<div id="msgsuccess" class="alert alert-danger text-center"><i class="fa fa-exclamation-triangle  fa-2x "></i>&nbsp;&nbsp;Öğretmen Seçmelisiniz!</div>');
            }else if(empty( $headers)) {
                echo('<div id="msgsuccess" class="alert alert-warning text-center"><i class="fa fa-exclamation-triangle  fa-2x ">
                  </i>&nbsp;&nbsp;Mesaj Başlığı İşaretlemesi Yapmadınız Eğer zaten Mesaj Başlığı atamalarını silmek istiyorsanız Bu uyarıyı Dikkate Almayın (Tüm Atamalar Silindi)</div>');
            }

            else{
                $N=count($headers);
            }
            if(!empty($N)){
                global $DB;
                $records=array();
                for($a=0;$a < $N;$a++){
                    $header=$headers[$a];
                    $sql="SELECT u.id,u.firstname,u.lastname FROM {user} u WHERE u.id=?";
                    $rs=$DB->get_record_sql($sql,Array($ogretmen_id));
                    $record[$a] = new stdClass();
                    $record[$a]->ogretmen_id=$ogretmen_id;
                    $record[$a]->ogretmen_ad=$rs->firstname;
                    $record[$a]->ogretmen_soyad=$rs->lastname;
                    $record[$a]->yetkisi=$header;
                    $record[$a]->yetki_tur=2;
                    $records[]=$record[$a];
                }
                try {

                    $DB->insert_records('block_sms_settings', $records);
                    echo '<div id="msgsuccess" class="row-fluid"><div class="alert alert-success text-center "><i class="fa fa-check fa-2x"></i> Başarılı Bir Şekilde Atama Yapıldı</div></div>';
                }
                catch (Exception $e){
                    echo '<div id="msgsuccess" class="row-fluid"><div class="alert alert-danger text-center "><i class="fa fa-check fa-2x"></i>'.$e->getMessage().'</div></div>';
                }

            }

        }
        echo '</div></div>';
    }
}
if(!empty($form)) {
    if (!$form->is_cancelled()) {

        if ($fromform = $form->get_data()) {

            if ($viewpage == 3) {
                global $DB;
                $chk = ($fromform->id) ? $DB->update_record('block_sms_template', $fromform) :
                        $DB->insert_record('block_sms_template', $fromform);
                redirect(new moodle_url('/blocks/sms/view.php?viewpage=3'));
            } else {
                if ($viewpage == 7) {
                    $allid = $fromform->id;
                    $tarih = $fromform->gettarih;
                    $mesaj = $fromform->sms_body;
                    mesajupdate($allid, $tarih, $mesaj);
                    redirect(new moodle_url('/blocks/sms/view.php?viewpage=6'));
                }
            }
        }
    } else {
        redirect(new moodle_url('/blocks/sms/view.php?viewpage=6'));
    }
}
$params = array($viewpage);
//$PAGE->requires->js('/blocks/sms/Js/mytab.js');
$PAGE->requires->js_init_call('M.block_sms.init', $params);

echo '<script src="Js/ion.rangeSlider.js"></script>';
echo $OUTPUT->footer();
