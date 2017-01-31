<?php
require_once('../../config.php');
require_once('sms_form.php');
require_once("lib.php");
global $OUTPUT,$DB,$CFG;
$id     = required_param('id',PARAM_INT); //öğretmen id si
$filtre = required_param('filtre',PARAM_TEXT);
if($filtre == 'ders'){
    if($id != -1) {
        $sql="SELECT DISTINCT yetkisi FROM {block_sms_settings} WHERE ogretmen_id=? AND yetki_tur=?";
        $rs=$DB->get_recordset_sql($sql,array($id,1),null,null);
      if($rs->valid()) {
        $html="";
          $dersler = explode(",", $CFG->block_sms_api_ders);
          foreach ($rs as $log){
              $yetkiler[]=$log->yetkisi;
          }

              foreach ($dersler as $value) {
                  if (!empty($value)) {

                      $html .= '<input type="checkbox" name="dersler[]" value="' . $value . '"';
                      if(in_array($value,$yetkiler)){$html .= 'checked="checked"';}
                      $html .='>' . $value . '<br>';

                  }

          }
          $html .='<input type="hidden" name="ogretmen_id" value="'.$id.'"/>';

      }else{
          $dersler = explode(",", $CFG->block_sms_api_ders);
          $html = "";
          foreach ($dersler as $value) {
              if (!empty($value)) {
                  $html .= '<input type="checkbox" name="dersler[]" value="' . $value . '">' . $value . '<br>';
              }
          }
          $html .='<input type="hidden" name="ogretmen_id" value="'.$id.'"/>';
      }
        $rs->close();
      }

    else{
        $html ="";
    }
    echo $html;

}
if($filtre == 'header'){
    if($id != -1) {
        $sql="SELECT DISTINCT yetkisi FROM {block_sms_settings} WHERE ogretmen_id=? AND yetki_tur=?";
        $rs=$DB->get_recordset_sql($sql,array($id,2),null,null);
        if($rs->valid()) {
            $html="";
            $headers = getmsgheader();
            foreach ($rs as $log){
                $yetkiler[]=$log->yetkisi;
            }

            foreach ($headers as $value) {
                if (!empty($value)) {

                    $html .= '<input type="checkbox" name="headers[]" value="' . $value . '"';
                    if(in_array($value,$yetkiler)){$html .= 'checked="checked"';}
                    $html .='>' . $value . '<br>';

                }

            }
            $html .='<input type="hidden" name="ogretmen_id" value="'.$id.'"/>';

        }else{
            $headers = getmsgheader();
            $html = "";
            foreach ($headers as $value) {
                if (!empty($value)) {
                    $html .= '<input type="checkbox" name="headers[]" value="' . $value . '">' . $value . '<br>';
                }
            }
            $html .='<input type="hidden" name="ogretmen_id" value="'.$id.'"/>';
        }
        $rs->close();
    }
    else{
        $html ="";
    }
    echo $html;
}











