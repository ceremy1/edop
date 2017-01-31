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
global $CFG,$PAGE;
require_once("{$CFG->libdir}/formslib.php");
require_once("lib.php");


$release = $CFG->release;
$release = explode(" (", $release);
if ($release[0] >= 2.2) {
    $PAGE->set_context(context_system::instance());
} else {
    $PAGE->set_context(get_system_context());
}
// kullanıcı listesi
class sms_main extends  moodleform{

    protected function definition() {
        return false;
    }
}
class sms_form extends moodleform {
    public function definition() {
        return false;
    }
    public function display_report() {
        global $DB, $OUTPUT, $CFG, $USER;
        $table = new html_table();
        $table->head  = array(get_string('serial_no', 'block_sms'), get_string('name', 'block_sms'), get_string('cell_no', 'block_sms'), get_string('select', 'block_sms'));
        $table->size  = array('10%', '20%', '20%', '20%');
        $table->align  = array('center', 'left', 'center', 'center');
        $table->width = '100%';
        $table->data  = array();
        $sql="SELECT usr.firstname, usr.lastname, usr.email,usr.phone1,c.fullname
        FROM {course} c 
		INNER JOIN {context} cx ON c.id = cx.instanceid
        AND cx.contextlevel = '50' and c.id=8
        INNER JOIN {role_assignments} ra ON cx.id = ra.contextid
        INNER JOIN {role} r ON ra.roleid = r.id
        INNER JOIN {user} usr ON ra.userid = usr.id
        WHERE r.name = 'Student'";
        $rs = $DB->get_recordset_sql($sql, array(),  null, null);
        $i=0;
        foreach ($rs as $log) {
            $row = array();
            $row[] = ++$i;
            $row[] = $log->firstname;
            $row[] = $log->phone1;
            $row[] = "<input type='checkbox' class='usercheckbox' name='user[]' value='$log->phone1'/>";
            $table->data[] = $row;
        }
		$rs->close();
        return $table;
    }
}

// sms form şablonu 
class sms_send extends moodleform {
    public function definition() {
        global $DB, $CFG;
        $mform =& $this->_form;
        $mform->addElement('header', 'sms_send', get_string('sms_send', 'block_sms'));
        if(isset($c_id)) {
            $attributes =  $DB->get_records_sql_menu('SELECT id , fullname FROM {course} where id = ?', array ($c_id), $limitfrom=0, $limitnum=0);
        }
        else {
            $attributes =  $DB->get_records_sql_menu('SELECT id , fullname FROM {course}', array ($params=null), $limitfrom=0, $limitnum=0);
        }
        $mform->addElement('select', 'c_id', get_string('selectcourse', 'block_sms'), $attributes);
        $mform->setType('c_id', PARAM_INT);
		//competency kullanılacağında yapılacak 
	//if(isset($c_id)) {
	  //  $attributes =  $DB->get_records_sql_menu('SELECT id,level_name FROM {competency_level} where id = ?',array ($l_id), $limitfrom=0, $limitnum=0);
      //  }
       // else {
	   // $attributes1=array('Düzenleyen', 'Öğrenci');
       // }
        $attributes2 =  $DB->get_records_sql_menu('SELECT id , shortname FROM {role}', null, $limitfrom=0, $limitnum=0);
        //$attributes=array_intersect($attributes2, $attributes1);
        $mform->addElement('select', 'r_id', get_string('selectrole', 'block_sms'), $attributes2);
		//netgsm mesajheader çekiliyor 
	 $usercode = $CFG->block_sms_api_username;
	 $password = $CFG->block_sms_api_password;
	 $url ="https://api.netgsm.com.tr/get_msgheader.asp?usercode=".urlencode($usercode)."&password=".urldecode($password);
	 $ch = curl_init(); 
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
	$output=curl_exec($ch);
    curl_close($ch);
	$msgheaders = explode("<br>", $output,-1);
	foreach ($msgheaders as $item) {
    $msgheader[$item] = $item;
	}
		$mform->addElement('html', ' <p class="text text-warning">Gönderen NetGsm sisteminden otomatik alınıyor...</p>');
		$mform->addElement('select', 'header_id', 'Gönderen Seçiniz:',$msgheader );
        $attributes =  $DB->get_records_sql_menu('SELECT id,tname FROM {block_sms_template}', null, $limitfrom=0, $limitnum=0);
        $mform->addElement('selectwithlink', 'm_id', get_string('selectmsg', 'block_sms'), $attributes, null,
                           array('link' => $CFG->wwwroot.'/blocks/sms/view.php?viewpage=3', 'label' => get_string('template', 'block_sms')));
		
		
        $attributes = array('rows' => '7', 'cols' => '40', 'maxlength' => '400');
        $mform->setType('r_id', PARAM_INT);
        $mform->addElement('textarea', 'sms_body', get_string('sms_body', 'block_sms'), $attributes);
        $mform->addRule('sms_body','Lütfen Mesaj Yazınız' , 'required', 'client');
        $mform->addRule('sms_body', $errors = null, 'required', null, 'server');
        $mform->setType('sms_body', PARAM_TEXT);
		$mform->addElement('html', '<div class="span8 pull-right"><h5>Karakter Sayısı:&nbsp;<span id="say">0</span></h5></div>
         <br><br><br>');
		$mform->addElement('html', '<img src="Loading.gif" id="load" style="margin-left:6cm;" />');
        $mform->addElement('hidden', 'viewpage', '2');
        $mform->setType('viewpage', PARAM_INT);
        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);
		$mform->addElement('html', '<div class="row-fluid"><div class="span6"><div class="alert alert-warning"><i class="fa fa-exclamation-triangle fa-2x pull-left"></i>
  Mesaj yazma ve Gönderen seçimi işlemini, Kullanıcıları Listeleme İşleminden önce mutlaka yapmalısınız!
</div></div></div>');
        $mform->addElement('button', 'nextbtn', 'Kullanıcıları Göster', array("id" => "btnajax"));
    }
    public function display_report($c_id = null, $r_id = null) {
        global $DB, $OUTPUT, $CFG, $USER;
        $table = new html_table();
        $table->attributes = array("name" => "userlist");
        $table->attributes = array("id" => "userlist");
		$table->attributes = array("class" => "table table-striped table-bordered table-condensed");
        $table->width = '100%';
        $table->data  = array();
        if(empty($c_id)) {
            $c_id=1;
            $r_id=3;
        }
        $sql="SELECT usr.firstname, usr.id, usr.lastname, usr.email,usr.phone1,c.fullname
            FROM {course} c
            INNER JOIN {context} cx ON c.id = cx.instanceid
            AND cx.contextlevel = '50' and c.id=$c_id
            INNER JOIN {role_assignments} ra ON cx.id = ra.contextid
            INNER JOIN {role} r ON ra.roleid = r.id
            INNER JOIN {user} usr ON ra.userid = usr.id
            WHERE r.id = $r_id";
        $count  =  $DB->record_exists_sql($sql, array ($params=null));
        if($count >= 1) {
            $table->head  = array(get_string('serial_no', 'block_sms'), get_string('name', 'block_sms'), get_string('lastname', 'block_sms'),get_string('cell_no', 'block_sms'), "<label class='checkbox'><input type='checkbox' class='usercheckboxall' id='selectall' name='' value=''/></label>");
			
            $table->size  = array('10%','30%','30%','25%','5%');
            $table->align  = array('center', 'left', 'center', 'center');
            $rs = $DB->get_recordset_sql($sql, array(), null, null);
            $i=0;
            foreach ($rs as $log) {
				if($log->id !=1){
                $fullname = $log->firstname;
                $row = array();
                $row[] = ++$i;
                $row[] = $log->firstname;
				$row[] = $log->lastname;
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
}
// sms şablonu 
class template_form extends moodleform {
    public function definition() {
        $mform =& $this->_form;
        $mform->addElement('header', 'sms_template_header', get_string('sms_template_header', 'block_sms'));
        $mform->addElement('text', 'tname', 'İsim:', array('size' => 44, 'maxlength' => 160));
        $mform->addRule('tname', 'Lütfen Şablon İsmi Giriniz', 'required', 'client');
        $mform->setType('tname', PARAM_TEXT);
        $mform->addElement('textarea', 'template', 'Mesaj:', array('rows' => '6', 'cols' => '47', 'maxlength' => '160', 'id' => 'asd123'));
        $mform->addRule('template', 'Lütfen Şablon Mesajı Giriniz', 'required', 'client');
        $mform->setType('template', PARAM_TEXT);
         $mform->addElement('hidden', 'viewpage', '2');
        $mform->setType('viewpage', PARAM_INT);
        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);
        $this->add_action_buttons();
    }
	
	
  public function validation($data,$files) {
        global $DB;
        $errors = array();
        if ($data['tname'] == "") {
            $errors['tname'] = "Lütfen Şablon İsmi Giriniz";
			
           }
		   if ($data['template'] == "") {
            $errors['template'] = "Lütfen Şablon mesajı Giriniz";
			
           }
		   
            if ($DB->record_exists('block_sms_template', array('tname' => $data['tname']))) {
                $errors['tname'] = 'Şablon ismi zaten var';
				
            }
            return $errors;
        
    }

    public function display_report() {
        global $DB, $OUTPUT, $CFG, $USER;
        $table = new html_table();
        $table->head  = array(get_string('serial_no', 'block_sms'), get_string('name', 'block_sms'), get_string('msg_body', 'block_sms'), get_string('edit', 'block_sms'), get_string('delete', 'block_sms'));
		$table->attributes['class']="table table-striped table-bordered table-condensed";
        // $table->size  = array('10%', '20%', '50%', '10%', '10%');
        // $table->align  = array('center', 'left', 'left', 'center', 'center');
        // $table->width = '100%';
        $table->data  = array();
        $sql="SELECT * FROM {block_sms_template}";
        $rs = $DB->get_recordset_sql($sql, array(),  null, null);
        
        $i=0;
        foreach ($rs as $log) {
            $row = array();
            $row[] = ++$i;
            $row[] = $log->tname;
            $row[] = $log->template;
            $row[] = '<a  title="Edit" href="'.$CFG->wwwroot.'/blocks/sms/view.php?viewpage=3&edit=edit&id='.$log->id.'"/><img src="'.$OUTPUT->pix_url('t/edit') . '" class="iconsmall" /></a> ';
            $row[] = '<a  title="Remove" href="'.$CFG->wwwroot.'/blocks/sms/view.php?viewpage=3&rem=remove&id='.$log->id.'"/><img src="'.$OUTPUT->pix_url('t/delete') . '" class="iconsmall"/></a>';
            $table->data[] = $row;
        }
		$rs->close();
        return $table;
    }
}
class template_yonetim extends  moodleform{

    protected function definition() {
        global $DB, $CFG;
        $mform =& $this->_form;
        $mform->addElement('header', 'sms_Duzenle',"Sms Düzenle");
        if(has_capability('block/sms:managemessage', context_system::instance())) {
            $mform->addElement('html',
                    '<a href="' . $CFG->wwwroot . '/blocks/sms/view.php?viewpage=5" class="btn btn-success" >
                    <i class="fa fa-chevron-circle-left pull-left" aria-hidden="true"></i>Sms Kaydet</a>');

            $mform->addElement('date_selector', 'gettarih', 'Tarih Seç:');
            $select = $mform->createElement('select', 'gonderen','Gönderen:');
            $select->addOption('TÜM GÖNDERİCİLER','-1',null);
            $attributes = block_sms_gonderen();
            foreach ($attributes as $label => $value) {
                $select->addOption( $value, $label );
            }
            $mform->addElement($select);
            //$mform->addelement('select', 'gonderen', 'Gönderen:', $attributes);
            $mform->addElement('html',
                    '<input name="yonetimlistele" id="btn_yonetimlistele" type="button" class="btn btn-default" value="Listele"/>');
            $mform->addElement('html', '<img src="Loading.gif" id="load" style="margin-left:6cm;" />');
            $mform->addElement('html', '<div id="listedoldur"></div>');

        }
    }
    /*public function report_yonetim(){
        global $DB, $OUTPUT, $CFG;
        $table = new html_table();
        $table->head  = array(get_string('serial_no', 'block_sms'), get_string('name', 'block_sms'), get_string('msg_body', 'block_sms'), get_string('edit', 'block_sms'), get_string('delete', 'block_sms'));
    }*/
    
}
class template_yonetim_edit extends moodleform{

    protected function definition() {
        global $DB, $CFG;
        $mform =& $this->_form;
        $mform->addElement('header', 'sms_Duzenle',"Mesaj Düzenleniyor..");
        if(is_siteadmin()) {
            $mform->addElement('html',
                    '<a href="' . $CFG->wwwroot . '/blocks/sms/view.php?viewpage=6" class="btn btn-success" >
                    <i class="fa fa-chevron-circle-left pull-left" aria-hidden="true"></i>Mesaj Yönetimi</a>');
        }
        $mform->addElement('date_selector','gettarih','Tarih Seç:');
        $mform->setDefault('gettarih',$this->_customdata['gettarih']);
        $attributes = array('rows' => '7', 'cols' => '40', 'maxlength' => '400');
        $mform->setType('r_id', PARAM_INT);
        $mform->addElement('textarea', 'sms_body', get_string('sms_body', 'block_sms'), $attributes);
        $mform->addRule('sms_body','Lütfen Mesaj Yazınız' , 'required', 'client');
        $mform->addRule('sms_body', $errors = null, 'required', null, 'server');
        $mform->setType('sms_body', PARAM_TEXT);
        $mform->setDefault('sms_body',$this->_customdata['sms_body']);
        $mform->addElement('hidden', 'viewpage', '6');
        $mform->setType('viewpage', PARAM_INT);
        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_TEXT);
        $this->add_action_buttons();

    }
}
class template_yonetim_netgsm extends moodleform{

    protected function definition() {
        global $DB, $CFG;
        $mform =& $this->_form;
        $mform->addElement('header', 'sms_Duzenle',"Sms Düzenle");
        if(has_capability('block/sms:managemessage', context_system::instance())) {
            $mform->addElement('html',
                    '<a href="' . $CFG->wwwroot . '/blocks/sms/view.php?viewpage=5" class="btn btn-success" >
                    <i class="fa fa-chevron-circle-left pull-left" aria-hidden="true"></i>Sms Kaydet</a>');

            $mform->addElement('date_selector', 'gettarihi', 'Bu Tarih İle:');
            $mform->addElement('date_selector', 'gettarihs', 'Bu Tarih Arasında:');
            $mform->addElement('html',
                    '<input name="yonetimlistele" id="btn_yonetimlistelenetgsm" type="button" class="btn btn-default" value="Listele"/>');
            $mform->addElement('html', '<img src="Loading.gif" id="load" style="margin-left:6cm;" />');
            $mform->addElement('html', '<div id="netgsmlistedoldur"></div>');
        }
    }
}
class template_rapor extends moodleform {
    public function definition() {
	        //şimdilik bootstrap 2 kullandım 
		  // $mform =& $this->_form;
         // $mform->addElement('header', 'template_rapor_header', 'Rapor Arşivi');
		 // $mform->addElement('text', 'bulkidname', 'GorevID:', array('size' => 10, 'maxlength' => 8));
		 // $mform->addRule('bulkidname', 'Lütfen GorevID yazınız', 'required', 'client');
        // $mform->setType('bulkidname', PARAM_TEXT);
		// $this->add_action_buttons();
	}
	
	}

/**
 * Class sms_kaydet
 */
class sms_kaydet extends moodleform{
    /**
     *kaydetme kısmı için şablon
     */
    public function definition() {
        global $DB, $CFG;
        $mform =& $this->_form;
        $mform->addElement('header', 'sms_kaydet',"Sms Kaydet");
       /**$usercode = $CFG->block_sms_api_username;
        $password = $CFG->block_sms_api_password;
        $url ="https://api.netgsm.com.tr/get_msgheader.asp?usercode=".urlencode($usercode)."&password=".urldecode($password);
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        $output=curl_exec($ch);
        curl_close($ch);
        $msgheaders = explode("<br>", $output,-1);
        foreach ($msgheaders as $item) {
            $msgheader[$item] = $item;
        }
        $mform->addElement('html', ' <p class="text text-warning">Gönderen NetGsm sisteminden otomatik alınıyor...</p>');
        $mform->addElement('select', 'header_id', 'Gönderen Seçiniz:',$msgheader );*/

            $mform->addElement('html',
                    '<a href="' . $CFG->wwwroot . '/blocks/sms/view.php?viewpage=6" class="btn btn-success pull-right">
                    Yönetim Paneli<i class="fa fa-chevron-circle-right pull-right" aria-hidden="true"></i></a>');

        $mform->addElement('date_selector','gettarih','Tarih Seç:');
        $attributes=block_sms_explodelesson();
        $mform->addelement('select','ders_id','Ders Seç:' ,$attributes);
        $attributes =  $DB->get_records_sql_menu('SELECT id,tname FROM {block_sms_template}', null, $limitfrom=0, $limitnum=0);
        $mform->addElement('selectwithlink', 'm_id', get_string('selectmsg', 'block_sms'), $attributes, null,
            array('link' => $CFG->wwwroot.'/blocks/sms/view.php?viewpage=3', 'label' => get_string('template', 'block_sms')));

         $attributes = array('rows' => '7', 'cols' => '40', 'maxlength' => '400');

        $mform->addElement('textarea', 'sms_body', get_string('sms_body', 'block_sms'), $attributes);
        $mform->addRule('sms_body','Lütfen Mesaj Yazınız' , 'required', 'client');
        $mform->addRule('sms_body', $errors = null, 'required', null, 'server');
        $mform->setType('sms_body', PARAM_TEXT);
        $mform->addElement('html', '<div class="span8 pull-right"><h5>Karakter Sayısı:&nbsp;<span id="say">0</span></h5></div>
         <br><br><br>');
        $mform->addElement('html','
<ul class="nav nav-tabs" role="tablist">
  <li class="active"><a href="#sinif" role="tab" data-toggle="tab">Sınıf & Kursa Göre Arama</a></li>
  <li><a href="#bolum" role="tab" data-toggle="tab">Bölümlere Göre Arama</a></li>
  <li><a href="#not" role="tab" data-toggle="tab">Nota Göre Arama</a></li>
  <li><a href="#ortalama" role="tab" data-toggle="tab">Not Ortalamasına Göre Arama</a></li>
  <li><a href="#rozet" role="tab" data-toggle="tab">Nişan & Rozet</a></li>
</ul>
<div class="tab-content">
	<div class="tab-pane active" id="sinif">	
 <div class="panel panel-success ">
  <div class="panel-heading">Sınıf(Tüm Öğrenciler) & Sınav(Girmeyen) a Göre Arama</div>
  <div class="panel-body">');
        if(isset($c_id)) {
            $attributes =  $DB->get_records_sql_menu('SELECT id , name FROM {cohort} where id = ?', array ($c_id), $limitfrom=0, $limitnum=0);
        }
        else {
            $attributes =  $DB->get_records_sql_menu('SELECT id , name FROM {cohort}', array ($params=null), $limitfrom=0, $limitnum=0);
        }
        $select = $mform->createElement('select', 'c_id','Sınıf Seç:');
        $select->addOption( 'Seçiniz', '-1',null );
        $select->addOption( 'TÜM SINIFLAR', '-2',null );
        foreach ($attributes as $label => $value) {
            $select->addOption( $value, $label );
        }
        $mform->addElement($select);
        if(isset($q_id)) {
            $attributes =  $DB->get_records_sql_menu('SELECT id , name FROM {quiz} where id = ?', array ($q_id), $limitfrom=0, $limitnum=0);
        }
        else {
            $attributes =  $DB->get_records_sql_menu('SELECT id , name FROM {quiz}', array ($params=null), $limitfrom=0, $limitnum=0);
        }

        $select = $mform->createElement('select', 'q_id','Sınavı Bitirmemiş Öğrenci Seç:');
        $select->addOption( 'Seçiniz', '-1',null );
        foreach ($attributes as $label => $value) {
            $select->addOption( $value, $label );
        }
        $mform->addElement($select);
        $mform->addElement('html','</div></div></div>
		<div class="tab-pane " id="bolum">
		<div class="panel panel-info ">
  <div class="panel-heading">Kurs Bölümlerine Göre Sınava Girmeyen Öğrenci Arama</div>
  <div class="panel-body">
    <select  id="kurssec">'
  .selectcourse().
   '</select>
   <br/>
   <select  id="bolumsec">
   <option value="-1">Bölüm Seçiniz:</option>
   </select>
   <br>
   <select  id="sinavsec">
   <option value="-1">Sınav Seçiniz:</option>
   </select>
  </div>
</div>
</div>
	<div class="tab-pane " id="not">
		<div class="panel panel-warning ">
  <div class="panel-heading">Aldığı Nota Göre Arama</div>
  <div class="panel-body">
    <select  id="kurssec_not">'
                .selectcourse().
                '</select>
   <br/>
   <select  id="bolumsec_not">
   <option value="-1">Bölüm Seçiniz:</option>
   </select>
   <br>
   <select  id="sinavsec_not">
   <option value="-1">Sınav Seçiniz:</option>
   </select>
   <input type="text" id="range_03" name="range_03" value="" />
   </br>
   <div class="text-center"><button type="button" class="btn btn-default btn-large " id="listele_not" >Listele</button></div>
  </div>
</div>
</div>
	<div class="tab-pane " id="ortalama">
		<div class="panel panel-warning ">
  <div class="panel-heading">Bölümdeki Sınavların Not Ortalmasına Göre Arama</div>
  <div class="panel-body">
    <select  id="kurssec_ortalama">'
                .selectcourse().
                '</select>
   <br/>
   <select  id="bolumsec_ortalama">
   <option value="-1">Bölüm Seçiniz:</option>
   </select>
   <br>
   
   <input type="text" id="range_04" name="range_04" value="" />
   </br>
   <div class="text-center"><button type="button" class="btn btn-default btn-large " id="listele_ortalama" >Listele</button></div>
  </div>
</div>
</div>
<div class="tab-pane " id="rozet">
		<div class="panel panel-info ">
		<div class="panel-heading">Nişan & Rozet Kazanımlarına göre Arama</div>
  <div class="panel-body">
  <select  id="nisansec">
   '.selectnisan().'
   </select>
   <strong>VEYA</strong>
   <select  id="rozetsec">
   '.selectrozet().'
   </select>
  </div>
  </div>
  </div>
		
</div>
		');
        $mform->addElement('html', '<img src="Loading.gif" id="load" style="margin-left:6cm;" />');
        $mform->addElement('hidden', 'viewpage', '5');
        $mform->setType('viewpage', PARAM_INT);
        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);
       // $mform->addElement('button', 'nextbtn', 'Kullanıcıları Göster', array("id" => "btnajax"));
        //$this->add_action_buttons();

    }

    /**
     * @param null $id quiz idisi
     * @param null $filtre
     * @param null $from
     * @param null $to
     * @return html_table
     * @throws coding_exception
     */
    public function display_kaydet($id=null,$filtre=null,$from=null,$to=null){
        global $DB, $CFG;
         $table="";

         if($filtre == 'sinif') {
             if($id == -2){
                 $sql="SELECT usr.firstname, usr.id, usr.lastname,usr.phone1,c.name AS sinif
        FROM {cohort} c
        INNER JOIN {cohort_members} cm ON c.id = cm.cohortid
        INNER JOIN  {user} usr ON cm.userid = usr.id";
             }else {
                 $sql = "SELECT usr.firstname, usr.id, usr.lastname,usr.phone1,c.name AS sinif
        FROM {cohort} c
        INNER JOIN {cohort_members} cm ON c.id = cm.cohortid
        INNER JOIN  {user} usr ON cm.userid = usr.id
        WHERE c.id = $id";
             }
             $table=block_sms_tableolusturma($sql);
         }
         else if($filtre == 'quiz')
         {
          $sql=quizwithoutattempsql($id);
             $table=block_sms_tableolusturma($sql);
         }else if($filtre == 'quiz_not'){
             $sql=quiz_not($id,$from,$to);
             $table=block_sms_tableolusturma($sql);
         }else if($filtre == 'ortalama'){
             $sql=block_sms_ortalama($id);
             $table=block_sms_tableolusturmaortalama($sql,$from,$to);
         }else if($filtre == 'nisan'){
             $sql = block_sms_nisan($id);
             $table = block_sms_nisantablo($sql);
         }else if($filtre == 'rozet'){
             $sql = block_sms_rozet($id);
             $table = block_sms_rozettablo($sql);
         }




return $table;

    } //end function




}
class admin_panel extends moodleform{
    public function definition() {

        global  $CFG;
        $mform =& $this->_form;
       // $mform->addElement('header', 'admin_panel', "Yönetici Paneli");

        $mform->addElement('html',
                '<a href="' . $CFG->wwwroot . '/blocks/sms/view.php?viewpage=5" class="btn btn-success" ><i class="fa fa-chevron-circle-left pull-left" aria-hidden="true"></i>Sms Kaydet</a>
                
                ');

        if(has_capability('block/sms:viewyonetim', context_system::instance())) {
            $msgheader = getmsgheaderdb();
            $mform->addElement('html', ' <p class="text text-warning">Gönderen NetGsm sisteminden otomatik alınıyor...</p>');
            $mform->addElement('select', 'header_id', 'Gönderen Seçiniz:', $msgheader);
            $mform->setDefault('header_id', 'Bilisim_KLJ');
            $mform->addElement('date_selector', 'admin_tarih', 'Tarih Seç:');
            $attributes = block_sms_explodeilksablon();
            $mform->addelement('select', 'ilksablon', 'Başlangıç Metni:', $attributes);
            $attributes = block_sms_explodesonsablon();
            $mform->addelement('select', 'sonsablon', 'Bitiş Metni:', $attributes);
            $mform->addElement('button', 'mesajlistele', 'Listele');
            // $mform->addElement('html', '<a id="id_mesajlistele" class="btn" >Listele</a>');

            //$mform->addElement('html', '<div id="admin_listener"></div>');

            $mform->addElement('html', '<img src="Loading.gif" id="load" style="margin-left:6cm;" />');
            $mform->addElement('hidden', 'viewpage', '6');
            $mform->setType('viewpage', PARAM_INT);
            $mform->addElement('hidden', 'id');
            $mform->setType('id', PARAM_INT);

        }


    }
    
    public function display_admin($date,$ilk,$son){
        global $DB;

        $table = new html_table();
        $table->attributes = array("name" => "userlist");
        $table->attributes = array("id" => "userlist");
        $table->attributes = array("class" => "table table-striped table-bordered table-condensed");
        $table->data  = array();
        $sql="SELECT id,tel,GROUP_CONCAT( ders, '(',sinif,'):' , mesaj) as mesaj from {block_sms_kaydet} WHERE tarih=$date AND gonderildimi=0
        GROUP BY tel";
        $count  =  $DB->record_exists_sql($sql, array ($params=null));
        if($count >= 1) {
            $table->head  = array(
                    "<label class='checkbox'><input type='checkbox' class='usercheckboxall' id='selectall' name='' value=''/></label>",
                    get_string('serial_no', 'block_sms'),
                    get_string('cell_no', 'block_sms'),
                     "Mesaj"
                   );
            $table->size  = array('2%','3%','5%','90%');
            $table->align  = array('left', 'left', 'left', 'left');
            $rs = $DB->get_recordset_sql($sql, array(), null, null);
            $i=0;

            foreach ($rs as $log) {

                $row = array();
                    $row[] = "<input type='checkbox' class='usercheckbox' name='tel[]' value='$log->tel'/>";
                    $row[] = ++$i;
                    $row[] = $log->tel;
                    $row[] ="<label>".$ilk.$log->mesaj.".".$son.".</label>
                    <input type='hidden' name='mesajlist[]' value =\"'$ilk$log->mesaj.$son.'\" />";
                    //$row[] = $ilk.":..".$log->mesaj.".".$son.".";

                    $table->data[] = $row;

            }
            //  $last[]='<td colspan="4" ><a href="#modalmesaj" class="btn btn-info" data-toggle="modal">LİSTELE</a><a href="#" class="btn btn-success pull-right">NetGsm ile Gönder</a></td>';
            //$table->data[] =  $last;


            $rs->close();

        }
        else {
            $row = array();
            $row[] = "<div id='load-users' style='border: 1px solid;margin: 10px 0px;padding:15px 10px 15px 50px;background-repeat: no-repeat;background-position: 10px center;color: #00529B;background-image: url(".'pic/info.png'."); background-color: #BDE5F8;border-color: #3b8eb5;'>Kayıt Bulunamadı!</div>";
            $table->data[] = $row;
        }

        return $table;

    }
    
    
}