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
define('TBLSINIF', 'block_okulsis_tanim_sinif');
//parametreleri alalım
$onay = optional_param('onay', null, PARAM_TEXT);
$delid = optional_param('delid', null, PARAM_INT);
$editid = optional_param('editid', null, PARAM_INT);
echo theme_writer::okulsis_pagesablonstart('fa fa-cog', ' TANIMLAR ', 'Genel Tanımlamalar Sayfası');
$mform = new block_okulsis_sinifekle();
echo theme_writer::startnicewell('span6', 'blue', 'Sınıf Ekle', 'fa fa-plus-square-o');
if (isset($delid) and $delid > 0) {

        $rs = $DB->get_record(TBLSINIF, array('id' => $delid), 'id,name', null);
        if ($onay == 'ok') {
            global $DB;
            $DB->delete_records(TBLSINIF, array('id' => $delid));
            redirect(new moodle_url('/blocks/okulsis/view.php?module=tanimlar&viewpage=sinif_ekle'), 'Sınıf Başarıyla Silindi', 2,
                    \core\output\notification::NOTIFY_SUCCESS);
        } else {
            echo $OUTPUT->confirm(okulsis_mesajyaz('danger', 'Sınıf ismini silmek üzeresiniz', 'uyarimesaj') . '<br><strong>' .
                    $rs->name . '</strong> isimli sınıfı silmek İstediğinize Emin misiniz?',
                    '/blocks/okulsis/view.php?module=tanimlar&viewpage=sinif_ekle&delid=' . $delid . '&onay=ok',
                    '/blocks/okulsis/view.php?module=tanimlar&viewpage=sinif_ekle');
        }

} else {
    if (isset($editid) and $editid > 0) {
        $rs = $DB->get_record(TBLSINIF, array('id' => $editid), 'id,name');
        $mform = new block_okulsis_sinifekle(null, array('name' => $rs->name, 'id' => $rs->id));
        $mform->display();

    } else {
        if ($mform->is_cancelled()) {
            redirect(new moodle_url('/blocks/okulsis/view.php?module=tanimlar&viewpage=sinif_ekle'));
        } else {
            if ($fromform = $mform->get_data()) {
                if (empty($fromform->id)) {
                    $DB->insert_record(TBLSINIF, $fromform);
                    echo(okulsis_mesajyaz('success', 'Sınıf başarıyla kayıt edildi'));
                    $mform->display();
                } else {
                    $DB->update_record(TBLSINIF, $fromform);
                    echo(okulsis_mesajyaz('success', 'Sınıf başarıyla Güncellendi'));
                    $mform->display();
                }
            } else {

                $mform->display();
            }
        }
    }
}
echo theme_writer::endnicewell();
$table = $mform->display_sinif();
echo theme_writer::startnicewell('span6', 'green', 'Sınıf Listesi', 'fa fa-list');
echo html_writer::table($table);
echo theme_writer::endnicewell();
echo theme_writer::okulsis_pagesablonend();

