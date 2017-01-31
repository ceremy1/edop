<?php
/**
 * Created by PhpStorm.
 * User: Çaglar
 * Date: 04.12.2016
 * Time: 20:02
 */
define('AJAX_SCRIPT', true);

global $CFG;
require_once ('../../../config.php');
require_once (__DIR__."/locallib.php");
$context = context_system::instance();
require_capability('block/okulsis:sendsms',$context);
$filtre = required_param('filtre',PARAM_TEXT);
switch ($filtre){
    case "bolum":
        $kurum =required_param('kurum',PARAM_TEXT);
        if($kurum != '-1'){
            if(is_siteadmin()){
                $sql="SELECT DISTINCT department
                      FROM {user} WHERE institution='$kurum'
                      ORDER BY department";
            }else{
                $sql="SELECT DISTINCT bolum AS department FROM {block_okulsis_sms_setting} 
                      WHERE kurum='$kurum' AND ogretmen_id =$USER->id AND yetki_tur=1
                      ORDER BY department";
            }

            echo(bolumsec($sql));
        }else{
           echo '';
        }
        break;
    case "sinif":
        $sinifdata = required_param('sinifdata',PARAM_TEXT);
        $kurum =     required_param('kurum',PARAM_TEXT);
        if($sinifdata == '-2'){
            $sql = "SELECT DISTINCT id,department,phone1 FROM {user} WHERE department='' AND institution = '$kurum' AND suspended = 0 AND deleted = 0";
        }else if ($sinifdata == '-3'){
            $sql = "SELECT DISTINCT id,department,phone1 FROM {user} WHERE  institution = '$kurum' AND suspended = 0 AND deleted = 0";
        }else{
            $sql = "SELECT DISTINCT id,department,phone1 FROM {user} WHERE department='$sinifdata' AND institution = '$kurum' AND suspended = 0 AND deleted = 0";

        }
        echo(tabloyaz($sql,'filtrelistele'));
        break;
    case "altbolum":
        $courseid=required_param('courseid',PARAM_INT);
        if($courseid != -1){
            $sql="SELECT id,name FROM {course_sections} WHERE course=$courseid";
            echo(altbolumsec($sql));
        }else{
            echo '';
        }
        break;
    case "sinav":
        $bolumid = required_param('bolumid',PARAM_INT);
        $sql ="SELECT q.id AS id ,q.name As name
    FROM {course_modules} cm
    JOIN {modules} m ON m.id= cm.module
    LEFT JOIN {quiz} q ON q.id = cm.instance
    WHERE m.name = 'quiz' AND cm.section=$bolumid";
        echo(sinavsec($sql));
        break;
    case "gelismisfiltreleme":
        $kursid = optional_param('kursid',null,PARAM_INT);
        $altbolum = optional_param('altbolum',null,PARAM_INT);
        $sinavid=optional_param('sinavid',null,PARAM_INT);
        $rdnfiltre =optional_param('rdnfiltre',null,PARAM_INT);
        $from = optional_param('from',null,PARAM_INT);
        $to = optional_param('to',null,PARAM_INT);
        echo(okulsis_gelismisfitreleme($kursid,$altbolum,$sinavid,$rdnfiltre,$from,$to));
        break;
    case "bolumsetting":
        $kurum =required_param('kurum',PARAM_TEXT);
        $ogretmen_id =required_param('ogretmen_id',PARAM_INT);
        if($kurum != '-1'){
           echo(bolumsecsetting($kurum,$ogretmen_id));
        }else{
            echo '';
        }
        break;
    
}


