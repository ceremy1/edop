<?php
/**
 * Created by PhpStorm.
 * User: Çaglar
 * Date: 23.09.2016
 * Time: 22:08
 */
global $CFG,$DB;
require_once(__DIR__. '/../../../config.php');
require_once ("$CFG->dirroot/blocks/nisan_rozet/locallib.php");
if(isset($_REQUEST["delete"])){
     $tur = $_REQUEST["tur"];
    $tarih = $_REQUEST["optionsRadios"];
    if(!empty($tur)){
         switch ($tur){
             case 'tum':
                 $select = "(id > 0)";
                 break;
             case 'RozetBot':
                 $select = "(tur = -5 OR tur= -4 OR tur = -3 OR tur = -2 OR tur = -1)";
                 break;
             case 'Nisan':
                 $select = "(tur = 1 OR tur = 2 OR tur = 3 OR tur = 4 OR tur = 10 OR tur = 11 OR tur = 13)";
                 break;
             case 'Kriter':
                 $select = "(tur = 5 OR tur = 6 OR tur = 7)";
                 break;
             case 'Duyuru':
                 $select = "(tur = 8 OR tur = 9 OR tur = 12)";
                 break;
             case 'Rozet':
                 $select = "(tur = 14 OR tur = 15)";
                 break;
             case 'Uyari':
                 $select = "(tur = 0)";
                 break;
         }
        switch ($tarih){
            case 1:
                $date = strtotime("-1 month");
                $select .= " AND tarih <= $date";
                break;
            case 3:
                $date = strtotime("-3 month");
                $select .= " AND tarih <= $date";
                break;
            case 6:
                $date = strtotime("-6 month");
                $select .= " AND tarih <= $date";
                break;
            
        }

       $rs= $DB->delete_records_select('block_nisan_rozet_log',$select,array());
        
        redirect(new moodle_url('/blocks/nisan_rozet/view.php?viewpage='.$viewpage.'&section='.$section),'Silme İşlemi Başarılı',
                   3,\core\output\notification::NOTIFY_SUCCESS);
        }else{
        redirect(new moodle_url('/blocks/nisan_rozet/view.php?viewpage='.$viewpage.'&section='.$section),'Lütfen İşlem Türlerinden Birini Seçiniz',
                3,\core\output\notification::NOTIFY_WARNING);
    }
}else{
$sql= "SELECT  id,tarih,expose,content ,
  CASE
  WHEN  (tur = -5 OR tur = -4 OR tur = -3 OR tur= -2 OR tur= -1) THEN 'RozetBot'
  WHEN (tur = 0) THEN 'Uyarı'
  WHEN (tur= 1 OR tur = 2 OR tur=  3 OR tur=  4 OR tur= 10 OR tur=  11 OR tur = 13) THEN 'Nişan'
  WHEN (tur = 5 OR tur = 6 OR tur = 7) THEN 'Kriter'
  WHEN (tur = 8 OR  tur = 9 OR tur = 12) THEN 'Duyuru'
  WHEN (tur = 14 OR tur =15) THEN 'Rozet'
  END AS tur
FROM {block_nisan_rozet_log}
ORDER BY tarih DESC ";
core_php_time_limit::raise();
raise_memory_limit(MEMORY_EXTRA);
$rs = $DB->get_records_sql($sql,array());
if($rs){
    echo '<div class="row-fluid"><a href="#dellog" role="button" class="btn btn-danger" data-toggle="modal">Log Temizle</a></div>';
    echo '<div style="height: 10px;"></div>';

    echo '<div id="dellog" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Log Kayıtlarını Temizle</h3>
  </div>
  <div class="modal-body">
    '.block_nisan_rozet_mesajyaz('warning','Silme İşleminden Önce Yedek Almanız Tavsiye Edilir').'
    <form action="" method="post">
    <select name="tur">
    <option value="">İşlem Türü Seçiniz</option>
    <option value="tum">Tüm Kayıtlar</option>
    <option value="RozetBot">RozetBot</option>
    <option value="Nisan">Nişan</option>
    <option value="Kriter">Kriter</option>
    <option value="Duyuru">Duyuru</option>
    <option value="Rozet">Rozet</option>
    <option value="Uyari">Uyarı</option>
    </select>
    <label>
    <input type="radio" name="optionsRadios"  value="bugun" checked>
    Bugünden itibaren
    </label>
    <label>
    <input type="radio" name="optionsRadios"  value="1" >
    1 aydan daha eski 
    </label>
    <label>
    <input type="radio" name="optionsRadios"  value="3" >
    3 aydan daha eski 
    </label>
    <label>
    <input type="radio" name="optionsRadios"  value="6" >
    6 aydan daha eski 
    </label>
  </div>
  <div class="modal-footer">
    <input type="button" class="btn btn-danger" data-dismiss="modal" aria-hidden="true" value="Kapat">
    <input type="submit" name="delete" value="Sil" />
     </form>
  </div>
 </div>';
    $html ='<table id="loglistesi" class="table table-bordered table-striped table-condensed table-responsive ">
 <thead>
  <tr>
    <th class="span3">Tarih</th>
    <th class="span8">İçerik</th>
    <th class="span1">Tür</th>
   </tr>
  </thead>';

    $html .='<tbody>';
    foreach ($rs as $log){
        $html .='<tr>';
        $html .='<td>'.date("d.m.Y  H:i:s",$log->tarih).'</td>';
        $html .='<td>'.$log->content.'</td>';
        $html .='<td>'.$log->tur.'</td>';
        $html .='</tr>';
    }
    $html .='</tbody>';
    $html .= '</table>';
    echo ($html);

}else{
    echo(block_nisan_rozet_mesajyaz('warning','Görüntülenecek Log Kaydı Bulunumadı'));
}
}
