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
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>
global $DB, $OUTPUT, $PAGE, $CFG, $USER;
require_once('../../config.php');
require_once('okulsis_form.php');
require_once('locallib.php');
$context = context_system::instance();
$PAGE->set_context($context);
//Yetki Ayarları
//$canmanagenisan = has_capability('block/nisan_rozet:managenisan', $context);
// eklenti değişkenleri
$module = required_param('module', PARAM_TEXT);
$viewpage = optional_param('viewpage', 'main', PARAM_TEXT);
$section = optional_param('section', 'Anasayfa', PARAM_TEXT);
$PAGE->set_pagelayout('noblocks');
$PAGE->requires->jquery();
$PAGE->requires->jquery_plugin('ui');
$PAGE->requires->jquery_plugin('ui-css');
$PAGE->requires->jquery_plugin('datatables', 'block_okulsis');
$PAGE->requires->jquery_plugin('datatables_dt', 'block_okulsis');
$PAGE->requires->jquery_plugin('ion.rangeSlider', 'block_okulsis');
$PAGE->requires->jquery_plugin('cookie', 'block_okulsis');
$PAGE->requires->jquery_plugin('select2', 'block_okulsis');
$PAGE->requires->jquery_plugin('select2tr', 'block_okulsis');
$PAGE->requires->jquery_plugin('mousewheel', 'block_okulsis');
//$PAGE->requires->jquery_plugin('datetimepicker', 'block_okulsis');
switch ($module) {
    case 'sms':
        $PAGE->set_title('SMS Yönetimi');
        break;
    case 'tanimlar':
        $PAGE->set_title('Tanımlamalar');
        break;

    default:
        $PAGE->set_title('Okul Yönetim Sistemi');

}
$PAGE->set_heading('okulsis');
$pageurl = new moodle_url('/blocks/okulsis/view.php', array('module' => $module, 'viewpage' => $viewpage));
require_login();
$PAGE->set_url($pageurl);
echo $OUTPUT->header();
$html = html_writer::start_div('sidebar-left');
$html .= html_writer::start_div('page-container');
$html .= html_writer::start_div('main-container row-fluid');
//sol menü oluşturalım
$html .= html_writer::start_div('span2');
$html .= html_writer::start_div('sidebar sidebar-inverse', array('id' => 'main-sidebar'));
$html .= html_writer::start_tag('ul', array('id' => 'mainSideMenu', 'class' => 'nav nav-list nav-side accordion'));
echo $html;
////////////////////////////////////
echo okulsis_menuyaz('dashboard', 'main', 'fa fa-tachometer', 'ANASAYFA');
echo okulsis_menuwithsubmenuyaz('togglesms', 'fa fa-mobile', 'SMS MODULÜ', array(
        '1' => array(
                'module' => 'sms',
                'viewpage' => 'send',
                'icon' => 'fa fa-commenting-o',
                'ilkcontent' => 'Sms',
                'soncontent' => 'Gönder'

        ),
        '2' => array(
                'module' => 'sms',
                'viewpage' => 'report',
                'icon' => 'fa fa-search',
                'ilkcontent' => 'Sms',
                'soncontent' => 'Rapor(Hemen)'

        ),
        '3' => array(
                'module' => 'sms',
                'viewpage' => 'future',
                'icon' => 'fa fa-clock-o',
                'ilkcontent' => 'Sms',
                'soncontent' => 'Rapor(İleri)'

        ),
        '4' => array(
                'module' => 'sms',
                'viewpage' => 'settings',
                'icon' => 'fa fa-cog',
                'ilkcontent' => 'Sms',
                'soncontent' => 'Ayarları'

        )

));
echo okulsis_menuwithsubmenuyaz('toggletanim', 'fa fa-cogs', 'TANIMLAR', array(
        '1' => array(
                'module' => 'tanimlar',
                'viewpage' => 'kurum_ekle',
                'icon' => 'fa fa-plus-circle',
                'ilkcontent' => 'Kurum',
                'soncontent' => 'Ekle'
        ),
        '2' => array(
                'module' => 'tanimlar',
                'viewpage' => 'derslik_ekle',
                'icon' => 'fa fa-plus-circle',
                'ilkcontent' => 'Derslik',
                'soncontent' => 'Ekle'
        ),
        '3' => array(
                'module' => 'tanimlar',
                'viewpage' => 'sinif_ekle',
                'icon' => 'fa fa-plus-circle',
                'ilkcontent' => 'Sınıf',
                'soncontent' => 'Ekle'
        )

));
////////////////////////////////////
$html = html_writer::end_tag('ul');
$html .= html_writer::end_div();//mainslider bitiş
$html .= html_writer::end_div();//solmenü bitiş

//main content oluşturalım
$html .= html_writer::start_div('span10', array('style' => 'margin-left:auto'));
$html .= html_writer::start_div('main-content container-fluid', array('id' => 'main-content'));
echo $html;
switch ($module) {
    case 'dashboard':
        require_once "main.php";
        break;
    case 'sms':
        require_once "SMS/" . $viewpage . ".php";
        break;
    case 'tanimlar':
        require_once "TANIMLAR/" . $viewpage . ".php";
        break;
}
$html = html_writer::end_div();//maincontent bitiş
$html .= html_writer::end_div();//span10 bitiş
$html .= html_writer::end_div();
$html .= html_writer::end_div();
$html .= html_writer::end_div();
echo $html;

$PAGE->requires->js("/blocks/okulsis/Content/libs/modernizr/modernizr-2.6.2/js/modernizr-2.6.2.js", true);
$PAGE->requires->js("/blocks/okulsis/Content/libs/pl-content/bootstrap-modal/js/bootstrap-modalmanager.js");
$PAGE->requires->js("/blocks/okulsis/Content/libs/pl-content/bootstrap-modal/js/bootstrap-modal.js");
$PAGE->requires->js("/blocks/okulsis/Content/libs/pl-content/bootbox/js/bootbox.min.js");
$PAGE->requires->js("/blocks/okulsis/Content/libs/pl-form/jquery.elastic/js/jquery.elastic.js");
$PAGE->requires->js("/blocks/okulsis/Content/libs/pl-system/jquery.nicescroll/js/jquery.nicescroll.min.js");
$PAGE->requires->js("/blocks/okulsis/Content/libs/pl-content/jquery.listnav/js/jquery.listnav.js");
$PAGE->requires->js("/blocks/okulsis/Content/libs/pl-content/list/js/list.min.js");
$PAGE->requires->js("/blocks/okulsis/Content/libs/pl-content/list/js/list.paging.min.js");
$PAGE->requires->js("/blocks/okulsis/Content/libs/pl-content/list/js/list.fuzzySearch.min.js");
$PAGE->requires->js("/blocks/okulsis/Content/libs/pl-content/list/js/list.filter.min.js");
$PAGE->requires->js("/blocks/okulsis/Content/libs/google-code-prettify/js/prettify.js");
$PAGE->requires->js("/blocks/okulsis/Content/libs/pl-system-info/jquery.notyfy/js/jquery.notyfy.js");
$PAGE->requires->js("/blocks/okulsis/Content/libs/pl-form/uniform/js/jquery.uniform.min.js");
echo html_writer::script('', new moodle_url('/blocks/okulsis/jquery/jquery.datetimepicker.full.min.js'));
$params = array($module, $viewpage, $section);
$PAGE->requires->js_init_call('M.block_okulsis.init', $params);
echo $OUTPUT->footer();
