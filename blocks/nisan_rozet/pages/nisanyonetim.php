<?php
/**
 * Created by PhpStorm.
 * User: ÇAĞLAR
 * Date: 13.09.2016
 * Time: 22:33
 */
global $CFG,$DB,$USER,$PAGE;
require_once(__DIR__. '/../../../config.php');
require_once ("$CFG->dirroot/blocks/nisan_rozet/locallib.php");

$context =context_system::instance();
$cannisanduzenlesil = has_capability('block/nisan_rozet:nisanduzenlesil', $context);
$cannisanyonetimbakma = has_capability('block/nisan_rozet:nisanyonetimbakma', $context);
$editid=optional_param('editid',null,PARAM_INT);
$delid=optional_param('delid',null,PARAM_INT);
$nisanid = optional_param('nisanid',null,PARAM_INT);
$ad = optional_param('ad',null,PARAM_TEXT);
$soyad = optional_param('soyad',null,PARAM_TEXT);
$nisanad = optional_param('nisanad',null,PARAM_TEXT);
$ok = optional_param('comfirm',null,PARAM_TEXT);
if($delid){
    if($cannisanduzenlesil){
     if($ok == 'ok'){
         try{
             $log = new mylog(3);
             $log->nisansilindi($delid,$USER);
             $DB->delete_records('block_nisan_rozet_atama',array('id'=>$delid));
             echo(block_nisan_rozet_mesajyaz('success','Sime İşlemi Başarıyla Gerçekleşti','uyarimesaj',false));

             redirect(new moodle_url('/blocks/nisan_rozet/view.php?viewpage='.$viewpage.'&section=nisanyonetim'));
         }catch (Exception $e){
             echo(block_nisan_rozet_mesajyaz('danger',$e->getMessage(),'uyarimesaj',false));

         }


     }else{
         echo(block_nisan_rozet_mesajyaz('info',$ad.' '.$soyad. ' adlı öğrenciden  '.$nisanad .
                 ' isimli nişanı silmek üzeresiniz. Onaylıyor musunuz?',null,false));
         echo '<div class="row-fluid text-center">
<a class="btn btn-success" href="' . $CFG->wwwroot . '/blocks/nisan_rozet/view.php?viewpage='.$viewpage.'&section=nisanyonetim&delid='.$delid.'&comfirm=ok " >Tamam</a>
<a type="button" class="btn btn-danger" href="' . $CFG->wwwroot . '/blocks/nisan_rozet/view.php?viewpage='.$viewpage.'&section=nisanyonetim" >İptal</a>
</div>';
     }
    }else{
        echo (block_nisan_rozet_mesajyaz('warning','Nişanları Silme Yetkiniz Yok','uyarimesaj',false));
    }
}
else if ($editid ){
    if($cannisanduzenlesil) {
        if (isset($_REQUEST["submit"])) {
            $nid = $_REQUEST["nisanselect"];
            if ($nid != -1) {
                try{
                    $ilknisan = $DB->get_field('block_nisan_rozet_atama','nisan_id',array('id' => $editid));
                    $DB->set_field('block_nisan_rozet_atama', 'nisan_id', $nid, array('id' => $editid));
                    echo(block_nisan_rozet_mesajyaz('success', 'Güncelleme Başarılı', 'uyarimesaj', false));
                    $log = new mylog(2);
                    $log->nisanduzenlendi($editid,$ilknisan,$nid,$USER);

                }catch (Exception $e){
                    echo(block_nisan_rozet_mesajyaz('danger',$e->getMessage(),'uyarimesaj',false));
                }

            } else {
                echo(block_nisan_rozet_mesajyaz('danger', 'Nişan Seçmelisiniz', 'uyarimesaj'));
            }
            redirect(new moodle_url('/blocks/nisan_rozet/view.php?viewpage='.$viewpage.'&section=nisanyonetim'));

        } else {
            echo '<form action="" method="post" name="nisanduzenle">';
            echo '<div class="widget">
                <div class="widget-header">
                  <div class="title">
                    <span class="fs1" aria-hidden="true" ><i class="fa fa-user fa-2x" aria-hidden="true"></i></span>
                     ' . $ad . ' ' . $soyad . ' adlı Öğrencinin Nişanı Düzenleniyor....
                  </div>
                </div>
                <div class="widget-body">
                  <div class="row-fluid">
<div class="span3">' . block_nisan_rozet_selectmenu('nisansec', $nisanid) . '</div>
<div class="span9"><div id="selectnisancontent"></div></div>
</div>
                </div>
              </div>';
            echo '<div class="row-fluid">
<input class="btn btn-success" type="submit" name="submit" value ="Güncelle"/>
<a href="' . $CFG->wwwroot . '/blocks/nisan_rozet/view.php?viewpage=1&section=nisanyonetim"><input type="button" class="btn btn-danger"  value="İptal" /></a>
</div>';
            echo '</form>';
        }
    }else{
        echo (block_nisan_rozet_mesajyaz('warning','Nişanları Düzenleme Yetkiniz Yok','uyarimesaj',false));

    }

}
else if($cannisanyonetimbakma){

    echo '<div class="headline text-center">ATANMIŞ NİŞAN LİSTESİ</div><div class="page-context-header"></div>';
    if(is_siteadmin()){
        $sql="SELECT giver.id AS giverid,a.timecreate,a.id,ogr.id AS ogrid,n.id AS nisanid,n.name AS nisanad,ogr.firstname,ogr.lastname,c.name AS sinif
      FROM {block_nisan_rozet_atama}  a
      LEFT JOIN {block_nisan_rozet_nisan}  n ON n.id=a.nisan_id
      LEFT JOIN {user}  ogr ON ogr.id=a.ogrid 
      JOIN {cohort_members} cm ON cm.userid = ogr.id
      JOIN {cohort} c ON c.id=cm.cohortid
      LEFT JOIN {user} giver ON giver.id=a.giverid 
      WHERE  cm.cohortid
      IN (SELECT DISTINCT MIN(c.cohortid) FROM {cohort_members} c WHERE c.userid=ogr.id)
      ORDER BY timecreate DESC ";
    }else {
        $sql = "SELECT giver.id AS giverid,a.timecreate,a.id,ogr.id AS ogrid,n.id AS nisanid,n.name AS nisanad,ogr.firstname,ogr.lastname,c.name AS sinif
      FROM {block_nisan_rozet_atama}  a
      LEFT JOIN {block_nisan_rozet_nisan}  n ON n.id=a.nisan_id
      LEFT JOIN {user}  ogr ON ogr.id=a.ogrid 
      JOIN {cohort_members} cm ON cm.userid = ogr.id
      JOIN {cohort} c ON c.id=cm.cohortid
      LEFT JOIN {user} giver ON giver.id=a.giverid 
      WHERE a.giverid=$USER->id 
      AND cm.cohortid
      IN (SELECT DISTINCT MIN(c.cohortid) FROM {cohort_members} c WHERE c.userid=ogr.id)
      ORDER BY timecreate DESC ";
    }
    $rs=$DB->get_recordset_sql($sql,array(),null,null);
    if($rs->valid()){
        echo '<table id="atanmisnisanlistesi" class="table table-bordered table-striped table-condensed table-responsive">';
        echo ' 
     <thead>
    <tr>
    <th class="span1">No</th>
    <th class="span1">Nişan Adı</th>
    <th class="span2">Öğrenci Ad</th>
    <th class="span2">Öğrenci Soyad</th>
    <th class="span2">Atayan</th>
    <th class="span1">Sınıf</th>
    <th class="span1">Atama Tarihi</th>
    <th class="span2">İşlem</th>
    </tr>
    </thead>';
        echo '<tbody>';
        $i=0;
        foreach ($rs as $log) {

            echo '<tr>';
            echo '<td>'.++$i.'</td>';
            echo '<td>'.getnisanlink($log->nisanid).'</td>';
            echo '<td>'.$log->firstname.'</td>';
            echo '<td>'.$log->lastname.'</td>';
            echo '<td>'.getpersonlink($log->giverid).'</td>';
            echo '<td>'.$log->sinif.'</td>';
            echo '<td>'.date("d.m.Y",$log->timecreate).'</td>';
            echo '<td>
                      <a title="Düzenle" id="btn_edit" href="' . $CFG->wwwroot . '/blocks/nisan_rozet/view.php?viewpage='.$viewpage.'&section=nisanyonetim&ad='.$log->firstname.'&soyad='.$log->lastname.'&nisanid='.$log->nisanid.'&editid='.$log->id.'"  class="btn btn-mini btn-success pull-left"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                      <a title="Sil" href="' . $CFG->wwwroot . '/blocks/nisan_rozet/view.php?viewpage='.$viewpage.'&section=nisanyonetim&ad='.$log->firstname.'&soyad='.$log->lastname.'&nisanad='.$log->nisanad.'&delid='.$log->id.' " class="btn btn-mini btn-danger"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                  </td>';
            echo '</tr>';

        }
        echo '</tbody>';
        echo '</table>';
    }else{
        echo(block_nisan_rozet_mesajyaz('danger','Atanmış Nişan Bulunamadı','uyarinisan',false));
    }
    $rs->close();
}
else {
echo (block_nisan_rozet_mesajyaz('warning','Bu Sayfayı Görmeye Yetkiniz Yok','uyarimesaj',false));
}

