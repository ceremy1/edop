<?php
/**
 * Created by PhpStorm.
 * User: Çaglar
 * Date: 08.09.2016
 * Time: 21:21
 */
defined('MOODLE_INTERNAL') || die();
define('LOGTABLE', 'block_nisan_rozet_log');
global $CFG;
require_once ("$CFG->dirroot/blocks/nisan_rozet/lib.php");
class mylog {
    public $id;
    public $tarih;
    public $expose;
    public $exposed;
    public $nisan_id;
    public $rozet_id;
    public $content;

    /**
     * @var int
     * -1 ,-2,-3,-4,-5= rozetbot
     * 1 = Nişan Verme
     * 2 = Nişan Düzenleme
     * 3 = Nişan silinme
     * 4 = Sistemden Nişan Silindi
     * 5 = kriter eklendi
     * 6 = kriter silindi
     * 7 = kriter güncelledindi
     * 8 = duyuru eklendi
     * 9 = duyuru düzenlendi
     * 10 =sisteme yeni nişan yüklendi
     * 11 = sistem nişanı düzenlendi
     * 12 = duyuru silindi
     * 13= Nişan tebrik Mesajları Dağıtıldı
     * 14 = Rozet Geri alma
     * 15 = rozet kazanma tebrik mesajı
     */
    public $tur;

    /**
     * mylog constructor.
     *
     * @param int $turid
     */
    public function __construct($turid) {
        $this->tur = $turid;
        
    }

    /**
     * @param int $expose for yapan
     * @param array $exposed for maruz kalan id
     * @param int $nisan_id nişan id
     * @throws dml_missing_record_exception
     * @throws dml_multiple_records_exception
     */
    public function nisanverildi($expose,$exposed,$nisan_id) {
        if ($this->tur == 1) {
            global $DB;
            foreach ($exposed as $item) {
                $sql = "SELECT v.id AS veren,n.id AS nisanid
                FROM {user} v
                LEFT JOIN {user} a ON a.id = $item 
                LEFT JOIN {block_nisan_rozet_nisan} n ON n.id = $nisan_id 
                WHERE v.id = $expose ";
                $rs = $DB->get_record_sql($sql, array());
                $this->tarih = time();
                $this->expose = $expose;
                $this->exposed = $item;
                $this->nisan_id = $nisan_id;
                $this->rozet_id = null;
                if ($rs) {
                    $this->content = getpersonlink($rs->veren) . '--> ' . getpersonlink($item) . '\'a ' . getnisanlink($rs->nisanid) . ' isimli nişan verdi';
                } else {
                    $this->content = null;
                }
                try {
                    $DB->insert_record(LOGTABLE, $this);
                } catch (Exception $e) {
                    $this->tur = 0;
                    $DB->insert_record(LOGTABLE, $this);
                }
            }

        }
    }
    public function trigger(){
        global $DB;
        try{
            $DB->insert_record(LOGTABLE,$this);
        }catch (Exception $e){
            debugging('log kayıdı sırasında hata oluştu');
        }
       
    }
    /**
     * @param int $editid düzenleme id 
     * @param int $ilknisan düzenlemeden önceki nisan id
     * @param int $sonnisan yeni nisan id
     * @param object $user yapan id 
     */
    public function nisanduzenlendi($editid,$ilknisan,$sonnisan,$user){
        global $DB;
       $sql ="SELECT u.id 
              FROM {block_nisan_rozet_atama} a 
              LEFT JOIN {user} u ON u.id = a.ogrid
              WHERE a.id = $editid";
        $rs = $DB->get_record_sql($sql,array());

        $this->tarih =time();
        $this->expose = $user->id;
        $this->exposed = $rs->id;
        $this->nisan_id = $ilknisan;
        $this->rozet_id = null;
        $this->content = getpersonlink($user->id). '--> '.getpersonlink($rs->id).' \'ın Nişanını Değiştirdi:  '.getnisanlink($ilknisan).' ---> '.getnisanlink($sonnisan);
        $this->trigger();
        
    }

    /**
     * @param int $delid
     * @param object $user
     */
    public function nisansilindi($delid,$user){
        global $DB;
        $sql="SELECT n.id AS nisanid,a.id,u.id AS ogrenci
                      FROM {block_nisan_rozet_atama} a 
                      LEFT JOIN {user} u ON u.id = a.ogrid
                      LEFT JOIN {block_nisan_rozet_nisan} n ON n.id = a.nisan_id
                      WHERE a.id =$delid";
        $rs = $DB->get_record_sql($sql,array());
        $this->tarih= time();
        $this->expose = $user->id;
        $this->exposed = $rs->ogrenci;
        $this->nisan_id = $rs->nisanid;
        $this->content = getpersonlink($user->id).'--> '.getpersonlink($rs->ogrenci) .' \'ın '.getnisanlink($rs->nisanid) . ' isimli Nişanını Sildi';
        $this->trigger();
    }

}
/**
 * @param int $id
 * @param string $class
 * @return string resim linki <img>
 * @throws dml_exception
 */
function block_nisan_rozet_nisanresimal($id,$class=null){
    global $CFG;
    $context =context_system::instance();
    $fs = get_file_storage();

    $files = $fs->get_area_files($context->id, 'block_nisan_rozet', 'attachment',$id,'',false);
    foreach ($files as $file) {
        $out = $file->get_filename();
        $fileurl = moodle_url::make_pluginfile_url($file->get_contextid(), $file->get_component(), $file->get_filearea(),
                $file->get_itemid(), $file->get_filepath(), $file->get_filename());


    }
   $px = $CFG->block_nisan_rozet_setimage;
    return '<img class="'.$class.'" style="height: '.$px.'; width: '.$px.';" src="'.$fileurl.'" alt="'.$out.'"/>';

}
/**
 * @param int $id kişi id si
 * @return string
 */
function getpersonlink($id){
    global $DB,$CFG;
    $rs = $DB->get_record('user',array('id'=>$id),'id,firstname,lastname');
    return '<a href="'.$CFG->wwwroot.'/user/profile.php?id='.$id.'">'.$rs->firstname.' '.$rs->lastname.'</a>';
}
/**
 * @param int $id
 * @return string
 */
function getnisanlink($id){
    global $DB,$CFG;
    $nisanad = $DB->get_field('block_nisan_rozet_nisan','name',array('id'=>$id));
     return '<a href="'.$CFG->wwwroot.'/blocks/nisan_rozet/view.php?viewpage=3&nisanid='.$id.'">'.$nisanad.'</a>';

}
function getrozetlink($id){
    global $DB,$CFG;
    $name = $DB->get_field('badge','name',array('id'=>$id));
    return '<a href ="'.$CFG->wwwroot.'/badges/overview.php?id='.$id.'">'.$name.'</a>';
}
/**
 * @param int $id :nisan idisi
 * @throws dml_exception
 */
function block_nisan_rozet_nisansil($id){
    $context =context_system::instance();
    $fs = get_file_storage();
    $files = $fs->get_area_files($context->id, 'block_nisan_rozet', 'attachment',$id,'',false);
    foreach ($files as $file) {
        $filename = $file->get_filename();
    }
    $file = $fs->get_file($context->id,'block_nisan_rozet', 'attachment',$id,'/',$filename);
    if ($file) {
        $file->delete();
    }

}
function block_nisan_rozet_controlnisan(){
    global $DB;
    if($DB->record_exists('block_nisan_rozet_settings',array('yetki_tur'=>1))){
        $sql="SELECT ogretmen_ad,ogretmen_soyad, GROUP_CONCAT(n.name) AS yetkiler FROM {block_nisan_rozet_settings} s
LEFT JOIN {block_nisan_rozet_nisan} n ON n.id=s.yetkisi
WHERE yetki_tur=1 GROUP BY ogretmen_id";
        $rs=$DB->get_recordset_sql($sql,array(),null,null);
       $html = '<div class="panel-group" id="accordion">
  <div class="panel panel-default">
    <div class="panel-heading">
      <a data-toggle="collapse" data-parent="#accordion" href="#nisanyetkilistesi">
        ÖĞRETMEN NİŞAN YETKİLERİ LİSTESİ
      </a>
    </div>
    <div id="nisanyetkilistesi" class="panel-collapse">
      <div class="panel-body">';
       $html .='<table class="table table-bordered table-striped table-condensed table-responsive">
  <tr>
    <th class="span1">No</th>
    <th class="span2">Öğretmen </th>
    <th class="span9">Yetkili Olduğu Nişanlar</th>
  </tr>';
        $i=0;
        foreach ($rs as $log) {
   $html .= '<tr>' ;
   $html .=' <td>'.++$i.'</td>';
   $html .= '<td>'.$log->ogretmen_ad.' '.$log->ogretmen_soyad.'</td>';
   $html .= '<td>'.$log->yetkiler.'</td>';
   $html .= '</tr>';
      }
$html .= '</table>';
        $rs->close();
$html .= '</div></div></div></div>';
        return $html;
    }else{
        return  block_nisan_rozet_mesajyaz(null,'Hiçbir Öğretmene nişan atama yetkisi vermediniz.
        Lütfen ayarlar menüsünden yetki veriniz',null,true);
    }

}
function block_nisan_rozet_controlduyuru(){
    global $DB;
    if($DB->record_exists('block_nisan_rozet_duyuru',array('aktif'=>1))){
        $sql="SELECT icerik,onem,tarih FROM {block_nisan_rozet_duyuru} WHERE aktif =? ORDER BY onem ASC";
        $rs=$DB->get_recordset_sql($sql,array(1),null,null);
        $html = '<div class="headline text-center">YÖNETİMDEN DUYURULAR</div><div class="page-context-header"></div>';
        foreach ($rs as $log){
           switch ($log->onem) {
               case 0 :
               $html .= block_nisan_rozet_mesajyaz('error','('.date("d.m.Y",$log->tarih).') '.$log->icerik,null,true);
               break;
               case 1 :
                   $html .= block_nisan_rozet_mesajyaz('warning','('.date("d.m.Y",$log->tarih).') '.$log->icerik,null,true);
                   break;
               case 2 :
                   $html .= block_nisan_rozet_mesajyaz('info','('.date("d.m.Y",$log->tarih).') '.$log->icerik,null,true);
                   break;
           }

        }

        $rs->close();
        return $html;
    }else{
        return  '';
    }

}
function block_nisan_rozet_duyurumesajyolla(){
    global $DB,$USER,$CFG;
    require_once($CFG->dirroot.'/message/lib.php');

    $duyuru = $DB->get_records('block_nisan_rozet_duyuru',array('aktif'=>1,'notification'=>0),null,'id');
    if ($duyuru){
        foreach ($duyuru as $duy){
            $sql="SELECT DISTINCT u.id,u.firstname,u.lastname 
  FROM {user} u
  JOIN {role_assignments} ra ON ra.userid = u.id
  JOIN {role} r ON r.id = ra.roleid AND r.archetype = 'coursecreator'
  WHERE u.suspended = 0 AND u.deleted = 0
 ";
    $rs = $DB->get_records_sql($sql, array(), null, null);
    if($rs){
          $message = new \core\message\message();
          $message->component = 'block_nisan_rozet';
          $message->name = 'duyuru';
          $message->userfrom = core_user::get_noreply_user();
          $message->subject = 'Yeni Duyuru Eklendi';
          $message->fullmessage = 'Yönetim Yeni Duyuru Ekledi';
          $message->fullmessageformat = FORMAT_MARKDOWN;
          $message->fullmessagehtml = '<p>Yönetim Yeni Duyuru Ekledi
 <br> <a href="'.$CFG->wwwroot.'/blocks/nisan_rozet/view.php?viewpage=2&section=Anasayfa">Görüntüle</a></p>';
          $message->smallmessage = 'Duyuru Eklendi';
          $message->notification = '1';
          $message->contexturl = $CFG->wwwroot.'/blocks/nisan_rozet/view.php?viewpage=2&section=Anasayfa';
          $message->contexturlname = 'Duyuruya Git';
        foreach ($rs as $log)  {
            $msgcopy = clone($message);
            $user = $DB->get_record('user',array('id'=>$log->id));
            $msgcopy->userto = $user;
            message_send($msgcopy);
            $DB->set_field('block_nisan_rozet_duyuru','notification','1',array('id'=>$duy->id));
         }

        }
        }
    }
}
function block_nisan_rozet_nisanmesajyolla(){
    global $DB,$CFG;
    core_php_time_limit::raise();
    raise_memory_limit(MEMORY_EXTRA);
    require_once($CFG->dirroot.'/message/lib.php');

    $ogrs = $DB->get_records('block_nisan_rozet_atama',array('notification'=>0),null,'id,ogrid,nisan_id');
    if($ogrs){
        $log= new mylog(13);
        $log->tarih = time();
        $log->content ='';
        $message = new \core\message\message();
        $message->component = 'block_nisan_rozet';
        $message->name = 'duyuru';
        $message->userfrom = core_user::get_noreply_user();
        $message->subject = 'Yeni Nişan Kazandınız';
        $message->fullmessage = ' TEBRİKLER Yeni Nişan Kazandınız';
        $message->fullmessageformat = FORMAT_MARKDOWN;
        $message->smallmessage = 'TEBRİKLER Nişan Kazandınız';
        $message->notification = '1';
        $message->contexturlname = 'Nişanı Görüntüle';
        foreach ($ogrs as $ogr)  {
            $msgcopy = clone($message);
            $user = $DB->get_record('user',array('id'=>$ogr->ogrid));
            $msgcopy->fullmessagehtml = 'TEBRİKLER '.getnisanlink($ogr->nisan_id).' Nişanı Kazandınız';
            $msgcopy->contexturl = $CFG->wwwroot.'/blocks/nisan_rozet/view.php?viewpage=3&nisanid='.$ogr->nisan_id;
            $msgcopy->userto = $user;
            message_send($msgcopy);
            $DB->set_field('block_nisan_rozet_atama','notification','1',array('id'=>$ogr->id));
            $log->content .= getpersonlink($ogr->ogrid).' , ';
        }
        $log->content .= ' kullanıcıya Nişan Tebrik Mesajı Gönderildi';
        $log->trigger();
    }


}
function block_nisan_rozet_rozetkazanimmesajyolla(){
    global $DB,$CFG;
    core_php_time_limit::raise();
    raise_memory_limit(MEMORY_EXTRA);
    require_once($CFG->dirroot.'/message/lib.php');
    $rs = $DB->get_records('block_nisan_rozet_winner',array('notification'=>0),null,'id,ogrid,message,rozet_id,notification');
    if($rs){
        $log= new mylog(15);
        $log->tarih = time();
        $log->content = '';
        $message = new \core\message\message();
        $message->component = 'block_nisan_rozet';
        $message->name = 'duyuru';
        $message->userfrom = core_user::get_noreply_user();
        $message->subject = 'Yeni Rozet Kazandınız';
        $message->fullmessage = ' TEBRİKLER Yeni Rozet Kazandınız';
        $message->fullmessageformat = FORMAT_MARKDOWN;
        $message->notification = '1';
        $message->contexturlname = 'Rozeti Görüntüle';
        foreach ($rs as $ogr){
            $msgcopy = clone($message);
            $msgcopy->smallmessage = html_to_text($ogr->message);
            $msgcopy->fullmessagehtml = $ogr->message;
            $msgcopy->contexturl = $CFG->wwwroot.'/badges/overview.php?id='.$ogr->rozet_id;
            $user = $DB->get_record('user',array('id'=>$ogr->ogrid));
            $msgcopy->userto = $user;
            message_send($msgcopy);
            $DB->set_field('block_nisan_rozet_winner','notification','1',array('id'=>$ogr->id));
            $log->content .= getpersonlink($ogr->ogrid).' , ';
        }
        $log->content .= ' kullanıcıya Rozet Kazanma Tebrik Mesajı Gönderildi';
        $log->trigger();

    }
}
function block_nisan_rozet_aktifduyurusayisi(){
    global $DB;
    if($DB->record_exists('block_nisan_rozet_duyuru',array('aktif'=>1))){
        return '<span class="badge badge-info">'.$DB->count_records('block_nisan_rozet_duyuru',array('aktif'=>1)).'</span>';
    }else{
        return '';
    }
}
function block_nisan_rozet_ogretmencontrolnisan(){
    global $DB,$USER;
    if($DB->record_exists('block_nisan_rozet_settings',array('ogretmen_id'=>$USER->id))){
        $sql="SELECT n.tanim,n.name,n.id FROM {block_nisan_rozet_settings} s
LEFT JOIN {block_nisan_rozet_nisan} n ON n.id=s.yetkisi
WHERE yetki_tur=1 AND ogretmen_id=$USER->id";
        $rs=$DB->get_recordset_sql($sql,array(),null,null);
        $html = '<div class="panel-group" id="accordion">
  <div class="panel panel-default">
    <div class="panel-heading">
      <a data-toggle="collapse" data-parent="#accordion" href="#nisanyetkilistesi">
      SAYIN ,'.fullname($USER).' NİŞAN ATAMA YETKİLERİNİZ
      </a>
    </div>
    <div id="nisanyetkilistesi" class="panel-collapse">
      <div class="panel-body">';
        $html .='<table class="table table-bordered table-striped table-condensed table-responsive">
  <tr>
    <th class="span1">No:</th>
    <th class="span1">Nişan Resmi </th>
    <th class="span3">Nişan Adı</th>
    <th class="span7">Nişan Tanımı</th>
  </tr>';
        $i=0;
        foreach ($rs as $log) {
            $html .= '<tr>' ;
            $html .=' <td>'.++$i.'</td>';
            $html .= '<td>'.block_nisan_rozet_nisanresimal($log->id).'</td>';
            $html .= '<td>'.$log->name.'</td>';
            $html .= '<td>'.$log->tanim.'</td>';
            $html .= '</tr>';
        }
        $html .= '</table>';
        $rs->close();
        $html .= '</div></div></div></div>';
        return $html;
    }else{
        if(!is_siteadmin()) {
            return block_nisan_rozet_mesajyaz(null, 'Sayın ' . fullname($USER) . ' hiçbir nişan atama yetkisine sahip değilsiniz.
        Lütfen sistem yöneticisiyle irtibat kurun', null, true);
        }else{
            return '' ;
        }
    }

}
/**
 * @param string $sec
 * @param string $mesaj
 * @param null $id
 * @param bool $tur
 * @return string
 */
function block_nisan_rozet_mesajyaz($sec=null,$mesaj,$id=null,$tur=false){
    if($tur){

        return '<div class="alert alert-block alert-'.$sec.' fade in">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <p>'.$mesaj.'</p>
            </div>';
    }else{
        switch ($sec){
            case 'danger':
                $fa = 'exclamation-triangle';
            break;
            case 'warning':
                $fa = 'exclamation-triangle';
                break;
            case 'success':
                $fa = 'check' ;
                break;
            case 'info':
                $fa = 'info-circle' ;
                break;
            default :
                $fa = '';
        }

        return  '<div id="'.$id.'" class="row-fluid"><div class="alert alert-'.$sec.' text-center "><i class="fa fa-'.$fa.' fa-2x"></i>&nbsp; &nbsp; '.$mesaj.'</div></div>';

    }
}
function block_nisan_rozet_ogretmensec(){
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
function block_nisan_rozet_nisanyetkidbkayit($N,$nisanlar,$ogretmen_id){
    if(!empty($N)){
        global $DB;
        $records=array();
        $sql="SELECT u.id,u.firstname,u.lastname FROM {user} u WHERE u.id=?";
        $rs=$DB->get_record_sql($sql,Array($ogretmen_id));
        for($a=0;$a < $N;$a++){
            $nisan=$nisanlar[$a];
            $record[$a] = new stdClass();
            $record[$a]->ogretmen_id=$ogretmen_id;
            $record[$a]->ogretmen_ad=$rs->firstname;
            $record[$a]->ogretmen_soyad=$rs->lastname;
            $record[$a]->yetkisi=$nisan;
            $record[$a]->yetki_tur=1;
            $records[]=$record[$a];
        }
        try {

            $DB->insert_records('block_nisan_rozet_settings', $records);
            return block_nisan_rozet_mesajyaz('success','Başarılı Bir Şekilde Atama Yapıldı','uyarimesaj',false);
        }
        catch (Exception $e){
            return block_nisan_rozet_mesajyaz('danger',$e->getMessage(),'uyarimesaj',false);
        }

    }else{
        return block_nisan_rozet_mesajyaz('danger','Atama Başarısız Eksik Parametre Programcıyla iletişime geçin!','uyarimesaj',false);
    }





}
/**
 * @param string $filtre
 * @return string
 */
function block_nisan_rozet_selectmenu($filtre,$id=null,$bool=false){
  global $DB,$USER;
    switch ($filtre){
        case $filtre == 'nisansec':
            if($bool){
                $sql="SELECT * FROM {block_nisan_rozet_nisan} 
                       WHERE id 
                       NOT IN ( SELECT nisan_id FROM {block_nisan_rozet_kriter} 
                       WHERE nisan_id
                        NOT IN ($id))";
                $rs = $DB->get_records_sql($sql,array(),null,null);
            }
            else{
            if(is_siteadmin()){
                $rs=$DB->get_records('block_nisan_rozet_nisan',null,null,'*',null,null);
            }else{
                $yetkiids =$DB->get_records_menu('block_nisan_rozet_settings',
                        array('ogretmen_id'=>$USER->id,'yetki_tur'=>1),null,'id,yetkisi',null,null);
                $rs=$DB->get_records_list('block_nisan_rozet_nisan','id',$yetkiids,null,'*',null,null);
            }
            }
            $html = '<select id="nisanselect" name="nisanselect">';
            $html .= '<option value="-1">Nişan Seçiniz</option>';
            foreach ($rs as $log){
               if ($id && $id == $log->id) {
                    $html .= '<option value ="' . $log->id . '" selected>' . $log->name . '</option>';
               }
                else{
                    $html .= '<option value ="' . $log->id . '" >' . $log->name . '</option>';

                }
            }

            $html .= '<select>';
            return $html;
        break;
        case  'sinifsec':
            $rs=$DB->get_recordset('cohort',null,null,'id,name',null,null);
            $html = '<select id="sinifselect" name="sinifselect">';
            $html .= '<option value="-1">Sınıf Seçiniz</option>';
            $html .= '<option value="-2">TÜM SINIFLAR</option>';
            foreach ($rs as $log){
                $html .= '<option value ="'.$log->id.'">'.$log->name.'</option>';
            }
            $html .= '<select>';
            $rs->close();
            return $html;
            break;
        case  'kurssec':
            global $DB;
            $sql="SELECT id,fullname from {course}";
            $count  =  $DB->record_exists_sql($sql, array ($params=null));
            if($count >= 1) {
                $rs = $DB->get_recordset_sql($sql, array(), null, null);
                $html  ='<select id="kurssec">';
                $html .='<option value="-1">Kurs Seçiniz:</option>';
                foreach ($rs as $log){
                    $html .='<option value="'.$log->id.'">'.$log->fullname.'</option>';
                }
                $rs->close();


            }else{
                $html='<option value="-2">Seçilecek Kurs Bulunamadı!</option>';

            }
            $html .='<select>';
            return $html;
            break;
        case  'kurssec_ortalama':
            global $DB;
            $sql="SELECT id,fullname from {course}";
            $count  =  $DB->record_exists_sql($sql, array ($params=null));
            if($count >= 1) {
                $rs = $DB->get_recordset_sql($sql, array(), null, null);
                $html  ='<select id="kurssec_ortalama">';
                $html .='<option value="-1">Kurs Seçiniz:</option>';
                foreach ($rs as $log){
                    $html .='<option value="'.$log->id.'">'.$log->fullname.'</option>';
                }
                $rs->close();


            }else{
                $html='<option value="-2">Seçilecek Kurs Bulunamadı!</option>';

            }
            $html .='<select>';
            return $html;
            break;
        case  'bolumsec':
        $html ='<select  id="bolumsec">
            <option value="-1">Bölüm Seçiniz:</option>
            </select>';

        return $html;
        break;
        case  'bolumsec_ortalama':
            $html ='<select  id="bolumsec_ortalama">
            <option value="-1">Bölüm Seçiniz:</option>
            </select>';

            return $html;
            break;
        case  'sinavsec':
            $html ='<select  id="sinavsec">
          <option value="-1">Sınav Seçiniz:</option>
           </select>';

            return $html;
            break;
        case  'rozetsec':
            global $DB;
            $sql="SELECT id,name FROM {badge} WHERE status = 1 OR status = 3";
            $rs = $DB->get_records_sql_menu($sql,array(),null,null);
            $html  = '<select id="rozetsec" name="rozetsec">';
            $html .= '<option value="-1">Rozet Seçiniz:</option>';
            if($rs) {
                foreach ($rs as $key => $value) {
                    if($id && $id == $key){
                        $html .= '<option value="' . $key . '" selected>' .$value . '</option>';
                    }else{
                        $html .= '<option value="' . $key . '">' .$value . '</option>';
                    }

                }
            }
            $html .='<select>';
            return $html;
            break;



    }

}
function block_nisan_rozet_ogrlistetablo($sql,$from=null,$to=null){
   if(isset($sql)) {
       global $DB;
       $rs =$DB->get_recordset_sql($sql,array(),null,null);
       if($rs->valid()) {

           $html = '<div class="widget">
                <div class="widget-header">
                  <div class="title">
                    <span class="fs1" aria-hidden="true" ><i class="fa fa-users fa-2x" aria-hidden="true"></i></span> ÖĞRENCİ LİSTESİ
                    </div>
                    <div class="tools pull-right"><button type="submit" id="btnnisanver" class="btn btn-mini  " name="submit" >NİŞAN VER</button></div>
                </div>
                <div class="widget-body">';
           $html .='<table id="ogrlistesi" class="table table-bordered table-striped table-condensed table-responsive">
 <thead>
  <tr>
    <th class="span1">No</th>
    <th class="span4">Ad</th>
    <th class="span4">Soyad</th>
    <th class="span2">Sınıf</th>
    <th class="span1"><input type="checkbox" class="usercheckboxall" id="selectall" name="" value=""></th>
  </tr>
  </thead>';
           $i=0;
           $html .='<tbody>';
           foreach ($rs as $log){
               if(!isset($log->puan)){
                   $log->puan =null;
               }
               
               if($log->id != -1 && $log->puan >= $from && $log->puan <= $to ){
               $html .='<tr>';
               $html .='<td>'.++$i.'</td>';
               $html .='<td>'.$log->firstname.'</td>';
               $html .='<td>'.$log->lastname.'</td>';
               $html .='<td>'.$log->sinif.'</td>';
               $html .='<td><input type="checkbox" class="usercheckbox" name="ogr[]" value="'.$log->id.'" /></td>';
               $html .='</tr>';
               }
           }
           $html .='</tbody>';
           $html .= '</table>';
           $html .='<div class="text-right"><input type="submit" id="btnnisanver" class="btn btn-mini " name="submit" value="NİŞAN VER"  /></div>';
           $html .= '</div>
                  </div>';
    
       }else {
           $html = block_nisan_rozet_mesajyaz('info','Kayıt Bulunamadı','uyarimasaj',false);
       }
       $rs->close();
   return $html;
   }else {
       return block_nisan_rozet_mesajyaz('warning','Lütfen Seçim Yapın','uyarimesaj',false);
   }

}
function block_nisan_rozet_kriteryaz(){
    global $DB,$USER;
    $sql ="SELECT k.id,k.nisan_id AS nid,k.adet AS adet,k.rozet_id AS rid
           FROM {block_nisan_rozet_kriter} k 
           LEFT JOIN {block_nisan_rozet_nisan} n ON n.id = k.nisan_id
           LEFT JOIN {badge} b ON b.id = k.rozet_id
           ";
    $rs =  $DB->get_records_sql($sql,array('aktif'=>1));
    if($rs){
        $html = '<h4>Sayın '.fullname($USER).', Sitedeki Rozet Kazanma Kriterleri;</h4>';
        foreach ($rs as $log){
            $html .= block_nisan_rozet_mesajyaz('info',getnisanlink($log->nid).
                    ' isimli Nişandan ' .$log->adet.' Adet Toplarsanız '.getrozetlink($log->rid).' Rozetini Kazanacaksınız');
        }
    }else{
        $html = block_nisan_rozet_mesajyaz('warning','Henüz Sisteme Kriter Tanımlaması Yapılmadı!');
    }
    return $html;

}
function block_nisan_rozet_profilenode($uid){
    global $DB,$CFG;
   $sql="SELECT nisan_id AS nid ,COUNT(nisan_id) AS toplamnisan,n.name
         FROM {block_nisan_rozet_atama} a 
         LEFT JOIN {block_nisan_rozet_nisan} n ON n.id = a.nisan_id
         WHERE ogrid = ?
         GROUP BY nisan_id";
    $nisanlar =$DB->get_records_sql($sql,array($uid));
    if ($nisanlar){
      $html ='<ul class="badges">';
       foreach ($nisanlar as $nisan){
          $html .='<li>';
           $html .='<a href="'.$CFG->wwwroot.'/blocks/nisan_rozet/view.php?viewpage=3&nisanid='.$nisan->nid.'">';
           $html .='<span class="badge-name">'.$nisan->name.'</span>';
           $html .= block_nisan_rozet_nisanresimal($nisan->nid);
           $html .='<span class="badge-name">( '.$nisan->toplamnisan.' Adet )</span>';
           $html.= '</a>';
          $html .='</li>';
       }
      $html .='</ul>';
       
    }else{
      $html = block_nisan_rozet_mesajyaz('warning','Şu an Nişanınız Yok!');
      
    }
    
    return $html;
}
/**
 * @param int $uid
 * @return string $html
 */
function block_nisan_rozet_profilenodewinnerbadge($uid){
    global $DB,$CFG;
    require_once ($CFG->libdir.'/badgeslib.php');
    $rs = $DB->get_records('block_nisan_rozet_winner',array('ogrid'=>$uid),'tarih','id,nisan_id,adet,rozet_id');
if($rs){
    $html = '<table class="table table-striped table-condensed">';
    foreach ($rs as $log){
        $badge = new badge($log->rozet_id);
        $context = context_system::instance();
        $html .='<tr>';
        $html .='<td><div class="text-center">'.block_nisan_rozet_nisanresimal($log->nisan_id).'</div><div class="text-center">'.getnisanlink($log->nisan_id).' ( '.$log->adet.' )</div></td>';
        $html .='<td><img src="'.$CFG->wwwroot.'/blocks/nisan_rozet/pic/ArrowRight.png" style="height:50px; witdh:50px;"></td>';
        $html .='<td><div class="text-center">'.block_nisan_rozet_print_badge_image($badge,$context,'large').'</div><div class="text-center">'.getrozetlink($log->rozet_id).'</div></td>';
        $html .='</tr>';
    }
    $html .= '</table >';
    
}else{
    $html = block_nisan_rozet_mesajyaz('warning','Henüz Nişanlarınızdan Kazandığınız Rozet yok');
}
    return $html;
}
function block_nisan_rozet_rozetkazanma(){
    global $DB,$CFG;
    require_once ($CFG->libdir.'/badgeslib.php');

    core_php_time_limit::raise();
    raise_memory_limit(MEMORY_EXTRA);
    $sql = "SELECT DISTINCT GROUP_CONCAT(id) AS id,ogrid,COUNT(nisan_id) AS adet,nisan_id FROM {block_nisan_rozet_atama} WHERE nisan_id = ? GROUP BY ogrid";
    $users = $DB->record_exists('block_nisan_rozet_atama',array()) ;
    $kriters = $DB->get_records('block_nisan_rozet_kriter',array('aktif'=>1,'multi'=>0),null,'*');
    $multikriters = $DB->get_records('block_nisan_rozet_kriter',array('aktif'=>1,'multi'=>1),null,'*');

    if(!$users or (empty($kriters) and empty($multikriters))){
        $log = new mylog(-1);
        $log->tarih = time();
        $log->expose = -1;
        $log->content = '****** Rozetbot Çalışmaya Başladı ******';
        $log->content .= '<br>Nişana sahip hiçbir öğrenci veya kriter kaydı bulunamadı !';
        $log->content .= '<br>****** Rozetbot çalışmayı durdurdu ****** ';
        $log->trigger();

    }else {
        $log = new mylog(-2);
        $log->tarih = time();
        $log->expose = -1;
        $log->content = '****** Rozetbot Çalışmaya Başladı ******';
        $log->content .= '<br>Kriter kaydı ve nişana sahip öğrenci bulundu şimdi şartlar araştırılıyor...';
        $log->trigger();
        sleep(1);
        foreach ($kriters as $kriter) {
            $ogrenciler = $DB->get_records_sql($sql, array($kriter->nisan_id));
            if($ogrenciler){
            foreach ($ogrenciler as $ogrenci) {
                if ($kriter->nisan_id == $ogrenci->nisan_id and $kriter->adet <= $ogrenci->adet) {
                    $rozet = new badge($kriter->rozet_id);
                    if ($rozet->is_active() and !$rozet->is_issued($ogrenci->ogrid)) {
                        $rozet->issue($ogrenci->ogrid);
                        $record = new stdClass();
                        $record->tarih = time();
                        $record->ogrid = $ogrenci->ogrid;
                        $record->nisan_id = $ogrenci->nisan_id;
                        $record->adet = $kriter->adet;
                        $record->rozet_id = $kriter->rozet_id;
                        $record->message = $kriter->adet . ' Adet ' . getnisanlink($ogrenci->nisan_id) .
                                ' Nişanına Sahip Olduğunuz için ' . getrozetlink($kriter->rozet_id) . ' Rozetini Kazandınız';
                        $DB->insert_record('block_nisan_rozet_winner', $record, false);
                        $log = new mylog(-4);
                        $log->tarih = time();
                        $log->expose = -1;
                        $log->content =
                                getpersonlink($ogrenci->ogrid) . ' -> ' . getrozetlink($kriter->rozet_id) . ' Rozetini KAZANDI';
                        $log->exposed = $ogrenci->ogrid;
                        $log->rozet_id = $kriter->rozet_id;
                        $delitems = explode(',', $ogrenci->id);
                        for ($a = 0; $a < $kriter->adet; $a++) {
                            $DB->delete_records('block_nisan_rozet_atama', array('id' => $delitems[$a]));
                        }
                        $log->content .= '<br>' . getpersonlink($ogrenci->ogrid) . ' nin Rozete dönüşen nişanları silindi';
                        $log->trigger();
                    } else {
                        $log = new mylog(-5);
                        $log->tarih = time();
                        $log->expose = -1;
                        $log->content = getpersonlink($ogrenci->ogrid) . ' zaten ' . getrozetlink($kriter->rozet_id) .
                                ' rozetine sahip olduğu için veya rozet erişime kapalı olduğu için ATANAMADI! ';
                        $log->trigger();
                    }

                }

            }
        }

        }
        //multi krtierleri dönmeye başla
        if ($multikriters){
            foreach ($multikriters as $kriter) {
                $ogrenciler = $DB->get_records_sql($sql, array($kriter->nisan_id));
                if($ogrenciler){
                    foreach ($ogrenciler as $ogrenci) {
                        if ($kriter->nisan_id == $ogrenci->nisan_id and $kriter->adet <= $ogrenci->adet) {
                            $rozet = new badge($kriter->rozet_id);
                            if ($rozet->is_active() and !$rozet->is_issued($ogrenci->ogrid)) {
                                $rozet->issue($ogrenci->ogrid);
                                $record = new stdClass();
                                $record->tarih = time();
                                $record->ogrid = $ogrenci->ogrid;
                                $record->nisan_id = $ogrenci->nisan_id;
                                $record->adet = $kriter->adet;
                                $record->rozet_id = $kriter->rozet_id;
                                $record->message = $kriter->adet . ' Adet ' . getnisanlink($ogrenci->nisan_id) .
                                        ' Nişanına Sahip Olduğunuz için ' . getrozetlink($kriter->rozet_id) . ' Rozetini Kazandınız';
                                $DB->insert_record('block_nisan_rozet_winner', $record, false);
                                $log = new mylog(-4);
                                $log->tarih = time();
                                $log->expose = -1;
                                $log->content =
                                        getpersonlink($ogrenci->ogrid) . ' -> ' . getrozetlink($kriter->rozet_id) . ' Rozetini KAZANDI';
                                $log->exposed = $ogrenci->ogrid;
                                $log->rozet_id = $kriter->rozet_id;
                                $log->trigger();
                            }

                        }

                    }
                }

            }
        }

        sleep(1);
        $log = new mylog(-1);
        $log->tarih = time();
        $log->content = '****** Rozetbot çalışmayı durdurdu ****** ';
        $log->trigger();
    }
}
//TODO:eski log temizlemeyi test et
function block_nisan_rozet_deloldlog(){
    global $DB,$CFG;

    if($CFG->block_sms_api_oldmessage ==0){
        $date = strtotime("-6 month");
    }
    else if($CFG->block_sms_api_oldmessage ==1){
        $date = strtotime("-9 month");
    }
    else if($CFG->block_sms_api_oldmessage ==2){

        $date = strtotime("-1 year");
    }
    else {

        $date = strtotime("-2 year");
    }
    $select="tarih <= $date ";
    $DB->delete_records_select('block_nisan_rozet_log',$select );

    return true;

}
function block_nisan_rozet_resetallplugin(){
    global $DB;
    try{
        $DB->delete_records('block_nisan_rozet_atama',array());
        $DB->delete_records('badge_manual_award',array());
        $DB->delete_records('badge_issued',array());
        $DB->delete_records('block_nisan_rozet_kriter',array());
        $DB->delete_records('block_nisan_rozet_duyuru',array());
        $DB->delete_records('block_nisan_rozet_log',array());
        $DB->delete_records('block_nisan_rozet_winner',array());
        return block_nisan_rozet_mesajyaz('success','Eklenti Başarıyla SIFIRLANDI');
    }catch (Exception $e){
        return block_nisan_rozet_mesajyaz('danger','Hata oluştu işlem başarısız. Oluşan Hata:'.$e->getMessage());
    }

}
function block_nisan_rozet_print_badge_image(badge $badge, stdClass $context, $size = 'small') {
    global $CFG;
    $fsize = ($size == 'small') ? 'f2' : 'f1';

    $imageurl = moodle_url::make_pluginfile_url($context->id, 'badges', 'badgeimage', $badge->id, '/', $fsize, false);
    // Appending a random parameter to image link to forse browser reload the image.
    $imageurl->param('refresh', rand(1, 10000));
    $px = $CFG->block_nisan_rozet_setimage;
    $attributes = array('src' => $imageurl, 'alt' => s($badge->name), 'class' => 'activatebadge','style'=>'height: '.$px.'; width: '.$px.';');

    return html_writer::empty_tag('img', $attributes);
}
function block_nisan_rozet_kriterrozetcontrol(){
    global $DB;
    $kriters = $DB->get_records('block_nisan_rozet_kriter',array(),null,'id,rozet_id');
    if($kriters){
        foreach ($kriters as $kriter){
            if(!$DB->record_exists('badge',array('id'=>$kriter->rozet_id))){
                $DB->delete_records('block_nisan_rozet_kriter',array('rozet_id'=>$kriter->rozet_id));
            }
        }
    }
    
}

/**
 * @param int $userid
 * @param int $nisanid
 * @return string 
 */
function block_nisan_rozet_ownnisancontrol($userid,$nisanid){
    global $DB;
    $sql="SELECT COUNT(id) AS toplam FROM mdl_block_nisan_rozet_atama
WHERE nisan_id = ? AND ogrid = ?";
    $toplam = $DB->get_field_sql($sql,array($nisanid,$userid));
    if($toplam){
        return block_nisan_rozet_mesajyaz('success','Bu Nişandan '.$toplam.' tanesine Sahipsiniz');
    }else{
        return block_nisan_rozet_mesajyaz('warning','Bu Nişana Sahip Değilsiniz');
    }
}
function block_nisan_rozet_ownwinnerrozetcontrol($userid,$nisanid){
    global $DB,$CFG;
    require_once ($CFG->libdir.'/badgeslib.php');
    $rs = $DB->get_record('block_nisan_rozet_winner',array('ogrid'=>$userid,'nisan_id'=>$nisanid),'tarih,nisan_id,adet,rozet_id');

    if($rs){
        $html ='<div class="text-center"><h5>Bu Nişanınız Rozete Dönüştü</h5></div>';
         $html .= '<table class="table table-striped table-condensed">';
  
        $badge = new badge($rs->rozet_id);
        $context = context_system::instance();
        $html .='<tr>';
        $html .='<td><div class="text-center">'.block_nisan_rozet_nisanresimal($rs->nisan_id).'</div><div class="text-center">'.getnisanlink($rs->nisan_id).' ( '.$rs->adet.' )</div></td>';
        $html .='<td><img src="'.$CFG->wwwroot.'/blocks/nisan_rozet/pic/ArrowRight.png" style="height:50px; witdh:50px;"></td>';
        $html .='<td><div class="text-center">'.block_nisan_rozet_print_badge_image($badge,$context,'large').'</div><div class="text-center">'.getrozetlink($rs->rozet_id).'</div></td>';
        $html .='</tr>';
   
    $html .= '</table >';

    return $html;
}
return '';
}