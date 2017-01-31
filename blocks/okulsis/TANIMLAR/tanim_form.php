<?php
/**
 * Created by PhpStorm.
 * User: caglar
 * Date: 30.01.2017
 * Time: 16:06
 */
global $CFG;
require_once("{$CFG->libdir}/formslib.php");
require_once($CFG->dirroot . '/lib/filelib.php');
require_once($CFG->dirroot . '/repository/lib.php');

class block_okulsis_kurumekle extends moodleform {
    protected function definition() {
        $mform =& $this->_form;
        // $mform->addElement('header', 'kurumekle', 'Kurum Ekle');
        $mform->addElement('text', 'name', 'Kurum Adı:', array('size' => '30'));
        $mform->setType('name', PARAM_NOTAGS);
        $mform->setDefault('name', $this->_customdata['name']);
        $mform->addRule('name', 'Lütfen Kurum İsmi Giriniz', 'required', 'server');
        $mform->addElement('hidden', 'module', 'tanimlar');
        $mform->setType('module', PARAM_TEXT);
        $mform->addElement('hidden', 'viewpage', 'kurum_ekle');
        $mform->setType('viewpage', PARAM_TEXT);
        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);
        $mform->setDefault('id', $this->_customdata['id']);
        $this->add_action_buttons($cancel = false, $submitlabel = 'Kurum Ekle');
    }

    public function validation($data, $files) {
        global $DB;

        $errors = array();
        if ($DB->record_exists('block_okulsis_tanim_kurum', array('name' => $data['name']))) {
            $errors['name'] = "Zaten aynı isimde kurum var";
        }
        return $errors;

    }

    public function display_kurum() {
        global $DB, $CFG;
        $table = new html_table();
        $sql = "SELECT * FROM {block_okulsis_tanim_kurum} ORDER BY name";
        $rs = $DB->get_records_sql($sql, array(), null, null);
        if ($rs) {
            $table->head = array('No', 'Kurum Adı', 'İşlemler');
            $table->id = 'kurumlistesi';
            $table->attributes['class'] =
                    "table table-bordered table-striped table-condensed table-responsive boo-table bg-white table-content ";
            $table->size = array('5%', '65%', '30%');
            $table->align = array('left', 'left', 'left');
            $table->data = array();
            $i = 0;
            foreach ($rs as $log) {
                $row = array();
                $row[] = ++$i;
                $row[] = $log->name;
                $row[] = '<a title="Düzenle" id="btn_edit" href="' . $CFG->wwwroot .
                        '/blocks/okulsis/view.php?module=tanimlar&viewpage=kurum_ekle&editid=' . $log->id . '"  class="btn btn-success btn-sm" role="button"><i class="fa fa-pencil" aria-hidden="true"></i></a>
        <a title="Sil" href="' . $CFG->wwwroot . '/blocks/okulsis/view.php?module=tanimlar&viewpage=kurum_ekle&delid=' . $log->id . ' " class="btn btn-danger btn-sm" role="button"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
        ';
                $table->data[] = $row;

            }

        } else {
            $row = array();
            $row[] = okulsis_mesajyaz('warning', 'Herhangi Bir Kayıt Bulunamadı !');
            $table->data[] = $row;
        }

        return $table;

    }

}

class block_okulsis_derslikekle extends moodleform {
    protected function definition() {
        global $DB;
        $mform =& $this->_form;
        // $mform->addElement('header', 'kurumekle', 'Kurum Ekle');

        $list = $DB->get_records_menu('block_okulsis_tanim_kurum',array(),'name','id,name');
        $mform->addElement('select', 'kurum_id', 'Kurum Seçiniz:', $list);
        $mform->setType('kurum_id', PARAM_INT);
        $mform->addRule('kurum_id', 'Lütfen Kurum Eklemesi için Yönetiye Başvurun', 'required', 'server');
        $mform->setDefault('kurum_id', $this->_customdata['kurum_id']);
        $mform->addElement('text', 'name', 'Derslik Adı:', array('size' => '30'));
        $mform->setType('name', PARAM_NOTAGS);
        $mform->setDefault('name', $this->_customdata['name']);
        $mform->addRule('name', 'Lütfen Derslik İsmi Giriniz', 'required', 'server');
        $mform->addElement('hidden', 'module', 'tanimlar');
        $mform->setType('module', PARAM_TEXT);
        $mform->addElement('hidden', 'viewpage', 'derslik_ekle');
        $mform->setType('viewpage', PARAM_TEXT);
        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);
        $mform->setDefault('id', $this->_customdata['id']);
        $this->add_action_buttons($cancel = false, $submitlabel = 'Derslik Ekle');
    }

    public function validation($data, $files) {
        global $DB;

        $errors = array();
        if ($DB->record_exists('block_okulsis_tanim_derslik', array('name' => $data['name']))) {
            $errors['name'] = "Zaten aynı isimde Derslik var";
        }
        return $errors;

    }

    public function display_derslik() {
        global $DB, $CFG;
        $table = new html_table();
        $sql = "SELECT d.id,d.name AS derslikadi,k.name AS kurumadi 
                FROM {block_okulsis_tanim_derslik} d
                LEFT JOIN {block_okulsis_tanim_kurum} k ON k.id=d.kurum_id
                ORDER BY derslikadi";
        $rs = $DB->get_records_sql($sql, array(), null, null);
        if ($rs) {
            $table->head = array('No', 'Kurum Adı', 'Derslik Adı', 'İşlemler');
            $table->id = 'kurumlistesi';
            $table->attributes['class'] =
                    "table table-bordered table-striped table-condensed table-responsive boo-table bg-white table-content ";
            $table->size = array('5%', '35%', '30%', '30%');
            $table->align = array('left', 'left', 'left', 'left');
            $table->data = array();
            $i = 0;
            foreach ($rs as $log) {
                $row = array();
                $row[] = ++$i;
                $row[] = $log->kurumadi;
                $row[] = $log->derslikadi;
                $row[] = '<a title="Düzenle" id="btn_edit" href="' . $CFG->wwwroot .
                        '/blocks/okulsis/view.php?module=tanimlar&viewpage=derslik_ekle&editid=' . $log->id . '"  class="btn btn-success btn-sm" role="button"><i class="fa fa-pencil" aria-hidden="true"></i></a>
        <a title="Sil" href="' . $CFG->wwwroot . '/blocks/okulsis/view.php?module=tanimlar&viewpage=derslik_ekle&delid=' .
                        $log->id . ' " class="btn btn-danger btn-sm" role="button"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
        ';
                $table->data[] = $row;

            }

        } else {
            $row = array();
            $row[] = okulsis_mesajyaz('warning', 'Herhangi Bir Kayıt Bulunamadı !');
            $table->data[] = $row;
        }

        return $table;

    }

}
class block_okulsis_sinifekle extends moodleform {
    protected function definition() {
        $mform =& $this->_form;
        // $mform->addElement('header', 'kurumekle', 'Kurum Ekle');
        $mform->addElement('text', 'name', 'Sınıf Adı:', array('size' => '30'));
        $mform->setType('name', PARAM_NOTAGS);
        $mform->setDefault('name', $this->_customdata['name']);
        $mform->addRule('name', 'Lütfen Sınıf İsmi Giriniz', 'required', 'server');
        $mform->addElement('hidden', 'module', 'tanimlar');
        $mform->setType('module', PARAM_TEXT);
        $mform->addElement('hidden', 'viewpage', 'sinif_ekle');
        $mform->setType('viewpage', PARAM_TEXT);
        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);
        $mform->setDefault('id', $this->_customdata['id']);
        $this->add_action_buttons($cancel = false, $submitlabel = 'Sınıf Ekle');
    }

    public function validation($data, $files) {
        global $DB;

        $errors = array();
        if ($DB->record_exists('block_okulsis_tanim_sinif', array('name' => $data['name']))) {
            $errors['name'] = "Zaten aynı isimde Sınıf var";
        }
        return $errors;

    }

    public function display_sinif() {
        global $DB, $CFG;
        $table = new html_table();
        $sql = "SELECT * FROM {block_okulsis_tanim_sinif} ORDER BY name";
        $rs = $DB->get_records_sql($sql, array(), null, null);
        if ($rs) {
            $table->head = array('No', 'Sınıf Adı', 'İşlemler');
            $table->id = 'kurumlistesi';
            $table->attributes['class'] =
                    "table table-bordered table-striped table-condensed table-responsive boo-table bg-white table-content ";
            $table->size = array('5%', '65%', '30%');
            $table->align = array('left', 'left', 'left');
            $table->data = array();
            $i = 0;
            foreach ($rs as $log) {
                $row = array();
                $row[] = ++$i;
                $row[] = $log->name;
                $row[] = '<a title="Düzenle" id="btn_edit" href="' . $CFG->wwwroot .
                        '/blocks/okulsis/view.php?module=tanimlar&viewpage=sinif_ekle&editid=' . $log->id . '"  class="btn btn-success btn-sm" role="button"><i class="fa fa-pencil" aria-hidden="true"></i></a>
        <a title="Sil" href="' . $CFG->wwwroot . '/blocks/okulsis/view.php?module=tanimlar&viewpage=sinif_ekle&delid=' . $log->id . ' " class="btn btn-danger btn-sm" role="button"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
        ';
                $table->data[] = $row;

            }

        } else {
            $row = array();
            $row[] = okulsis_mesajyaz('warning', 'Herhangi Bir Kayıt Bulunamadı !');
            $table->data[] = $row;
        }

        return $table;

    }

}