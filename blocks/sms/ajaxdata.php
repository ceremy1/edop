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
$id = required_param('id', PARAM_INT);
$filtre =required_param('filtre',PARAM_TEXT);
if ($filtre == 'kurs'){
   global $DB;
    $sql="SELECT id,name FROM {course_sections} WHERE course=$id";
    $count  =  $DB->record_exists_sql($sql, array ($params=null));
    if($count >= 1) {
        $rs = $DB->get_recordset_sql($sql, array(), null, null);

        $html='<option value="-1">Bölüm Seçiniz:</option>';
            foreach ($rs as $log) {
                if(!empty($log->name)) {

                $html .= '<option value="'.$log->id.'">'.$log->name.'</option>';

            }
        }
     $rs->close();
    }else{
        $html ='<option value="-1">Bölüm Seçiniz</option>';


    }
}else if($filtre == 'bolum'){
    global $DB;
    $sql="SELECT q.id AS id ,q.name As name
    FROM {course_modules} cm
    JOIN {modules} m ON m.id= cm.module
    LEFT JOIN {quiz} q ON q.id = cm.instance
    WHERE m.name = 'quiz' AND cm.section=$id";
    $count  =  $DB->record_exists_sql($sql, array ($params=null));
    if($count >= 1) {
        $rs = $DB->get_recordset_sql($sql, array(), null, null);

        $html='<option value="-1">Sınav Seçiniz:</option>';
        foreach ($rs as $log) {
            if(!empty($log->name)) {

                $html .= '<option value="'.$log->id.'">'.$log->name.'</option>';

            }
        }
  $rs->close();
    }else{
        $html ='<option value="-1">Sınav Seçiniz</option>';
    }


}






echo $html;
?>