<?php
/**
 * Created by PhpStorm.
 * User: Çaglar
 * Date: 13.12.2016
 * Time: 19:05
 */
global $CFG,$DB;
require_once(__DIR__. '/../../../config.php');
require_once ("$CFG->dirroot/blocks/nisan_rozet/locallib.php");
$sql = "
SELECT GROUP_CONCAT(CONCAT(n.name,' (',toplam,')')separator ' & ') list,ogrid,c.name AS sinif
FROM
(SELECT ogrid, nisan_id,count(nisan_id) AS toplam  FROM {block_nisan_rozet_atama} GROUP BY ogrid,nisan_id) f
LEFT JOIN {block_nisan_rozet_nisan} n ON n.id=f.nisan_id
LEFT JOIN {cohort_members} cm ON cm.userid = f.ogrid
LEFT JOIN {cohort} c On c.id =cm.cohortid
GROUP BY ogrid";
$rs = $DB->get_records_sql($sql,array());
if($rs){
    $html ='<table id="raporlar" class="table table-bordered table-striped table-condensed table-responsive ">
 <thead>
  <tr>
    <th class="span3">Ad & Soyad</th>
    <th class="span1">Sınıf</th>
    <th class="span8">Nişanlar</th>
   </tr>
  </thead>';

    $html .='<tbody>';
    foreach ($rs as $log){
        $html .='<tr>';
        $html .='<td>'.getpersonlink($log->ogrid).'</td>';
        $html .='<td>'.$log->sinif.'</td>';
        $html .='<td>'.$log->list.'</td>';
        $html .='</tr>';
    }
    $html .='</tbody>';
    $html .= '</table>';
    echo ($html);
}else{
    echo(block_nisan_rozet_mesajyaz('warning','Görüntülenecek Rapor Kaydı Bulunumadı'));
}
