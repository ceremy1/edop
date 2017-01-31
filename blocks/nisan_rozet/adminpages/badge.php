<?php
/**
 * Created by PhpStorm.
 * User: Çaglar
 * Date: 27.09.2016
 * Time: 20:08
 */
global $CFG,$DB,$USER;
require_once(__DIR__. '/../../../config.php');
require_once ("$CFG->dirroot/blocks/nisan_rozet/locallib.php");

$dellid =optional_param('dellid',null,PARAM_INT);
$ok =optional_param('comfirm',null,PARAM_TEXT);
$userid = optional_param('userid',null,PARAM_INT);
$badgeid = optional_param('badgeid',null,PARAM_INT);
$rs = $DB->get_records('badge_issued',array(),'dateissued','*');
if (isset($dellid)){
if($ok == 'ok'){
    $log= new mylog(14);
    $log->tarih = time();
    $log->expose = $USER->id;
    $log->exposed =$userid;
    $log->rozet_id = $badgeid;
if(!$DB->record_exists('block_nisan_rozet_winner',array('ogrid'=>$userid,'rozet_id'=>$badgeid))){
    $DB->delete_records('badge_issued',array('id'=>$dellid));
    $DB->delete_records('badge_manual_award',array('badgeid'=>$badgeid,'recipientid'=>$userid));
    $log->content = getpersonlink($USER->id).' --> '.getpersonlink($userid).' isimli kullanıcıdan '.getrozetlink($badgeid).
            ' isimli Rozeti GERİ ALDI';
    $log->trigger();
    echo(block_nisan_rozet_mesajyaz('success','Atanmış Rozet Başarıyla Geri Alındı'));
    redirect(new moodle_url('/blocks/nisan_rozet/view.php?viewpage='.$viewpage.'&section=rozetyonetim'));

}else{
    $log->content =getpersonlink($USER->id).' --> Eklenti Kriterine Göre Kazanılmış '.getrozetlink($badgeid).' isimli Rozeti 
     '.getpersonlink($userid).' den Geri Almaya çalıştı :(';
    $log->trigger();
  redirect(new moodle_url('/blocks/nisan_rozet/view.php?viewpage='.$viewpage.'&section=rozetyonetim'),'Bu Rozet Eklenti Kriterlerine Göre Kazanılmış Olduğundan GERİ ALINAMAZ',5,$messagetype = \core\output\notification::NOTIFY_ERROR);

}

}else{
    echo(block_nisan_rozet_mesajyaz('info',getpersonlink($userid).' isimli kullanıcıdan  '.getrozetlink($badgeid) .
            ' isimli Rozeti Geri Almak üzeresiniz. Onaylıyor musunuz?',null,false));
    echo '<div class="row-fluid text-center">
<a class="btn btn-success" href="?viewpage='.$viewpage.'&section='.$section.'&dellid='.$dellid.'&userid='.$userid.'&badgeid='.$badgeid.'&comfirm=ok " >Tamam</a>
<a type="button" class="btn btn-danger" href="' . $CFG->wwwroot . '/blocks/nisan_rozet/view.php?viewpage='.$viewpage.'&section=rozetyonetim" >İptal</a>
</div>';

}
}
else if($rs){
    if($CFG->badges_allowexternalbackpack == 1){
     echo(block_nisan_rozet_mesajyaz('danger','Eklentinin Doğru çalışabilmesi için external backpacks ayarlarını kapatmanız önerilir.<a href="'.$CFG->wwwroot.'/admin/settings.php?section=badgesettings"> Ayarlara git</a> ',null,true));
    }
    $html ='<table id="rozetlistesi" class="table table-bordered table-striped table-condensed table-responsive ">
 <thead>
  <tr>
    <th class="span2">Tarih</th>
    <th class="span7">Ad Soyad</th>
    <th class="span2">Rozet</th>
    <th class="span1">İşlem</th>
   </tr>
  </thead>';
    $html .='<tbody>';
    foreach ($rs as $log){
        $html .='<tr>';
        $html .='<td>'.date("d.m.Y",$log->dateissued).'</td>';
        $html .='<td>'.getpersonlink($log->userid).'</td>';
        $html .='<td>'.getrozetlink($log->badgeid).'</td>';
        $html .='<td><a href="?viewpage='.$viewpage.'&section='.$section.'&dellid='.$log->id.'&userid='.$log->userid.'&badgeid='.$log->badgeid.'" class="btn btn-danger">Sil</a></td>';
        $html .='</tr>';
    }
    $html .='</tbody>';
    $html .= '</table>';
    echo ($html);

}else{
    echo(block_nisan_rozet_mesajyaz('warning','Rozete Sahip Kullanıcı Bulunamadı!'));
}