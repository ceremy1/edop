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
$msg=htmlspecialchars((String)$_REQUEST['msg']);
//$msgheader = addslashes((String)$_REQUEST['header']);
$c_id = required_param('class', PARAM_INT);
$date = required_param('date', PARAM_INT);
$ders = required_param('ders', PARAM_TEXT);
//$sinif = required_param('sinif', PARAM_TEXT);
$filtre = required_param('filtre', PARAM_TEXT);
$from=optional_param('from',null,PARAM_TEXT);
$to=optional_param('to',null,PARAM_TEXT);

if ($c_id == -1){
    return;
}

$form = new sms_kaydet();
$table= $form->display_kaydet($c_id,$filtre,$from,$to);
$a= html_writer::table($table);
echo $a;
//echo "<input type='hidden' value=\"'$msgheader'\" name='msgheader' />";
echo "<input type='hidden' value=\"$msg\" name='msg' />";
echo "<input type='hidden' value='$date' name='dateal' />";
echo "<input type='hidden' value='$ders' name='dersal' />";
//echo "<input type='hidden' value='$sinif' name='sinifal' />";
?>