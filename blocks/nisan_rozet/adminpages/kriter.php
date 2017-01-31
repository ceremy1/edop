<?php
/**
 * Created by PhpStorm.
 * User: Çaglar
 * Date: 17.09.2016
 * Time: 13:53
 */
global $CFG,$DB,$USER;
require_once(__DIR__. '/../../../config.php');
require_once ("$CFG->dirroot/blocks/nisan_rozet/locallib.php");
$cmd = optional_param('cmd',null,PARAM_TEXT);
$editid = optional_param('editid',null,PARAM_INT);
$delid = optional_param('delid',null,PARAM_INT);
$ok = optional_param('comfirm',null,PARAM_TEXT);
block_nisan_rozet_kriterrozetcontrol();
if ($cmd == 'addnew'){
    echo '<div class="headline text-center">KRİTER EKLENİYOR ...</div><div class="page-context-header"></div>';
    echo(block_nisan_rozet_mesajyaz('info','<i class="fa fa-info-circle fa-2x pull-left" aria-hidden="true"></i> Rozetlerin Listelenmesi için Site rozeti ekleyip 
    <br> ölçüt olarak Manual issue seçmelisiniz.<a href ="'.$CFG->wwwroot.'/badges/newbadge.php?type=1" target="_blank">Rozet Ekle</a>
     <br>
    Erişim Etkin kılınmayan Rozetler listelenmez',null,true));
echo '<form action="" method="post" class="form-inline">';
   echo(block_nisan_rozet_selectmenu('nisansec'));
    echo '&nbsp;&nbsp;';
    echo '<input type="number" class="input-mini" name="adet" placeholder="Adet " max="99"/>';
    echo '&nbsp;&nbsp;';
    echo(block_nisan_rozet_selectmenu('rozetsec'));
    echo '&nbsp;&nbsp;';
    echo '<input type="checkbox" name="aktif" checked> Aktif';
    echo '&nbsp;&nbsp;&nbsp;&nbsp;';
    echo  '<button type="submit" name="submit" class="btn">Ekle</button>';
    echo '&nbsp;&nbsp;';
    echo  '<a class="btn btn-danger" href="'.$CFG->wwwroot.'/blocks/nisan_rozet/view.php?viewpage='.$viewpage.'&section='.$section.'">Vazgeç</a>';
echo '</form>';
    echo '</br>';
    if(isset($_REQUEST['submit'])){

        $nisanid = optional_param('nisanselect',null,PARAM_INT);
        $rozetid = optional_param('rozetsec',null,PARAM_INT);
        $adet    = optional_param('adet',null,PARAM_INT);
        $aktif   = optional_param('aktif',null,PARAM_TEXT);
        if ($DB->record_exists('block_nisan_rozet_kriter',array('nisan_id'=>$nisanid))){
            echo (block_nisan_rozet_mesajyaz('danger','Aynı Nişanın zaten bir kriteri var.','uyarimesaj',false));

        }
        else if( $nisanid > 0 and  $rozetid > 0 and $adet > 0){
            $entry = new stdClass();
            $entry->id = null;
            $entry->nisan_id = $nisanid;
            $entry->adet = $adet;
            $entry->rozet_id = $rozetid;
            $entry->tarih = time();
                if (empty($aktif)) {
                    $entry->aktif = 0;
                }
            try{
                $DB->insert_record('block_nisan_rozet_kriter',$entry,false);
                echo(block_nisan_rozet_mesajyaz('success','Kriter Başarıyla Eklendi','uyarimesaj',false));
                $log = new mylog(5);
                $log->tarih = time();
                $log->expose = $USER->id;
                $log->nisan_id = $nisanid;
                $log->rozet_id = $rozetid;
                $log->content = getpersonlink($USER->id).
                        ' --> Yeni kriter Ekledi :: '.getnisanlink($nisanid).' ('.$adet.')
                        ---------> '.getrozetlink($rozetid);
                $log->trigger();
                redirect(new moodle_url('/blocks/nisan_rozet/view.php?viewpage='.$viewpage.'&section=kriter'));
            }catch (Exception $e){
                echo (block_nisan_rozet_mesajyaz('danger',$e->getMessage(),'uyarimesaj',false));
            }

        }else{
            echo(block_nisan_rozet_mesajyaz('danger','Lütfen Formu Eksiksiz ve Doğru Bir Şekilde Doldurun','uyarimesaj',false));
        }

    }
    
}
else if ($cmd == 'addmultinew'){
    echo '<div class="headline text-center">ÇOKLU KRİTER EKLENİYOR ...</div><div class="page-context-header"></div>';
    echo(block_nisan_rozet_mesajyaz('info','<i class="fa fa-info-circle fa-2x pull-left" aria-hidden="true"></i> Rozetlerin Listelenmesi için Site rozeti ekleyip 
    <br> ölçüt olarak Manual issue seçmelisiniz.<a href ="'.$CFG->wwwroot.'/badges/newbadge.php?type=1" target="_blank">Rozet Ekle</a>
     <br>
    Erişim Etkin kılınmayan Rozetler listelenmez',null,true));
    echo '<form action="" method="post" class="form-inline">';
    echo(block_nisan_rozet_selectmenu('nisansec'));
    echo '&nbsp;&nbsp;';
    echo '<input type="number" class="input-mini" name="adet" placeholder="Adet " max="99"/>';
    echo '&nbsp;&nbsp;';
    echo(block_nisan_rozet_selectmenu('rozetsec'));
    echo '&nbsp;&nbsp;';
    echo '<input type="checkbox" name="aktif" checked> Aktif';
    echo '&nbsp;&nbsp;&nbsp;&nbsp;';
    echo  '<button type="submit" name="submit" class="btn">Ekle</button>';
    echo '&nbsp;&nbsp;';
    echo  '<a class="btn btn-danger" href="'.$CFG->wwwroot.'/blocks/nisan_rozet/view.php?viewpage='.$viewpage.'&section='.$section.'">Vazgeç</a>';
    echo '</form>';
    echo '</br>';
    if(isset($_REQUEST['submit'])){

        $nisanid = optional_param('nisanselect',null,PARAM_INT);
        $rozetid = optional_param('rozetsec',null,PARAM_INT);
        $adet    = optional_param('adet',null,PARAM_INT);
        $aktif   = optional_param('aktif',null,PARAM_TEXT);
        if ($DB->record_exists('block_nisan_rozet_kriter',array('nisan_id'=>$nisanid,'multi'=>0))){
            echo (block_nisan_rozet_mesajyaz('danger','Aynı Nişanın zaten bir kriteri var.','uyarimesaj',false));

        }
        else if( $nisanid > 0 and  $rozetid > 0 and $adet > 0){
            $entry = new stdClass();
            $entry->id = null;
            $entry->nisan_id = $nisanid;
            $entry->adet = $adet;
            $entry->rozet_id = $rozetid;
            $entry->tarih = time();
            $entry->multi = 1;
            if (empty($aktif)) {
                $entry->aktif = 0;
            }
            try{
                $DB->insert_record('block_nisan_rozet_kriter',$entry,false);
                echo(block_nisan_rozet_mesajyaz('success','Kriter Başarıyla Eklendi','uyarimesaj',false));
                $log = new mylog(5);
                $log->tarih = time();
                $log->expose = $USER->id;
                $log->nisan_id = $nisanid;
                $log->rozet_id = $rozetid;
                $log->content = getpersonlink($USER->id).
                        ' --> Yeni Çoklu kriter Ekledi :: '.getnisanlink($nisanid).' ('.$adet.')
                        ---------> '.getrozetlink($rozetid);
                $log->trigger();
                redirect(new moodle_url('/blocks/nisan_rozet/view.php?viewpage='.$viewpage.'&section=kriter'));
            }catch (Exception $e){
                echo (block_nisan_rozet_mesajyaz('danger',$e->getMessage(),'uyarimesaj',false));
            }

        }else{
            echo(block_nisan_rozet_mesajyaz('danger','Lütfen Formu Eksiksiz ve Doğru Bir Şekilde Doldurun','uyarimesaj',false));
        }

    }

}
else if($delid){
    if($ok == 'ok'){
      try{
          $rs = $DB->get_record('block_nisan_rozet_kriter',array('id'=>$delid));
          $log = new mylog(6);
          $log->tarih = time();
          $log->expose = $USER->id;
          $log->nisan_id = $rs->nisan_id;
          $log->rozet_id = $rs->rozet_id;
          $log->content = getpersonlink($USER->id).' --> Kriter Sildi '
                  .getnisanlink($rs->nisan_id).' ('.$rs->adet.') >>>>>>> '.getrozetlink($rs->rozet_id);
          $log->trigger();
         $DB->delete_records('block_nisan_rozet_kriter',array('id'=>$delid));
          echo(block_nisan_rozet_mesajyaz('success','Kriter Başarıyla Silindi','uyarimesaj',false));
          redirect(new moodle_url('/blocks/nisan_rozet/view.php?viewpage='.$viewpage.'&section=kriter'));
      }catch (Exception $e){
          echo (block_nisan_rozet_mesajyaz('danger',$e->getMessage(),'uyarimesaj',false));
      }
    }else{
        echo(block_nisan_rozet_mesajyaz('warning','Kriteri Silmek İstediğinize Emin misiniz?','uyarimesaj'));
        echo '<div class="row-fluid text-center">
<a class="btn btn-success" href="' . $CFG->wwwroot . '/blocks/nisan_rozet/view.php?viewpage='.$viewpage.'&section='.$section.'&delid='.$delid.'&comfirm=ok " >Tamam</a>
<a type="button" class="btn btn-danger" href="' . $CFG->wwwroot . '/blocks/nisan_rozet/view.php?viewpage='.$viewpage.'&section='.$section.'" >İptal</a>
</div>';
    }
    
}
else if ($editid){
    $rs = $DB->get_record('block_nisan_rozet_kriter',array('id'=>$editid),'*');
    echo '<div class="headline text-center">KRİTER DÜZENLENİYOR ...</div><div class="page-context-header"></div>';
   echo(block_nisan_rozet_mesajyaz('info','Önceden Kriter ataması yapılmamış Nişanlar Listelenir','uyarimesaj'));
    echo '<form action="" method="post" class="form-inline">';
    echo(block_nisan_rozet_selectmenu('nisansec',$rs->nisan_id,true));
    echo '&nbsp;&nbsp;';
    echo '<input type="number" class="input-mini" name="adet" placeholder="Adet " max="99" value ="'.$rs->adet.'"/>';
    echo '&nbsp;&nbsp;';
    echo(block_nisan_rozet_selectmenu('rozetsec',$rs->rozet_id));
    echo '&nbsp;&nbsp;';
    if($rs->aktif == 1){
        echo '<input type="checkbox" name="aktif" checked> Aktif';
    }else{
        echo '<input type="checkbox" name="aktif" > Aktif';
    }

    echo '&nbsp;&nbsp;&nbsp;&nbsp;';
    echo  '<button type="submit" name="duzenle" class="btn">Düzenle</button>';
    echo '&nbsp;&nbsp;';
    echo  '<a class="btn btn-danger" href="'.$CFG->wwwroot.'/blocks/nisan_rozet/view.php?viewpage='.$viewpage.'&section='.$section.'">Vazgeç</a>';
    echo '</form>';
    echo '</br>';
    if(isset($_REQUEST['duzenle'])){

        $nisanid = optional_param('nisanselect',null,PARAM_INT);
        $rozetid = optional_param('rozetsec',null,PARAM_INT);
        $adet    = optional_param('adet',null,PARAM_INT);
        $aktif   = optional_param('aktif',null,PARAM_TEXT);
        $multi   =optional_param('multi',null,PARAM_INT);
         if( $nisanid > 0 and  $rozetid > 0 and $adet > 0){
            $entry = new stdClass();
            $entry->id = $editid;
            $entry->nisan_id = $nisanid;
            $entry->adet = $adet;
            $entry->rozet_id = $rozetid;
            $entry->tarih = time();
            if (empty($aktif)) {
                $entry->aktif = 0;
            }else{
                $entry->aktif = 1;
            }
             $entry->multi = $multi;
            try{
                $DB->update_record('block_nisan_rozet_kriter',$entry);
                echo(block_nisan_rozet_mesajyaz('success','Kriter Başarıyla Güncellendi','uyarimesaj',false));
              $log = new mylog(7);
                $log->tarih = time();
                $log->expose = $USER->id;
                $log->nisan_id = $nisanid;
                $log->rozet_id = $rozetid;
                $log->content = getpersonlink($USER->id).' --> Kriter Güncelledi '
                        .getnisanlink($nisanid).'( '.$adet.' )'.' >>>>>>>'.getrozetlink($rozetid);
                $log->trigger();
                redirect(new moodle_url('/blocks/nisan_rozet/view.php?viewpage='.$viewpage.'&section=kriter'));
            }catch (Exception $e){
                echo (block_nisan_rozet_mesajyaz('danger',$e->getMessage(),'uyarimesaj',false));
            }

        }else{
            echo(block_nisan_rozet_mesajyaz('danger','Lütfen Formu Eksiksiz Doldurun','uyarimesaj',false));
        }

    }
}
else{
    echo '<div class="headline text-center">KRİTER LİSTESİ</div><div class="page-context-header"></div>';
   $sql="SELECT n.id AS nisanid,b.id AS rozetid,k.id,adet,aktif,n.name AS nisanad,k.multi AS multi,b.name AS rozetad
         FROM {block_nisan_rozet_kriter} k
         LEFT JOIN {block_nisan_rozet_nisan} n ON n.id=k.nisan_id
         LEFT JOIN {badge} b ON b.id = k.rozet_id
         ORDER BY aktif DESC ";
    $rs=$DB->get_recordset_sql($sql,array(),null,null);
    if ($rs ->valid()){
        echo' <a class="btn btn-success" href="' . $CFG->wwwroot . '/blocks/nisan_rozet/view.php?viewpage='.$viewpage.'&section='.$section.'&cmd=addnew" >Yeni Kriter Ekle</a>
        &nbsp;&nbsp;<a class="btn btn-info" href="' . $CFG->wwwroot . '/blocks/nisan_rozet/view.php?viewpage='.$viewpage.'&section='.$section.'&cmd=addmultinew" >Yeni Çoklu Kriter Ekle</a><br><br>';
        $html ='<table id="kriterlistesi" class="table table-bordered table-striped table-condensed table-responsive">
 <tr>
    <th class="span1">No</th>
    <th class="span3">Nişan Adı</th>
    <th class="span1">Adet</th>
    <th class="span3">Rozet Adı</th>
    <th class="span1">Aktiflik</th>
    <th class="span2">İşlemler</th>
  </tr>
';
        $i=0;
        foreach ($rs as $log){
            if($log->multi == 0){
                $multi = '';
            }else{
                $multi = '<span class="label label-info">Çoklu</span>';
            }
            $html .= '<tr>';
            $html .='<td>'.++$i.'</td>';
            $html .='<td>'.$log->nisanad.'</td>';
            $html .='<td>'.$log->adet.'</td>';
            $html .='<td>'.$log->rozetad.'</td>';
            if ($log->aktif == 1){
                $html .='<td><span class="label label-success">Aktif</span>'.$multi.'</td>';
            }else{
                $html .='<td><span class="label label-important">Pasif</span>'.$multi.'</td>';
            }
            $html .='<td>
          <a title="Düzenle" id="btn_edit" href="?viewpage='.$viewpage.'&section='.$section.'&editid='.$log->id.'&multi='.$log->multi.'"  class="btn btn-success pull-left"><i class="fa fa-pencil" aria-hidden="true"></i></a>
          &nbsp;<a title="Sil" href="?viewpage='.$viewpage.'&section='.$section.'&delid='.$log->id.'" class="btn btn-danger"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                     </td>';
            $html .= '</tr>';
        }
          
        $html .='</table>';
        echo($html);
    }else{
        echo(block_nisan_rozet_mesajyaz('warning','Listelenecek Herhangi bir Kriter Bulunamadı!'));
        echo' <a class="btn btn-success" href="' . $CFG->wwwroot . '/blocks/nisan_rozet/view.php?viewpage='.$viewpage.'&section='.$section.'&cmd=addnew" >Yeni Kriter Ekle</a>
        &nbsp;&nbsp;<a class="btn btn-info" href="' . $CFG->wwwroot . '/blocks/nisan_rozet/view.php?viewpage='.$viewpage.'&section='.$section.'&cmd=addmultinew" >Yeni Çoklu Kriter Ekle</a>';

    }
    $rs->close();

}