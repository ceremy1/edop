<?php
/**
 * Created by PhpStorm.
 * User: Çaglar
 * Date: 02.01.2017
 * Time: 19:44
 */
global $CFG,$DB;
require_once (__DIR__."/locallib.php");
$context = context_system::instance();
require_capability('block/okulsis:sendsms',$context);
$section = optional_param('section','main',PARAM_TEXT);
if($section == 'main') {
    echo '<div class="row-fluid page-head">
                    <h2 class="page-title heading-icon"  aria-hidden="true"><i class="fa fa-phone-square" aria-hidden="true"></i>
     SMS RAPORLARI <small>Gönderilen smslerin ulaşım raporları</small></h2>
               </div></br>
               ';
    echo '<div id="page-content" class="page-content "><section> ';
    echo '<div class="row-fluid"><div class="span12 well well-impressed bg-gray-light scrollboxfuture" style="height:600px;">
<h5 class="simple-header "><i class="fa fa-list-alt" aria-hidden="true"></i>&nbsp;&nbsp;SMS RAPOR LİSTESİ (İLERİ TARİHLİ GÖNDERİLENLER)</h5>';
    $rs = $DB->get_records('block_okulsis_sms_rapor', array('future'=>1), 'date DESC', '*');
    $html = ' ';
    if ($rs) {
        $html .= '<table id="" class="table table-bordered table-striped table-condensed table-responsive boo-table bg-blue">
                                      <thead>
                                            <tr>
                                                <th class="span1" >No</th>
                                                <th class="span4" >Gönderen<span class="column-sorter"></span></th>
                                                <th class="span2" >Durum<span class="column-sorter"></span></th>
                                                <th class="span3" >Tarih<span class="column-sorter"></span></th>
                                                <th class="span2" >İşlem<span class="column-sorter"></span></th>
                                            </tr>
                                        </thead>
                                       <tbody>';
        $i = 0;
        foreach ($rs as $log) {
            $html .= '<tr>';
            $html .= '<td style="text-align:center;">' . ++$i . '</td>';
            $html .= '<td>' . okulsis_getpersonlink($log->gonderen_id) . '</td>';
            if ($log->code == '00' || $log->code == '01' || $log->code == '02') {
                $html .= '<td style="text-align:center;"><span class="label label-success">Başarılı</span></td>';
            } else {
                $html .= '<td style="text-align:center;"><span class="label label-important">Başarısız</span></td>';
            }

            $html .= '<td style="text-align:center;">' . date('d.m.Y', $log->date) . '</td>';
            $html .= '<td style="text-align:center;"><a id="'.$log->bulkid.'" href="#" class="btn btn-danger btn-small cancelbulkid">Görev İptali</a></td>';
            $html .= '</tr>';
        }
        $html .= '</tbody>
               </table>';
    } else {
        $html .= okulsis_mesajyaz('warning', 'Listelenecek Kayıt Bulunamadı!');
    }
    echo($html);
    echo '</div></div>';
    echo '</section></div>';
}