<?php
/**
 * Created by PhpStorm.
 * User: Çaglar
 * Date: 16.12.2016
 * Time: 18:35
 */
defined('MOODLE_INTERNAL') || die;
global $CFG;
require_once($CFG->dirroot . '/blocks/okulsis/lib.php');

class theme_writer {
    public static function startnicewell($span, $color, $header, $icon) {
        $html = '<div class="' . $span . ' well well-nice bg-' . $color . '-light">';
        $html .= '<h5 class="simple-header"><i class="' . $icon . '" aria-hidden="true"></i>
        &nbsp;&nbsp;' . $header . '</h5>';
        return $html;
    }

    public static function endnicewell() {
        return '</div>';
    }

    public static function okulsis_pagesablonstart($icon, $bigcontent, $smallcontent) {
        $html = html_writer::start_div('row-fluid page-head');
        $html .= html_writer::start_tag('h2', array('class' => 'page-title heading-icon', 'aria-hidden' => 'true'));
        $html .= html_writer::tag('i', '', array('class' => $icon));
        $html .= $bigcontent;
        $html .= html_writer::tag('small', $smallcontent);
        $html .= html_writer::end_tag('h2');
        $html .= html_writer::end_div();
        $html .= html_writer::empty_tag('br');
        $html .= html_writer::start_div('page-content', array('id' => 'page-content'));
        $html .= html_writer::start_tag('section');
        return $html;

    }

    public static function okulsis_pagesablonend() {
        $html = html_writer::end_tag('section');
        $html .= html_writer::end_div();
        return $html;
    }
}

/**
 * @param string $sql
 * @param integer $tabloid
 * @return string
 */
function tabloyaz($sql, $tabloid) {
    global $DB;
    $rs = $DB->get_records_sql($sql, array());
    $html = ' ';
    if ($rs) {
        $html .= '<table id="' . $tabloid . '" class="table table-bordered table-striped table-condensed table-responsive boo-table bg-blue">
                                      <thead>
                                            <tr>
                                                <th class="span1" >
                                                <input type="checkbox" class="checkbox check-all"  name="check-all"></th>
                                                <th class="span6" >Ad & Soyad <span class="column-sorter"></span></th>
                                                <th class="span2" >Bölüm <span class="column-sorter"></span></th>
                                                <th class="span3" >Tel<span class="column-sorter"></span></th>
                                            </tr>
                                        </thead>
                                       <tbody>';
        foreach ($rs as $log) {
            $html .= '<tr>';
            $html .= '<td style="text-align: center;">
                                <input type="checkbox" class="checkbox check-row" value="' . $log->id . '" name="checkRow[]"></td>';
            $html .= '<td>' . okulsis_getpersonlink($log->id) . '</td>';
            $html .= '<td style="text-align: center;">' . $log->department . '</td>';
            $html .= '<td style="text-align: center;">' . $log->phone1 . '</td>';
            $html .= '</tr>';
        }

        $html .= ' </tbody>
                                             </table>';
    } else {
        $html .= okulsis_mesajyaz('warning', 'Listelenecek Kayıt Bulunamadı!');
    }

    return $html;

}

/**
 * @param $sql
 * @param $tabloid
 * @return string
 */
function ortalamatabloyaz($sql, $tabloid) {
    global $DB;
    $rs = $DB->get_records_sql($sql, array());
    $html = ' ';
    if ($rs) {
        $html .= '<table id="' . $tabloid . '" class="table table-bordered table-striped table-condensed table-responsive boo-table bg-blue">
                                      <thead>
                                            <tr>
                                                <th class="span1" >
                                                <input type="checkbox" class="checkbox check-all"  name="check-all"></th>
                                                <th class="span6">Ad & Soyad </th>
                                                <th class="span2">Bölüm</th>
                                                <th class="span2">Tel</th>
                                                <th class="span1">Not</th>
                                            </tr>
                                        </thead>
                                       <tbody>';
        foreach ($rs as $log) {
            $html .= '<tr>';
            $html .= '<td style="text-align: center;">
                                <input type="checkbox" class="checkbox check-row" value="' . $log->id . '" name="checkRow[]"></td>';
            $html .= '<td>' . okulsis_getpersonlink($log->id) . '</td>';
            $html .= '<td style="text-align: center;">' . $log->department . '</td>';
            $html .= '<td style="text-align: center;">' . $log->phone1 . '</td>';
            $html .= '<td style="text-align: center;">' . number_format($log->puan, 2, '.', ',') . '</td>';
            $html .= '</tr>';
        }

        $html .= ' </tbody>
                                             </table>';
    } else {
        $html .= okulsis_mesajyaz('warning', 'Listelenecek Kayıt Bulunamadı!');
    }

    return $html;

}

/**
 * @param array $ogrenciler
 * @param array $sinavlar
 * @param string $tabloid
 * @return string $html
 */
function gelismistabloyaz($ogrenciler, $sinavlar, $tabloid) {
    $html = ' ';
    if ($ogrenciler) {
        $html .= '<table id="' . $tabloid . '" class="table table-bordered table-striped table-condensed table-responsive boo-table bg-blue">
                                      <thead>
                                            <tr>
                                                <th class="span1" >
                                                <input type="checkbox" class="checkbox check-all"  name="check-all"></th>
                                                <th class="span3">Ad & Soyad </th>
                                                <th class="span1">Bölüm</th>
                                                <th class="span1">Tel</th>
                                                <th class="span6">Not</th>
                                            </tr>
                                        </thead>
                                       <tbody>';
        foreach ($ogrenciler as $ogrenci) {
            $html .= '<tr>';
            $html .= '<td style="text-align: center;">
                                <input type="checkbox" class="checkbox check-row" value="' . $ogrenci->id .
                    '" name="checkRow[]"></td>';
            $html .= '<td>' . okulsis_getpersonlink($ogrenci->id) . '</td>';
            $html .= '<td style="text-align: center;">' . $ogrenci->department . '</td>';
            $html .= '<td style="text-align: center;">' . $ogrenci->phone1 . '</td>';
            $html .= '<td style="text-align: center;">';
            foreach ($sinavlar as $sinav) {
                global $DB;
                //$sql="SELECT grade AS puan FROM {quiz_grades} WHERE quiz =$sinav->id AND userid = $ogrenci->id";
                $puan = $DB->get_field('quiz_grades', 'grade', array('userid' => $ogrenci->id, 'quiz' => $sinav->id));
                $isim = explode(" ", $sinav->name);
                if (!empty($puan)) {
                    $html .= $isim[3] . ":" . number_format($puan, 2, '.', ',') . "\n";
                } else {
                    $html .= $isim[3] . ": <span class=\"label label-important\">Girmedi</span>\n";
                }
                //TODO:sınav isimlerini daha mantıklı bir şekilde yap

            }
            $html .= '</td>';
            $html .= '</tr>';
        }
        $html .= ' </tbody>
                   </table>';
    } else {
        $html .= okulsis_mesajyaz('warning', 'Listelenecek Kayıt Bulunamadı!');
    }

    return $html;

}

/**
 * @param null $sec
 * @param string $mesaj
 * @param null $id
 * @param bool $tur
 * @return string
 */
function okulsis_mesajyaz($sec = null, $mesaj, $id = null, $tur = false) {
    if ($tur) {

        return '<div class="alert alert-block alert-' . $sec . ' fade in">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <p>' . $mesaj . '</p>
            </div>';
    } else {
        switch ($sec) {
            case 'danger':
                $fa = 'exclamation-triangle';
                break;
            case 'warning':
                $fa = 'exclamation-triangle';
                break;
            case 'success':
                $fa = 'check';
                break;
            case 'info':
                $fa = 'info-circle';
                break;
            default :
                $fa = '';
        }

        return '<div id="' . $id . '" class="row-fluid"><div class="alert alert-' . $sec . ' text-center "><i class="fa fa-' . $fa .
        ' fa-2x"></i>&nbsp; &nbsp; ' . $mesaj . '</div></div>';

    }
}

/**
 * @param int $id
 * @return string
 */
function okulsis_getpersonlink($id) {
    global $DB, $CFG;
    $rs = $DB->get_record('user', array('id' => $id), 'id,firstname,lastname');
    return '<a href="' . $CFG->wwwroot . '/user/profile.php?id=' . $id . '">' . $rs->firstname . ' ' . $rs->lastname . '</a>';
}

/**
 * @param string $module
 * @param string $viewpage
 * @param string $icon
 * @param string $content
 * @return string $html
 */
function okulsis_menuyaz($module, $viewpage, $icon = 'fa fa-tachometer', $content) {
    $html = html_writer::start_tag('li', array('class' => 'accordion-group'));
    $html .= html_writer::start_div('accordion-heading');
    $html .= html_writer::start_tag('a',
            array('href' => '?module=' . $module . '&viewpage=' . $viewpage, 'data-parent' => '#mainSideMenu',
                    'class' => 'accordion-toggle'));
    //menü iconu
    $html .= html_writer::start_span('item-icon');
    $html .= html_writer::tag('i', '', array('class' => $icon));
    $html .= html_writer::end_tag('i');
    $html .= html_writer::end_span();
    //menü icon bitiş
    $html .= html_writer::tag('i', '', array('class' => 'fa fa-chevron-right pull-right', 'aria-hidden' => 'true'));
    $html .= $content;
    $html .= html_writer::end_tag('i');
    $html .= html_writer::end_tag('a');
    $html .= html_writer::end_div();
    $html .= html_writer::end_tag('li');
    return $html;
}

/**
 * @param string $href
 * @param string $icon
 * @param $content
 * @param array $altbolum
 * @return string $html
 */
function okulsis_menuwithsubmenuyaz($href, $icon, $content, $altbolum) {
    $html = html_writer::start_tag('li', array('class' => 'accordion-group'));
    $html .= html_writer::start_div('accordion-heading');
    $html .= html_writer::start_tag('a',
            array('href' => '#' . $href, 'data-parent' => '#mainSideMenu', 'data-toggle' => 'collapse',
                    'class' => 'accordion-toggle'));
    $html .= html_writer::start_span('item-icon');
    $html .= html_writer::tag('i', '', array('class' => $icon));
    $html .= html_writer::end_tag('i');
    $html .= html_writer::end_span();
    $html .= html_writer::tag('i', '', array('class' => 'fa fa-chevron-right pull-right', 'aria-hidden' => 'true'));
    $html .= $content;
    $html .= html_writer::end_tag('i');
    $html .= html_writer::end_tag('a');
    $html .= html_writer::end_div();
    //üst kısım bitti /alt manu başlangıç
    $html .= html_writer::start_tag('ul', array('id' => $href, 'class' => 'accordion-content nav nav-list collapse'));
    foreach ($altbolum as $item) {
        $html .= html_writer::start_tag('li');
        $html .= html_writer::start_tag('a',
                array('href' => '?module=' . $item['module'] . '&viewpage=' . $item['viewpage']));
        $html .= html_writer::start_span('hidden-tablet');
        $html .= html_writer::tag('i', '', array('class' => $item['icon'], 'aria-hidden' => 'true'));
        $html .= html_writer::end_tag('i');
        $html .= '&nbsp;' . $item['ilkcontent'];
        $html .= html_writer::end_span();
        $html .= '&nbsp;' . $item['soncontent'];
        $html .= html_writer::end_tag('a');
        $html .= html_writer::end_tag('li');
    }
    $html .= html_writer::end_tag('ul');
    $html .= html_writer::end_tag('li');
    return $html;
}

/**
 * @param string $icon
 * @param string $bigcontent
 * @param string $smallcontent
 * @return string $html
 */
function okulsis_pagesablonstart($icon, $bigcontent, $smallcontent) {
    $html = html_writer::start_div('row-fluid page-head');
    $html .= html_writer::start_tag('h2', array('class' => 'page-title heading-icon', 'aria-hidden' => 'true'));
    $html .= html_writer::tag('i', '', array('class' => $icon));
    $html .= $bigcontent;
    $html .= html_writer::tag('small', $smallcontent);
    $html .= html_writer::end_tag('h2');
    $html .= html_writer::end_div();
    $html .= html_writer::empty_tag('br');
    $html .= html_writer::start_div('page-content', array('id' => 'page-content'));
    $html .= html_writer::start_tag('section');
    return $html;
}