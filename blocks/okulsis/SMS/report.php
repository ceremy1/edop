<?php
/**
 * Created by PhpStorm.
 * User: Çaglar
 * Date: 01.01.2017
 * Time: 22:54
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
    echo '<div class="row-fluid"><div class="span12 well well-impressed bg-gray-light scrollboxreportlist" style="height:600px;">
<h5 class="simple-header "><i class="fa fa-list-alt" aria-hidden="true"></i>&nbsp;&nbsp;SMS RAPOR LİSTESİ (HEMEN SEÇENEĞİYLE GÖNDERİLENLER)</h5>';
    $rs = $DB->get_records('block_okulsis_sms_rapor', array('future'=>0), 'date DESC', '*');
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
            $html .= '<td style="text-align:center;"><a href="?module=sms&viewpage=report&section=detay&bulkid='.$log->bulkid.'" class="btn btn-info btn-small"><i class="fa fa-info-circle" aria-hidden="true"></i></a><a id="'.$log->id.'" href="#" class="btn btn-danger btn-small reportsil"><i class="fa fa-trash" aria-hidden="true"></i></a></td>';
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
}else if ($section == 'detay'){
    $bulkid =required_param('bulkid',PARAM_TEXT);
    $gorevrapor = block_okulsis_sms_rapor($bulkid, 2);
    
    echo '<div class="row-fluid page-head">
                    <h2 class="page-title heading-icon"  aria-hidden="true"><i class="fa fa-phone-square" aria-hidden="true"></i>
     SMS DETAYLI RAPOR <small>Smslerin Detaylı Raporları</small></h2>
               </div></br>
               ';
    echo '<div id="page-content" class="page-content "><section>';
    echo '<div class="row-fluid"><div class="span12 well well-impressed bg-gray-light scrollboxdetayreport" style="height:600px;">';
 echo'<h5 class="simple-header"><i class="fa fa-list-alt" aria-hidden="true"></i>&nbsp;&nbsp;'.$bulkid.' Nolu Gönderimin Raporu</h5>';
    $html = '';
    if (!empty($gorevrapor)) {
        $html .= '<table id="" class="table table-bordered table-striped table-condensed table-responsive boo-table bg-blue">
                                      <thead>
                                            <tr>
                                                <th class="span1" >No</th>
                                                <th class="span3" >Numara<span class="column-sorter"></span></th>
                                                <th class="span2" >Durum<span class="column-sorter"></span></th>
                                                <th class="span2" >Operatör<span class="column-sorter"></span></th>
                                                <th class="span2" >Tarih<span class="column-sorter"></span></th>
                                                <th class="span2" >Hata<span class="column-sorter"></span></th>
                                            </tr>
                                        </thead>
                                       <tbody>';
        foreach ($gorevrapor as $key_item => $rapor_item) {
            foreach (explode(" ", $rapor_item) as $k => $item) {
                $sutun[$k] = $item;
            }
            $html .= '<tr>';
            $html .= '<td style="text-align:center;">'.($key_item + 1).'</td>';
            $html .= '<td>'.$sutun[0].'</td>';
            $html .= '<td>'.block_okulsis_sms_durum($sutun[1]).'</td>';
            $html .= '<td>'.block_okulsis_sms_operator($sutun[2]).'</td>';
            $html .= '<td>'.$sutun[4].'</td>';
            $html .= '<td>'.block_okulsis_sms_hata($sutun[6]).'</td>';
            $html .= '</tr>';
        }
        $html .= '</tbody>
               </table>';
       echo($html);
    }else{
        echo(okulsis_mesajyaz('warning','Rapora Ulaşılamadı!'));
    }
    echo '</div></div></section></div>';
}