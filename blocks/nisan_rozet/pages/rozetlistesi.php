<?php
/**
 * Created by PhpStorm.
 * User: Çaglar
 * Date: 29.10.2016
 * Time: 12:31
 */
global $CFG,$DB,$USER;
require_once(__DIR__. '/../../../config.php');
require_once ("$CFG->dirroot/blocks/nisan_rozet/locallib.php");
$context =context_system::instance();
$cannisanyonetimbakma = has_capability('block/nisan_rozet:nisanyonetimbakma', $context);
if($cannisanyonetimbakma){
    echo '<div class="headline text-center">ROZET LİSTESİ</div><div class="page-context-header"></div>';   
    $sql="SELECT u.id,u.firstname,u.lastname,c.name AS sinif ,COUNT(bi.badgeid) AS adet,GROUP_CONCAT(b.name) AS rozetler
          FROM {badge_issued} bi
          LEFT JOIN {badge} b ON b.id = bi.badgeid
          LEFT JOIN {user} u ON u.id = bi.userid
          JOIN {cohort_members} cm ON cm.userid = u.id
          JOIN {cohort} c ON c.id=cm.cohortid
          WHERE  cm.cohortid
          IN (SELECT DISTINCT MIN(c.cohortid) FROM {cohort_members} c WHERE c.userid=u.id)
          GROUP BY bi.userid
          ";
    $rs=$DB->get_recordset_sql($sql,array(),null,null);
    if($rs->valid()){
        echo '<table id="rozetlistesi" class="table table-bordered table-striped table-condensed table-responsive">';
        echo ' 
     <thead>
    <tr>
    <th class="span1">No</th>
    <th class="span2">Ad</th>
    <th class="span2">Soyad</th>
    <th class="span2">Sınıf</th>
    <th class="span1">Adet</th>
    <th class="span4">Rozetler</th>
    </tr>
    </thead>';
        echo '<tbody>';
        $i=0;
        foreach ($rs as $log) {
            echo '<tr>';
            echo '<td>'.++$i.'</td>';
            echo '<td>'.$log->firstname.'</td>';
            echo '<td>'.$log->lastname.'</td>';
            echo '<td>'.$log->sinif.'</td>';
            echo '<td>'.$log->adet.'</td>';
            echo '<td>'.$log->rozetler.'</td>';
        }
        echo '</tbody>';
        echo '</table>';
    }
    else{
        echo(block_nisan_rozet_mesajyaz('warning','Henüz Rozete Sahip Öğrenci Yok','uyarinisan',false));
    }
    $rs->close();
}else {
    echo (block_nisan_rozet_mesajyaz('warning','Bu Sayfayı Görmeye Yetkiniz Yok','uyarimesaj',false));
}