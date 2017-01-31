<?php
/**
 * Created by PhpStorm.
 * User: Çaglar
 * Date: 08.09.2016
 * Time: 01:57
 */
require_once(__DIR__. '/../../../config.php');
require_once("../locallib.php");
global $OUTPUT,$DB,$CFG;
$id     = required_param('id',PARAM_INT); //öğretmen id si
$filtre = required_param('filtre',PARAM_TEXT);
if($filtre == 'nisan'){
    if($id != -1) {
        $sql="SELECT DISTINCT yetkisi FROM {block_nisan_rozet_settings}  WHERE ogretmen_id=? AND yetki_tur=?";
        $tumnisanlar =$DB->get_records('block_nisan_rozet_nisan',null,null,'id,name');
        $rs=$DB->get_recordset_sql($sql,array($id,1),null,null);

        if($rs->valid()) {
            $html="";
            foreach ($rs as $log){
                $yetkiler[]=$log->yetkisi;
            }
            foreach ($tumnisanlar as $value) {
                if (!empty($value)) {

                    $html .= '<label class="checkbox"><input type="checkbox" name="nisanlar[]" value="' . $value->id . '"';
                    if (in_array($value->id, $yetkiler)) {
                        $html .= 'checked="checked"';
                    }
                    $html .= '>'.block_nisan_rozet_nisanresimal($value->id).'&nbsp; &nbsp;'. $value->name . '</label></br>';

                }
            }
            $html .='<input type="hidden" name="ogretmen_id" value="'.$id.'"/>';
        }else{
            $nisanlar = $DB->get_records_menu('block_nisan_rozet_nisan',array());
            $html = "";
            if(!empty($nisanlar)){
            foreach ($nisanlar as $key=>$value) {
                if (!empty($value)) {
                    $html .= '<label class="checkbox"><input type="checkbox" name="nisanlar[]" value="' . $key . '">'.block_nisan_rozet_nisanresimal($key).'&nbsp; &nbsp;' . $value . '</label></br>';
                }
            }
            $html .='<input type="hidden" name="ogretmen_id" value="'.$id.'"/>';
            }else{
                $html .= block_nisan_rozet_mesajyaz('danger','Seçilecek Nişan Bulunamadı',null);
            }
        }
        $rs->close();
        
        
    }else{
        $html ="";
    }
    echo $html;
}
