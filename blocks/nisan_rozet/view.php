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
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>
/**
 * Created by PhpStorm.
 * User: Çaglar
 * Date: 05.09.2016
 * Time: 17:25
 */

global $DB, $OUTPUT, $PAGE, $CFG, $USER,$COURSE;
require_once('../../config.php');
require_once('nisan_rozet_form.php');
require_once("locallib.php");

$context = context_system::instance();
$PAGE->set_context($context);

//Yetki Ayarları
$canmanagenisan = has_capability('block/nisan_rozet:managenisan', $context);
$canmanageduyuru = has_capability('block/nisan_rozet:manageduyuru', $context);
$canviewogretmen = has_capability('block/nisan_rozet:viewogretmen', $context);
$cannisanview = has_capability('block/nisan_rozet:nisanview', $context);
$canogretmennisanatama = has_capability('block/nisan_rozet:ogretmennisanatama', $context);
$canviewanothernisanlist = has_capability('block/nisan_rozet:othernisanviewlist', $context);
// eklenti değişkenleri
$viewpage = required_param('viewpage', PARAM_INT);
$section = optional_param('section','Anasayfa',PARAM_TEXT);
$editid =optional_param('editid',0,PARAM_INT);
$delid = optional_param('delid',0,PARAM_INT);
$ok = optional_param('comfirm',null,PARAM_TEXT);
$PAGE->set_pagelayout('admin');
switch ($section){
    case 'Anasayfa':
        $PAGE->set_title('Anasayfa');
        break;
    case 'nisanekle':
        $PAGE->set_title('Nişan Ekle');
        break;
    case 'nisanatama';
        $PAGE->set_title('Nişan Atama');
        break;
    case 'nisanyonetim';
        $PAGE->set_title('Nişan Listesi');
        break;
    case 'rozetyonetim';
        $PAGE->set_title('Rozet Yönetimi');
        break;
    case 'duyuru';
        $PAGE->set_title('Duyuru Yönetimi');
        break;
    case 'kriter';
        $PAGE->set_title('Kriter Yönetimi');
        break;
    case 'log';
        $PAGE->set_title('Log Listesi');
        break;
    case 'ayarlar';
        $PAGE->set_title('Ayarlar');
        break;
    default:
        $PAGE->set_title(get_string("pluginname", 'block_nisan_rozet'));

}
$PAGE->set_heading('Nisan_Rozet');
$pageurl = new moodle_url('/blocks/nisan_rozet/view.php',array('viewpage' => $viewpage));
require_login();
$PAGE->set_url($pageurl);

//require_capability('block/nisan_rozet:view', context_system::instance());
echo $OUTPUT->header();

if($viewpage == 1){
    if ($CFG->block_nisan_rozet_notificationbar == 1){
        echo '<div id="notificationbar"></div>';
    }
    if((strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') || strpos($_SERVER['HTTP_USER_AGENT'], 'Trident/7'))){
echo(block_nisan_rozet_mesajyaz('warning','İnterner Explorer kullanıyorsunuz ,Eklentinin Düzgün Görüntülenmesi için Chrome ,mozilla vs. gibi modern tarayıcı kullanmanız tavsiye edilir',null,true));
    }
    echo '<div class="row-fluid">';
echo '<div class="span3">';
echo '<div class="sidebar-nav">
    <div class="well">
		<ul class="nav nav-list"> 
		  <li class="nav-header">Yönetici Paneli</li>        
		  <li><a href="?viewpage=1&section=Anasayfa"><i class="icon-home"></i>Anasayfa</a></li>
          <li><a href="?viewpage=1&section=nisanekle"><i class="icon-star"></i> Nişan Yükle </a></li>
          <li><a href="?viewpage=1&section=nisanatama"><i class="icon-star-empty"></i>Nişan Ver</a></li>
           <li><a href="?viewpage=1&section=nisanyonetim"><i class="icon-pencil"></i>Nişan Yönetimi</a></li>
           <li><a href="?viewpage=1&section=rozetyonetim"><i class="icon-pencil"></i>Rozet Yönetimi</a></li>
           <li><a href="?viewpage=1&section=rozetlistesi"><i class="icon-pencil"></i>Rozet Listesi</a></li>
		    <li class="divider"></li>
		  <li><a href="?viewpage=1&section=duyuru"><i class="icon-volume-up"></i>Duyuru Yönetimi</a></li>
          <li><a href="?viewpage=1&section=kriter"><i class="icon-plus-sign"></i>Kriter Yönetimi</a></li>
          <li><a href="?viewpage=1&section=log"><i class="icon-eye-open"></i>Log Görüntüleme</a></li>
          <li><a href="?viewpage=1&section=rapor"><i class="icon-eye-open"></i>Raporlar</a></li>
		  <li><a href="?viewpage=1&section=ayarlar"><i class="icon-cog"></i> Ayarlar</a></li>
		  <li><a href="'.$CFG->wwwroot.'"><i class="icon-share"></i>Çıkış</a></li>
		</ul>
	</div>
</div>';
echo '</div>';
echo '<div class="span9">';
    echo '<div class="dashboard-wrapper">';
    echo '<div class="main-container">';
    if($section == 'Anasayfa' && $canmanagenisan){
        $lastcron = $DB->get_field_sql('SELECT MAX(lastruntime) FROM {task_scheduled} WHERE component = "block_nisan_rozet"');
        if(!empty($lastcron)){
        $cronoverdue = ($lastcron < time() - 610);
        if ($cronoverdue){
            echo(block_nisan_rozet_mesajyaz('danger','Eklenti Botları 10 dk dır Çalışmıyor.Cron ayarlarınız doğru yapılandırılmamış.
            Eklentinin Düzgün çalışması için Cron ayarlarınızı sistem yöneticinize 5 dk bir çalışacak şekilde ayarlatın
            <br><a target="_blank" href="https://docs.moodle.org/31/en/Cron">Detaylı Bilgi</a>'));
        }
        }
        if($CFG->enablebadges == 0){
            redirect(new moodle_url('/admin/settings.php?section=optionalsubsystems'),'Site de Rozet Etkinleşmemiş Lütfen Rozet ayarını etkinleştiriniz ',3,\core\output\notification::NOTIFY_ERROR);

        }
        if(!$DB->record_exists('block_nisan_rozet_nisan',array())){
            echo(block_nisan_rozet_mesajyaz('info','Eklentiye Hiçbir Nişan Yüklenmedi.Lütfen Nişan Yükleyiniz.<a href="'.$CFG->wwwroot.'/blocks/nisan_rozet/view.php?viewpage=1&section=nisanekle">Nişan Yükle</a>',null,true));
        }
        if(!$DB->record_exists('badge',array('type'=>1))){
            echo(block_nisan_rozet_mesajyaz('info','Siteye Hiçbir Site Rozeti Yüklenmedi.Lütfen Site Rozeti  Yükleyiniz.<a href="'.$CFG->wwwroot.'/badges/newbadge.php?type=1">Rozet Yükle</a>',null,true));
        }
        if(!$DB->record_exists('block_nisan_rozet_kriter',array('aktif'=>1))){
            echo(block_nisan_rozet_mesajyaz('info','Eklentiye Hiçbir Aktif Kriter Girişi Yapılmadı.Lütfen Kriter Ekleyiniz.<a href="'.$CFG->wwwroot.'/blocks/nisan_rozet/view.php?viewpage=1&section=kriter">Kriter Ekle</a>',null,true));
        }
        include_once ("adminpages/sonislemlerlive.php");
        include_once ("adminpages/rozetbotlive.php");
       
    }
    else if ($section == 'nisanekle' && $canmanagenisan){
        $filemanageropts = array('subdirs' => false, 'maxfiles' => 1, 'accepted_types' => array('image'),
                'maxbytes' => $COURSE->maxbytes, 'return_types' => FILE_INTERNAL | FILE_EXTERNAL);
        $customdata = array('filemanageropts' => $filemanageropts);
        $mform=new block_nisan_rozet_nisanekle(null, $customdata);
        if($delid != 0){
            $rs=$DB->get_record('block_nisan_rozet_nisan',array('id'=>$delid),'id,name',null);
            if($ok == 'ok') {
                global  $DB;
                try{
                    $log = new mylog(4);
                    $log->tarih = time();
                    $log->expose =$USER->id;
                    $log->nisan_id = $delid;
                    $log->content = getpersonlink($USER->id) .'--> '.getnisanlink($delid).' isimli Nişanı Sistemden Sildi';
                    $log->trigger();
                    $DB->delete_records('block_nisan_rozet_nisan', array('id' => $delid));
                    block_nisan_rozet_nisansil($delid);
                    $DB->delete_records('block_nisan_rozet_atama',array('nisan_id'=>$delid));
                    $DB->delete_records('block_nisan_rozet_kriter',array('nisan_id'=>$delid));
                    $DB->delete_records('block_nisan_rozet_settings',array('yetkisi'=>$delid));

                }catch (Exception $e){
                    echo '<div id="msgsuccess" class="row-fluid"><div class="alert alert-danger text-center "><i class="fa fa-exclamation-triangle fa-2x"></i>&nbsp; &nbsp; '.$e->getMessage().'</div></div>';

                }

            }
            else {
                echo $OUTPUT->confirm(block_nisan_rozet_mesajyaz('danger','Nişan silindiği zaman bu nişana sahip tüm kullanıcılar nişanı kaybedecek 
                aynı zamanda nişana atanmış kriter ve öğretmen nişan yetkiside silinecek','uyarimesaj').'<br>'.getnisanlink($rs->id).' isimli Nişanı Silmek İstediğinize Emin misiniz?', '/blocks/nisan_rozet/view.php?viewpage=1&section=nisanekle&delid='.$delid.'&comfirm=ok', '/blocks/nisan_rozet/view.php?viewpage=1&section=nisanekle');
            }
        }
        if($editid !=0) {
            $itemid = $editid;
            $draftitemid = file_get_submitted_draft_itemid('attachments');
            file_prepare_draft_area($draftitemid, $context->id, 'block_nisan_rozet', 'attachment', $itemid, $filemanageropts);
            // Prepare the data to pass into the form - normally we would load this from a database, but, here, we have no 'real' record to load
            $entry = new stdClass();
            $entry->attachments =$draftitemid;// Add the draftitemid to the form, so that 'file_get_submitted_draft_itemid' can retrieve it
            // ---------
            // Set form data
            // This will load the file manager with your previous files
            $rs=$DB->get_record('block_nisan_rozet_nisan',array('id'=>$editid),'*');
            $entry->name=$rs->name;
            $entry->tanim=$rs->tanim;
            $entry->id=$editid;
            
            $mform->set_data($entry);
            $mform->get_data();
        }

        $mform->get_data();
        $mform->display();



    }
    else if ($section == 'nisanatama' && $canmanagenisan){
        include_once ("pages/nisanatama.php");
    }
    else if ($section == 'ayarlar' && $canmanagenisan){
        include_once('adminpages/ayarlar.php');
    }
    else if ($section == 'duyuru' && $canmanageduyuru){
     $mform= new block_nisan_rozet_duyuru();
        if($delid != 0){
            if($ok == 'ok') {
                global  $DB;
                try{
                    $DB->delete_records('block_nisan_rozet_duyuru', array('id' => $delid));
                    $log = new mylog(12);
                    $log->tarih = time();
                    $log->expose = $USER->id;
                    $log->content = getpersonlink($USER->id).' --> Duyuru Sildi';
                    $log->trigger();
                }catch (Exception $e){
                    echo '<div id="msgsuccess" class="row-fluid"><div class="alert alert-danger text-center "><i class="fa fa-exclamation-triangle fa-2x"></i>&nbsp; &nbsp; '.$e->getMessage().'</div></div>';
                }

            }
            else {
                echo $OUTPUT->confirm('Duyuruyu Silmek İstediğinize Emin misiniz?', '/blocks/nisan_rozet/view.php?viewpage=1&section=duyuru&delid='.$delid.'&comfirm=ok', '/blocks/nisan_rozet/view.php?viewpage=1&section=duyuru');
            }
        }
        if($editid !=0) {
            //$entry = new stdClass();
            $rs=$DB->get_record('block_nisan_rozet_duyuru',array('id'=>$editid),'*');
            $mform= new block_nisan_rozet_duyuru();
            $mform->set_data($rs);


        }
     $mform->get_data();
     $mform->display();

    }
    else if ($section == 'nisanyonetim' && $canmanagenisan) {
        include_once ("pages/nisanyonetim.php");
    }
    else if ($section == 'rozetyonetim' && $canmanagenisan){
        include_once ("adminpages/badge.php");
    }
    else if ($section == 'kriter' && $canmanagenisan){
        include_once ("adminpages/kriter.php");
    }
    else if ($section == 'log' && $canmanagenisan){
        include_once ("adminpages/log.php");
    }
    else if ($section == 'rapor' && $canmanagenisan){
        include_once ("adminpages/rapor.php");
    }
    else if ($section == 'rozetlistesi'){
        include_once ("pages/rozetlistesi.php");
    }
    echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '</div>';



}
else if ($viewpage == 2 && $canviewogretmen){
    if ($CFG->block_nisan_rozet_notificationbar == 1){
        echo '<div id="notificationbar"></div>';
    }
    if((strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') || strpos($_SERVER['HTTP_USER_AGENT'], 'Trident/7'))){
        echo(block_nisan_rozet_mesajyaz('warning','İnterner Explorer kullanıyorsunuz ,Eklentinin Düzgün Görüntülenmesi için Chrome ,mozilla vs. gibi modern tarayıcı kullanmanız tavsiye edilir',null,true));
    }
    echo '<div class="row-fluid">';
    echo '<div class="span3">';
    echo '<div class="sidebar-nav">
    <div class="well">
		<ul class="nav nav-list"> 
		  <li class="nav-header">Öğretmen Paneli</li>        
		  <li><a href="?viewpage=2&section=Anasayfa"><i class="icon-home"></i>Anasayfa '.block_nisan_rozet_aktifduyurusayisi().'</a></li>
          <li><a href="?viewpage=2&section=nisanatama"><i class="icon-star-empty"></i> Nişan Ver </a></li>
          <li><a href="?viewpage=2&section=nisanyonetim"><i class="icon-pencil"></i> Nişan Yönetim</a></li>
		  <li><a href="?viewpage=2&section=rozetlistesi"><i class="icon-pencil"></i>Rozet Listesi</a></li>
		  <li class="divider"></li>
		  <li><a href="'.$CFG->wwwroot.'"><i class="icon-share"></i> Çıkış</a></li>
		</ul>
	</div>
</div>';
    echo '</div>';
    echo '<div class="span9">';
    echo '<div class="dashboard-wrapper">';
    echo '<div class="main-container">';
    if($section == 'Anasayfa'){
    echo(block_nisan_rozet_controlduyuru());
    echo(block_nisan_rozet_ogretmencontrolnisan());

    }
    else if ($section == 'nisanatama' && $canogretmennisanatama ){
    include_once ("pages/nisanatama.php");
    }else if ($section == 'nisanyonetim' && $canogretmennisanatama ){
        include_once ("pages/nisanyonetim.php");
    }
    else if ($section == 'rozetlistesi'){
        include_once ("pages/rozetlistesi.php");
    }
    echo '</div>';//main conteiner bitiş
    echo '</div>'; //dashboard bitiş
    echo '</div>';
    echo '</div>';

}
else if($viewpage == 3 && $cannisanview){
    include_once ("pages/nisanview.php");
}
else if($viewpage == 4 && $cannisanview){
    echo(block_nisan_rozet_kriteryaz());
}
if(!empty($mform)) {
    if ($mform->is_cancelled()) {
        redirect(new moodle_url('/blocks/nisan_rozet/view.php?viewpage=1&section=Anasayfa'));

    } else if ($data = $mform->get_data()) {
        // SUCCESS
        if($viewpage == 1 && $section == 'nisanekle') {
            if(empty($data->id)) {
                try {
                    if (!$DB->record_exists('block_nisan_rozet_nisan', array('name' => $data->name))) {
                        $DB->insert_record('block_nisan_rozet_nisan', $data);
                        $sql = "SELECT id FROM {block_nisan_rozet_nisan} ORDER BY id DESC LIMIT 1";
                        $rs = $DB->get_record_sql($sql, array());
                        // Save the files submitted
                        file_save_draft_area_files($data->attachments, $context->id, 'block_nisan_rozet', 'attachment', $rs->id,
                                $filemanageropts);
                        echo '<div id="msgsuccess" class="row-fluid"><div class="alert alert-success text-center "><i class="fa fa-check fa-2x"></i>&nbsp; &nbsp; Nişan Eklendi</div></div>';
                         $log = new mylog(10);
                         $log->tarih = time();
                         $log->expose = $USER->id;
                         $log->nisan_id = $rs->id;
                         $log->content = getpersonlink($USER->id).' --> Sisteme '.getnisanlink($rs->id).' isimli nişanı Yükledi';
                        $log->trigger();
                    }else{
                        echo '<div id="msgsuccess" class="row-fluid"><div class="alert alert-danger text-center "><i class="fa fa-exclamation-triangle fa-2x"></i>&nbsp; &nbsp; Aynı İsimde Nişan Zaten Var</div></div>';
                    }

                }catch (Exception $e){
                    echo '<div id="msgsuccess" class="row-fluid"><div class="alert alert-danger text-center "><i class="fa fa-exclamation-triangle fa-2x"></i>&nbsp; &nbsp; '.$e->getMessage().'</div></div>';
                }
                $table=$mform->display_nisan();
                echo html_writer::table($table);
            }else{

                $DB->update_record('block_nisan_rozet_nisan',$data);
                file_save_draft_area_files($data->attachments, $context->id, 'block_nisan_rozet', 'attachment', $data->id ,$filemanageropts);
                echo '<div id="msgsuccess" class="row-fluid"><div class="alert alert-success text-center "><i class="fa fa-check fa-2x"></i>&nbsp; &nbsp; Nişan Düzenlendi</div></div>';
                $log = new mylog(11);
                $log->tarih = time();
                $log->expose = $USER->id;
                $log->nisan_id = $data->id;
                $log->content = getpersonlink($USER->id). ' --> '.getnisanlink($data->id).' isimli sistem nişanını düzenledi';
                $log->trigger();
                $table=$mform->display_nisan();
                echo html_writer::table($table);
            }
        }
        if($viewpage == 1 && $section == 'duyuru') {
            if(empty($data->id)) {
                try {
                    $data->yazan =fullname($USER);
                    $data->tarih = time();
                        $DB->insert_record('block_nisan_rozet_duyuru', $data);
                        echo (block_nisan_rozet_mesajyaz('success','Duyuru Başarılı bir Şekilde Eklendi','uyarimesaj',false));
                    $log = new mylog(8);
                    $log->tarih = time();
                    $log->expose = $USER->id;
                    $log->content = getpersonlink($USER->id). ' --> Yeni Duyuru Ekledi';
                    $log->trigger();


                }catch (Exception $e){
                    echo(block_nisan_rozet_mesajyaz('danger',$e->getMessage(),'uyarimesaj',false));
                }
                $table=$mform->display_duyuru();
                echo html_writer::table($table);
            }else{
                $DB->update_record('block_nisan_rozet_duyuru',$data);
                echo(block_nisan_rozet_mesajyaz('success','Duyuru Güncellendi','uyarimesaj',false));
                $log = new mylog(9);
                $log->tarih = time();
                $log->expose = $USER->id;
                $log->content =getpersonlink($USER->id).' --> Duyuru Düzenledi';
                $log->trigger();
                $table=$mform->display_duyuru();
                echo html_writer::table($table);
            }
        }

    } else {
        // FAIL / DEFAULT
        if($viewpage == 1 && $section == 'nisanekle') {
            echo '<br><div class="text-box-heading text-center">NİŞAN LİSTESİ</div><br/>';
            $table = $mform->display_nisan();
            echo html_writer::table($table);
        }
        if($viewpage == 1 && $section == 'duyuru') {
            echo '<br><div class="text-box-heading text-center">DUYURULAR</div><br/>';
            $table = $mform->display_duyuru();
            echo html_writer::table($table);
        }
    }
}

$params = array($viewpage,$section);
echo '<script src="Js/datatables.min.js"></script>';
echo '<script src="Js/DT_bootstrap.js"></script>';
echo '<script src="Js/ion.rangeSlider.js"></script>';
$PAGE->requires->js_init_call('M.block_nisan_rozet.init', $params);
echo $OUTPUT->footer();
