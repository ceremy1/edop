<?php
/**
 * Created by PhpStorm.
 * User: Çaglar
 * Date: 19.09.2016
 * Time: 20:43
 */
global $CFG,$DB,$USER;
require_once(__DIR__. '/../../../config.php');
require_once ("$CFG->dirroot/blocks/nisan_rozet/locallib.php");
$id = required_param('nisanid',PARAM_INT);
$rs = $DB->get_record('block_nisan_rozet_nisan',array('id'=>$id));
$sql="SELECT  a.ogrid,u.firstname,u.lastname ,COUNT(a.nisan_id) AS nisansayisi,c.name AS sinif
      FROM  {block_nisan_rozet_atama}  a                             
      LEFT JOIN {user} u ON u.id=a.ogrid 
      LEFT JOIN  {cohort_members} cm ON cm.userid = u.id 
      LEFT JOIN {cohort} c ON c.id = cm.cohortid               
      WHERE a.nisan_id = ?
      AND cm.cohortid
      IN (SELECT DISTINCT MIN(c.cohortid) FROM {cohort_members} c WHERE c.userid=u.id)
      GROUP BY a.ogrid";
$nisanlar= $DB->get_records_sql($sql,array($id));
$uid=$USER->id;

if ($rs) {
    echo '<div class="row-fluid">';
    echo '<div class="span4">';
    echo '<div class="panel panel-info ">
  <div class="panel-heading">Nişan Künyesi</div>
  <div class="panel-body">
    <div class="row-fluid">
    <div class="span4"><div class="text-center">' . block_nisan_rozet_nisanresimal($id) . '</div><div class="text-center">' . $rs->name . '</div></div>
    <div class="span8">
    <dl>
  <dt>Tanım:</dt>
  <dd>' . $rs->tanim . '</dd>
   </dl>
</div>
    </div>
  </div>
</div>';
    echo(block_nisan_rozet_ownnisancontrol($USER->id,$id));
    echo(block_nisan_rozet_ownwinnerrozetcontrol($USER->id,$id));
    
    echo '</div>';
    echo '<div class="span8">';
   
    echo '<div class="panel panel-success">
  <div class="panel-heading">' . $rs->name . ' Nişanına Sahip Kullanıcılar</div>
  <div class="panel-body">';
    if ($nisanlar) {
        if($canviewanothernisanlist){
        $html = '<table id="sahipnisanlistesi" class="table table-bordered table-striped table-condensed table-responsive">';
        $html .= ' 
     <thead>
    <tr>
    <th class="span1">No</th>
    <th class="span3">Ad</th>
    <th class="span4">Soyad</th>
    <th class="span3">Sınıf</th>
    <th class="span1">'.getnisanlink($id).' <br>Sayısı</th>
    </tr>
    </thead>';
        $html .= '<tbody>';
        $i = 0;
        foreach ($nisanlar as $nisan) {
            $html .= '<tr>';
            $html .= '<td>' . ++$i . '</td>';
            $html .= '<td>' . $nisan->firstname . '</td>';
            $html .= '<td>' . $nisan->lastname . '</td>';
            $html .= '<td>' . $nisan->sinif.'</td>';
            $html .= '<td>' . $nisan->nisansayisi . '</td>';
            $html .= '</tr>';
        }
        $html .= '</tbody>';
        $html .= '</table>';
        echo($html);
    }else{
            echo(block_nisan_rozet_mesajyaz('warning','Burayı Görüntüleme Yetkiniz Yok '));
        }
    } else {
        echo(block_nisan_rozet_mesajyaz('warning', 'Bu Nişana Sahip Kullanıcı Bulunamadı !'));
    }
    
    echo '</div>
      </div>';
    echo '</div>';
    
    echo '</div>';
}else{
    echo(block_nisan_rozet_mesajyaz('danger','Nişan Bulunamadı!'));
}