<?php
require_once('../../config.php');
require_once('sms_form.php');
require_once("lib.php");
global $OUTPUT,$DB,$CFG;
$date     = optional_param('date',null, PARAM_INT);
$gonderen = optional_param('gonderen',-1, PARAM_INT);
$filtre   =   required_param('filtre',PARAM_TEXT);
$ilkdate     = optional_param('ilkdate',null, PARAM_INT);
$sondate     = optional_param('sondate',null, PARAM_INT);
if($filtre=='yonetim') {
    if ($gonderen != -1) {
        $sql = "SELECT *,GROUP_CONCAT(ad,' ',soyad,'(',tel,')') AS content,GROUP_CONCAT(tel) AS alltel,GROUP_CONCAT(ogrenci_id) AS allogrenci_id ,GROUP_CONCAT(id) AS allid
FROM {block_sms_kaydet}
WHERE gonderen_id=$gonderen AND tarih=$date GROUP BY mesaj";
    } else {
        $sql = "SELECT *,GROUP_CONCAT(ad,' ',soyad,'(',tel,')') AS content,GROUP_CONCAT(tel) AS alltel,GROUP_CONCAT(ogrenci_id) AS allogrenci_id ,GROUP_CONCAT(id) AS allid
FROM {block_sms_kaydet}
WHERE  tarih=$date GROUP BY mesaj";
    }

    $count = $DB->record_exists_sql($sql, array($params = null));
    $table = new html_table();
    $table->attributes = array("name" => "userlist");
    $table->attributes = array("id" => "userlist");
    $table->attributes = array("class" => "table table-striped table-bordered table-condensed");
    if ($count >= 1) {

        $table->data = array();
        $table->size = array('2%', '2%', '5%', '4%', '4%', '20%', '60%', '3%');
        $table->align = array('left', 'left', 'left', 'left', 'left', 'left', 'left', 'left');
        $table->head = array(
                "Seç", get_string('serial_no', 'block_sms'), "Gönderen", "Ders", "Sınıf", "Mesaj", "Öğrenciler", "Tarih");

        $rs = $DB->get_recordset_sql($sql, array(), null, null);
        $i = 0;
        foreach ($rs as $key => $log) {
            /*foreach (explode(',', $log->allid) as $index => $value) {
                $allid[$index] = $value;
            }*/

            $row = array();
            $row[] = '<a title="Düzenle" id="btn_edit" href="' . $CFG->wwwroot . '/blocks/sms/view.php?viewpage=7&tarih=' .
                    $log->tarih . '&edit=edit&mesaj=' . $log->mesaj . '&allid=' . $log->allid . '" class="btn btn-success"><i class="fa fa-pencil" aria-hidden="true"></i></a>
        <a title="Sil" href="' . $CFG->wwwroot . '/blocks/sms/view.php?viewpage=7&rem=remove&allid=' . $log->allid . '" class="btn btn-danger"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
        ';
            $row[] = ++$i . '<input id="ids" type="hidden" value="' . $log->allid . '"/>';
            $row[] = $log->gonderen_ad . " " . $log->gonderen_soyad;
            $row[] = $log->ders;
            $row[] = $log->sinif;
            $row[] = $log->mesaj;
            $row[] = $log->content;
            $row[] = date('d/m/Y', $log->tarih);
            $table->data[] = $row;

        }
        $rs->close();
    } else {
        $row = array();
        $row[] =
                "<div id='load-users' style='border: 1px solid;margin: 10px 0px;padding:15px 10px 15px 50px;background-repeat: no-repeat;background-position: 10px center;color: #00529B;background-image: url(" .
                'pic/info.png' . "); background-color: #BDE5F8;border-color: #3b8eb5;'>Kayıt Bulunamadı!</div>";
        $table->data[] = $row;
    }
    echo html_writer::table($table);
}
else if ($filtre == 'netgsm'){
 $sql="SELECT *,
CASE gonderildimi
WHEN 0 THEN 'Gönderilmedi'
WHEN 1 THEN 'Gönderildi'
END AS durum
FROM {block_sms_kaydet} WHERE tarih BETWEEN ? AND ?";
    $table = new html_table();
    $table->attributes = array("name" => "userlist");
    $table->attributes = array("id" => "userlist");
    $table->attributes = array("class" => "table table-striped table-bordered table-condensed");
    $rs = $DB->get_recordset_sql($sql, array($ilkdate,$sondate), null, null);
    if($rs->valid()){
        $table->data = array();
        $table->size = array('2%', '20%', '20%', '43%', '10%', '5%');
        $table->align = array('left', 'left', 'left', 'left', 'left', 'left');
        $table->head = array(get_string('serial_no', 'block_sms'), "Mesajı Yazan", "Kime", "Mesaj","Tel","Durum");
        $i = 0;
        foreach ($rs as  $log) {
            $row = array();
            $row[] = ++$i;
            $row[] = $log->gonderen_ad.' '.$log->gonderen_soyad;
            $row[] = $log->ad.' '.$log->soyad;
            $row[] = $log->mesaj;
            $row[] = $log->tel;
            if ($log->durum =='Gönderildi'){
                $row[] ='<span class="label label-success">'.$log->durum.'</span>';
            }else{
                $row[] ='<span class="label label-important">'.$log->durum.'</span>';
            }

            $table->data[] = $row;
        }
        
        
    }else{
        $row = array();
        $row[] =
                "<div id='load-users' style='border: 1px solid;margin: 10px 0px;padding:15px 10px 15px 50px;background-repeat: no-repeat;background-position: 10px center;color: #00529B;background-image: url(" .
                'pic/info.png' . "); background-color: #BDE5F8;border-color: #3b8eb5;'>Kayıt Bulunamadı Lütfen Tarihi Küçükten Büyüğe Yazdığınızdan Emin Olun!</div>";
        $table->data[] = $row; 
        
    }
$rs->close();
    echo html_writer::table($table);
}
