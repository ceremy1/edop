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
/**
 * Created by PhpStorm.
 * User: Çaglar
 * Date: 05.09.2016
 * Time: 17:27
 */
global $CFG;
require_once("{$CFG->libdir}/formslib.php");
require_once($CFG->dirroot.'/lib/filelib.php');
require_once("locallib.php");
class block_nisan_rozet_nisanekle extends moodleform{

    protected function definition() {

        $mform =& $this->_form;
        $filemanageropts = $this->_customdata['filemanageropts'];
        $mform->addElement('header', 'addnisan', 'Nişan Yükle');
        $mform ->addElement('text','name','İsim');
        $mform->setType('name', PARAM_NOTAGS);
        $mform->addRule('name','Lütfen İsim Giriniz','required','server');
        $mform->addElement('filemanager', 'attachments', 'Nişan Resmi Seçiniz:', null, $filemanageropts);
        $mform->addRule('attachments','Lütfen Resim Seçiniz','required','server');
        $mform->addElement('textarea', 'tanim', 'Tanım:', array('rows' => '6', 'cols' => '47', 'maxlength' => '160', 'id' => 'nisantanim'));
        $mform->addRule('tanim', 'Lütfen Nişan Tanımı Giriniz ', 'required', 'server');
        $mform->setType('tanim', PARAM_TEXT);
        $mform->addElement('hidden', 'viewpage', '1');
        $mform->setType('viewpage', PARAM_INT);
        $mform->addElement('hidden', 'section', 'nisanekle');
        $mform->setType('section', PARAM_TEXT);
        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);

        $this->add_action_buttons();
    }
      public function validation($data,$files) {
            global $DB;
            $errors = array();
            if ($data['name'] == "") {
                  $errors['name'] = "Lütfen İsim Giriniz";

            }
            if ($data['tanim'] == "") {
                  $errors['tanim'] = "Lütfen Nişan Tanımı Giriniz";

            }
            if (empty($files['attachments'])) {
                  if (array_key_exists('url', $data)) {
                        $errors['attachments'] = "Resim Seçmelisiniz";
                  }
            }


            return $errors;

      }
  public function display_nisan(){
        global $DB,$CFG;
        $table = new html_table();
        $sql="SELECT * FROM {block_nisan_rozet_nisan}";
        $rs=$DB->get_recordset_sql($sql,array(),null,null);
        if($rs->valid()) {
              
              
              $table->head = array('No', 'Nişan Adı', 'Nişan Resmi', 'Nişan Tanımı', 'İşlemler');
              $table->attributes['class'] = "table table-striped table-bordered table-condensed";
              $table->size = array('2%', '15%', '5%', '73%', '5%');
              $table->align = array('left', 'left', 'left', 'left');
              $table->data = array();
              $i=0;
              foreach ($rs as $log){
                    $row = array();
                    $row[] = ++$i;
                    $row[] = getnisanlink($log->id);
                    $row[] = block_nisan_rozet_nisanresimal($log->id);
                    $row[] = $log->tanim;
                    $row[] = '<a title="Düzenle" id="btn_edit" href="' . $CFG->wwwroot . '/blocks/nisan_rozet/view.php?viewpage=1&section=nisanekle&editid='.$log->id.'"  class="btn btn-success"><i class="fa fa-pencil" aria-hidden="true"></i></a>
        <a title="Sil" href="' . $CFG->wwwroot . '/blocks/nisan_rozet/view.php?viewpage=1&section=nisanekle&delid='.$log->id.' " class="btn btn-danger"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
        ';
                    $table->data[] = $row;
                    
              }

        }else {
              $row = array();
              $row[] = "<div id='load-users' style='border: 1px solid;margin: 10px 0px;padding:15px 10px 15px 50px;background-repeat: no-repeat;background-position: 10px center;color: #00529B;background-image: url(".'pic/info.png'."); background-color: #BDE5F8;border-color: #3b8eb5;'>Kayıt Bulunamadı!</div>";
              $table->data[] = $row;
        }
        $rs->close();
        return $table;

  }

}
class block_nisan_rozet_duyuru extends moodleform{

      protected function definition() {
            $mform =& $this->_form;
            $mform->addElement('header', 'addduyuru', 'Öğretmen Paneline Duyuru Ekle');
            $array = array('1'=>'Görünür','0'=>'Görünmez');
            $mform->addElement('select','aktif','Gösterim:',$array);
            $mform->setType('aktif', PARAM_INT);
            $attributes = array('rows' => '7', 'cols' => '60');
            $mform->addElement('textarea', 'icerik','Duyuru Mesajı', $attributes);
            $mform->addRule('icerik','Lütfen Mesaj Yazınız' , 'required', 'client');
            $mform->addRule('icerik', $errors = null, 'required', null, 'server');
            $mform->setType('icerik', PARAM_TEXT);
            $array = array('0'=>'Yüksek','1'=>'Normal','2'=>'Düşük');
            $mform->addElement('select','onem','Önemi:',$array);
            $mform->setType('onem', PARAM_INT);
            $mform->addElement('hidden', 'viewpage', '1');
            $mform->setType('viewpage', PARAM_INT);
            $mform->addElement('hidden', 'section', 'duyuru');
            $mform->setType('section', PARAM_TEXT);
            $mform->addElement('hidden', 'id');
            $mform->setType('id', PARAM_INT);
            $this->add_action_buttons();

      }
      public function validation($data,$files) {
            $errors = array();
            if ($data['icerik'] == "") {
                  $errors['icerik'] = "Lütfen Mesaj Yazınız";

            }
            return $errors;

      }
      public function display_duyuru(){
            global $DB,$CFG;
            $table = new html_table();
            $sql="SELECT *,
 CASE onem
 WHEN '0' THEN 'YÜKSEK'
 WHEN '1' THEN 'NORMAL'
 WHEN '2' THEN 'DÜŞÜK'
 END AS onemi,
 CASE aktif
 WHEN '0' THEN 'Görünmüyor'
 WHEN '1' THEN 'Görünüyor'
 END AS aktiflik
 FROM {block_nisan_rozet_duyuru} ORDER BY tarih DESC";
            $rs=$DB->get_recordset_sql($sql,array(),null,null);
            if($rs->valid()) {
              $table->head = array('No', 'Tarih', 'Yazan', 'Duyuru','Önemi','Görünme','İşlem');
                  $table->attributes['class'] = "table table-striped table-bordered table-condensed";
                  $table->size = array('2%','8%','15%', '50%', '5%','5%','15%');
                  $table->align = array('left', 'left', 'left', 'left','left','left','left');
                  $table->data = array();
                  $i=0;
                  foreach ($rs as $log){
                        $row = array();
                        $row[] = ++$i;
                        $row[] = date('d.m.Y',$log->tarih);
                        $row[] = $log->yazan;
                        $row[] = $log->icerik;
                        $row[] = $log->onemi;
                        if($log->aktif == 1){
                              $row[] = '<span class="label label-success">'.$log->aktiflik.'</span>';
                        }else{
                              $row[] = '<span class="label label-important">'.$log->aktiflik.'</span>';
                        }
                        $row[] = '<a title="Düzenle" id="btn_edit" href="' . $CFG->wwwroot . '/blocks/nisan_rozet/view.php?viewpage=1&section=duyuru&editid='.$log->id.'"  class="btn btn-success pull-left"><i class="fa fa-pencil" aria-hidden="true"></i></a>
        <a title="Sil" href="' . $CFG->wwwroot . '/blocks/nisan_rozet/view.php?viewpage=1&section=duyuru&delid='.$log->id.' " class="btn btn-danger"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
        ';
                        $table->data[] = $row;

                  }

            }else {
                  $row = array();
                  $row[] = block_nisan_rozet_mesajyaz('warning','Duyuru Kaydı Bulunamadı!');
                  $table->data[] = $row;
            }
            $rs->close();
            return $table;

      }


}