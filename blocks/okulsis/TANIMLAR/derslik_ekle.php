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
define('TBLDERSLIK', 'block_okulsis_tanim_derslik');
//parametreleri alalım
$onay = optional_param('onay', null, PARAM_TEXT);
$delid = optional_param('delid', null, PARAM_INT);
$editid = optional_param('editid', null, PARAM_INT);
echo theme_writer::okulsis_pagesablonstart('fa fa-cog', ' TANIMLAR ', 'Genel Tanımlamalar Sayfası');
$mform = new block_okulsis_derslikekle();
echo theme_writer::startnicewell('span6', 'blue', 'Derslik Ekle', 'fa fa-plus-square-o');
if (isset($delid) and $delid > 0) {
    $rs = $DB->get_record(TBLDERSLIK, array('id' => $delid), '*', null);
    if ($onay == 'ok') {
        global $DB;
        $DB->delete_records(TBLDERSLIK, array('id' => $delid));
        redirect(new moodle_url('/blocks/okulsis/view.php?module=tanimlar&viewpage=derslik_ekle'), 'Derslik Başarıyla Silindi', 2,
                \core\output\notification::NOTIFY_SUCCESS);
    } else {
        echo $OUTPUT->confirm(okulsis_mesajyaz('danger', 'Derslik ismini silmek üzeresiniz', 'uyarimesaj') . '<br><strong>' .
                $rs->name . '</strong> isimli Dersliği silmek İstediğinize Emin misiniz?',
                '/blocks/okulsis/view.php?module=tanimlar&viewpage=derslik_ekle&delid=' . $delid . '&onay=ok',
                '/blocks/okulsis/view.php?module=tanimlar&viewpage=derslik_ekle');
    }
} else {
    if (isset($editid) and $editid > 0) {
        $rs = $DB->get_record(TBLDERSLIK, array('id' => $editid), '*');
        $mform = new block_okulsis_derslikekle(null, array('name' => $rs->name, 'id' => $rs->id,'kurum_id'=>$rs->kurum_id));
        $mform->display();

    } else {
        if ($mform->is_cancelled()) {
            redirect(new moodle_url('/blocks/okulsis/view.php?module=tanimlar&viewpage=derslik_ekle'));
        } else {
            if ($fromform = $mform->get_data()) {
                if (empty($fromform->id)) {
                    $DB->insert_record(TBLDERSLIK, $fromform);
                    echo(okulsis_mesajyaz('success', 'Derslik başarıyla kayıt edildi'));
                    $mform->display();
                } else {
                    $DB->update_record(TBLDERSLIK, $fromform);
                    echo(okulsis_mesajyaz('success', 'Derslik başarıyla Güncellendi'));
                    $mform->display();
                }
            } else {

                $mform->display();
            }
        }
    }
}
echo theme_writer::endnicewell();
$table = $mform->display_derslik();
echo theme_writer::startnicewell('span6', 'green', 'Derslik Listesi', 'fa fa-list');
echo html_writer::table($table);
echo theme_writer::endnicewell();
echo theme_writer::okulsis_pagesablonend();
