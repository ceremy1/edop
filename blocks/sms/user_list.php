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

$msg=addslashes((String)$_REQUEST['msg']);
$msgheader = addslashes((String)$_REQUEST['header']);
require_once('../../config.php');
require_once('sms_form.php');
require_once("lib.php");

$c_id = required_param('c_id', PARAM_INT);
$r_id = required_param('r_id', PARAM_INT);

$form = new sms_send();
$table= $form->display_report($c_id,$r_id);
$a= html_writer::table($table);
echo $a."<input type='hidden' value=\"'$msg'\" name='msg' />";
echo "<input type='hidden' value=\"'$msgheader'\" name='msgheader' />";

?>