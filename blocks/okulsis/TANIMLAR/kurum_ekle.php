<?php
/**
 * Created by PhpStorm.
 * User: caglar
 * Date: 30.01.2017
 * Time: 16:09
 */
global $DB, $OUTPUT;
require_once(__DIR__ . '/tanim_form.php');
$context = context_system::instance();
require_capability('block/okulsis:tanimlamalar', $context);
//sabitler
define('TBLKURUM', 'block_okulsis_tanim_kurum');
//parametreleri alalım
$onay = optional_param('onay', null, PARAM_TEXT);
$delid = optional_param('delid', null, PARAM_INT);
$editid = optional_param('editid', null, PARAM_INT);
echo theme_writer::okulsis_pagesablonstart('fa fa-cog', ' TANIMLAR ', 'Genel Tanımlamalar Sayfası');
$mform = new block_okulsis_kurumekle();
echo theme_writer::startnicewell('span6', 'blue', 'Kurum Ekle', 'fa fa-plus-square-o');
if (isset($delid) and $delid > 0) {
if(!$DB->record_exists('block_okulsis_tanim_derslik',array('kurum_id'=>$delid))){
    $rs = $DB->get_record(TBLKURUM, array('id' => $delid), 'id,name', null);
    if ($onay == 'ok') {
        global $DB;
        $DB->delete_records(TBLKURUM, array('id' => $delid));
        redirect(new moodle_url('/blocks/okulsis/view.php?module=tanimlar&viewpage=kurum_ekle'), 'Kurum Başarıyla Silindi', 2,
                \core\output\notification::NOTIFY_SUCCESS);
    } else {
        echo $OUTPUT->confirm(okulsis_mesajyaz('danger', 'Kurum ismini silmek üzeresiniz', 'uyarimesaj') . '<br><strong>' .
                $rs->name . '</strong> isimli kurumu silmek İstediğinize Emin misiniz?',
                '/blocks/okulsis/view.php?module=tanimlar&viewpage=kurum_ekle&delid=' . $delid . '&onay=ok',
                '/blocks/okulsis/view.php?module=tanimlar&viewpage=kurum_ekle');
    }
}else{
    echo $OUTPUT->confirm(okulsis_mesajyaz('danger', 'Bu Kuruma Ait Derslik Var Önce Derslikleri Silmelisiniz  ', 'uyarimesaj') ,
            '/blocks/okulsis/view.php?module=tanimlar&viewpage=derslik_ekle',
            '/blocks/okulsis/view.php?module=tanimlar&viewpage=kurum_ekle');
}
} else {
    if (isset($editid) and $editid > 0) {
        $rs = $DB->get_record(TBLKURUM, array('id' => $editid), 'id,name');
        $mform = new block_okulsis_kurumekle(null, array('name' => $rs->name, 'id' => $rs->id));
        $mform->display();

    } else {
        if ($mform->is_cancelled()) {
            redirect(new moodle_url('/blocks/okulsis/view.php?module=tanimlar&viewpage=kurum_ekle'));
        } else {
            if ($fromform = $mform->get_data()) {
                if (empty($fromform->id)) {
                    $DB->insert_record(TBLKURUM, $fromform);
                    echo(okulsis_mesajyaz('success', 'Kurum başarıyla kayıt edildi'));
                    $mform->display();
                } else {
                    $DB->update_record(TBLKURUM, $fromform);
                    echo(okulsis_mesajyaz('success', 'Kurum başarıyla Güncellendi'));
                    $mform->display();
                }
            } else {

                $mform->display();
            }
        }
    }
}
echo theme_writer::endnicewell();
$table = $mform->display_kurum();
echo theme_writer::startnicewell('span6', 'green', 'Kurum Listesi', 'fa fa-list');
echo html_writer::table($table);
echo theme_writer::endnicewell();
echo theme_writer::okulsis_pagesablonend();

