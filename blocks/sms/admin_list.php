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
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/* SMS_NetGsm Block
 * SMS_NetGsm block eklentisi tek yollu istenen kullanıcı rollerine sms göndermeye yarar
 * @package blocks
 * @author: Çağlar Mersinli
 * @date: 03.07.2016
*/


require_once('../../config.php');
require_once('sms_form.php');
require_once("lib.php");

$date = required_param('d_id', PARAM_INT);
$ilk =required_param('ilk', PARAM_TEXT);
$son =required_param('son', PARAM_TEXT);
$header =required_param('header', PARAM_TEXT);
global $DB;
$sql="SELECT tel,GROUP_CONCAT( ders, '(',sinif,'):' , mesaj) as mesaj from {block_sms_kaydet} WHERE tarih=$date AND gonderildimi=0
GROUP BY tel
";
$count  =  $DB->record_exists_sql($sql, array ($params=null));
if($count >= 1) {
    $rs = $DB->get_recordset_sql($sql, array(), null, null);
    $satir="<pre>";
    foreach ($rs as $log) {
        $result = $log->tel.",".$ilk.$log->mesaj.".".$son.".";

        $satir .=$result."<br>";
    }
   $satir.="</pre>";
 $rs->close();
}else{

    $satir='<div id="msgsuccess" class="alert alert-danger text-center"><i class="fa fa-exclamation-triangle  fa-2x "></i>&nbsp;&nbsp;Mesaj Bulunamadı!</div>';
}

$modal='<div id="modalmesaj" class="modal hide " tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">'. date('d/m/Y', $date).' TARİHLİ MESAJ LİSTESİ</h3>
  </div>
  <div class="modal-body ">
    '.$satir.'
  </div>
  <div class="modal-footer ">
    <a class="btn" data-dismiss="modal" aria-hidden="true">Kapat</a>
    
  </div>
</div>';

$form = new admin_panel();
$table= $form->display_admin($date,$ilk,$son);
$a= html_writer::table($table);
echo $a;
echo "<input type='hidden' value=\"'$header'\" name='msgheader' />";
echo "<input type='hidden' value='$date' name='gondermetarihi' />";
echo $modal;


?>