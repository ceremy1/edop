<?php
/**
 * Created by PhpStorm.
 * User: Çaglar
 * Date: 09.09.2016
 * Time: 22:02
 */

echo '<form action="" method="post" name="nisanatama">';
echo '<div class="widget">
                <div class="widget-header">
                  <div class="title">
                    <span class="fs1" aria-hidden="true" ><i class="fa fa-bars fa-2x" aria-hidden="true"></i></span> NİŞAN SEÇME MENÜSÜ
                  </div>
                </div>
                <div class="widget-body">
                  <div class="row-fluid">
<div class="span3">'.block_nisan_rozet_selectmenu('nisansec').'</div>
<div class="span9"><div id="selectnisancontent"></div></div>
</div>
                </div>
              </div>';
include_once ("ogrencilistesifiltre.php");
echo '<img src="pic/Loading.gif" id="load" style="margin-left:6cm;" />';
echo '<div id="ogrencilistesi"></div>';
echo '<input type="hidden" name="viewpage" id="viewpage" value="'.$viewpage.'" />';
echo '<input type="hidden" name="section" id="viewpage" value="'.$section.'" />';
echo '</form>';
if(isset($_REQUEST['submit'])) {
if($_REQUEST['nisanselect'] < 0){
   echo(block_nisan_rozet_mesajyaz('danger','Lütfen Atanacak Nişanı Seçiniz','uyarimesaj',false));
    global $USER;
    $log = new mylog(0);
    $log->content = getpersonlink($USER->id).'--> Nişan ataması yaparken Nişan Seçmedi :)';
    $log->expose = $USER->id;
    $log->tarih = time();
    $log->trigger();
}
else if(empty($_REQUEST['ogr'])){
  echo(block_nisan_rozet_mesajyaz('danger','Lütfen Nişan Atanacak Öğrenci Seçiniz','uyarimesaj',false));
   global $USER;
    $log = new mylog(0);
    $log->content = getpersonlink($USER->id).'--> Öğrenci Seçmeden Nişan atamaya Çalıştı :)';
    $log->expose = $USER->id;
    $log->tarih = time();
    $log->trigger();
}
else{
   $nid = $_REQUEST['nisanselect'];
   $ogrid = $_REQUEST['ogr'];
    $N = count($ogrid);
    global $DB,$USER;
    $records =array();
    for ($a=0 ; $a<$N ; $a++){
        $id = $ogrid[$a];
        $record[$a] = new stdClass();
        $record[$a]->nisan_id = $nid;
        $record[$a]->ogrid = $id;
        $record[$a]->giverid = $USER->id;
        $record[$a]->timecreate = time();
        $records[]=$record[$a];
    }
    try{
        $DB->insert_records('block_nisan_rozet_atama',$records);
        echo(block_nisan_rozet_mesajyaz('success','Başarılı Bir Şekilde Nişan Ataması Yapıldı','uyarimesaj',false));
         $log = new mylog(1);
         $log->nisanverildi($USER->id,$ogrid,$nid);
        // block_nisan_rozet_nisanmesajyolla($ogrid,$nid);
    }catch (Exception $e){
        echo(block_nisan_rozet_mesajyaz('danger',$e->getMessage(),'uyarimesaj',false));
    }
    
    
}
}













