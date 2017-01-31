<?php
/**
 * Created by PhpStorm.
 * User: Çaglar
 * Date: 18.09.2016
 * Time: 23:31
 */
global $CFG,$DB;
require_once(__DIR__. '/../../../config.php');
require_once ("$CFG->dirroot/blocks/nisan_rozet/locallib.php");
$filtre = required_param('filtre',PARAM_TEXT);
$limit = $CFG->block_nisan_rozet_setdbrecord;
if ($filtre == 'site'){
    $sql ="SELECT id,tarih,content,
            CASE tur
            WHEN -10  THEN 'danger'
            WHEN 0  THEN 'danger'
            WHEN 1  THEN 'success'
            WHEN 2  THEN 'info'
            WHEN 3  THEN 'danger'
            WHEN 4  THEN 'danger'
            WHEN 5  THEN 'success'
            WHEN 6  THEN 'danger'
            WHEN 7  THEN 'info'
            WHEN 8  THEN 'success'
            WHEN 9  THEN 'info'
            WHEN 10  THEN 'success'
            WHEN 11  THEN 'info'
            WHEN 12  THEN 'danger'
            WHEN 13  THEN 'success'
            WHEN 14  THEN 'danger'
            WHEN 15  THEN 'success'
            END AS class
            FROM {block_nisan_rozet_log} 
            WHERE tur NOT IN (-5,-4,-3,-2,-1)
            ORDER BY tarih DESC"; 
}
else if($filtre == 'tumloglar'){
    $sql ="SELECT id,tarih,content,
            CASE tur
            WHEN -5  THEN 'danger'
            WHEN -4  THEN 'success'
            WHEN -3  THEN 'info'
            WHEN -2  THEN 'info'
            WHEN -1  THEN 'info'
            END AS class
            FROM {block_nisan_rozet_log} 
            WHERE tur  IN (-5,-4,-3,-2,-1)
            ORDER BY id DESC";
}
else if($filtre == 'calisma'){
    $sql ="SELECT id,tarih,content,
            CASE tur
            WHEN -2  THEN 'info'
            WHEN -1  THEN 'info'
            END AS class
            FROM {block_nisan_rozet_log} 
            WHERE tur  IN (-1,-2)
            ORDER BY id DESC";
}
else if($filtre == 'rozetkazanma') {
    $sql = "SELECT id,tarih,content,
            CASE tur
            WHEN -4  THEN 'success'
            END AS class
            FROM {block_nisan_rozet_log} 
            WHERE tur  IN (-4)
            ORDER BY id DESC";
}
else if($filtre == 'dagitimuyari') {
    $sql = "SELECT id,tarih,content,
            CASE tur
            WHEN -5  THEN 'danger'
            END AS class
            FROM {block_nisan_rozet_log} 
            WHERE tur  IN (-5)
            ORDER BY id DESC";
}

$rs = $DB->get_records_sql($sql,array(),0,$limit);
if($rs){
    $html = '<ul class="list-style-1 colored">';
    foreach ($rs as $log){
      $html .= '<li class="text-'.$log->class.'">('.date("d.m.Y H:i:s",$log->tarih).') # ' .$log->content.'</li>';
    }
    $html .= '</ul>';
    echo($html);
}else{
    echo(block_nisan_rozet_mesajyaz('warning','Henüz Sistemde Görüntülenecek Haraket Oluşmadı'));
}