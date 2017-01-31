<?php
/**
 * Created by PhpStorm.
 * User: Çaglar
 * Date: 12.12.2016
 * Time: 19:11
 */
global $CFG,$DB,$USER;
require_once ('../../../config.php');
require_once "locallib.php";
$sql="SELECT b.id,b.userid,u.firstname,u.lastname,u.phone1,u.department,u.institution,b.tel
      FROM {block_okulsis_sms_basket} b
      LEFT JOIN {user} u ON u.id=b.userid
      WHERE b.ownerid=$USER->id";
$rs=$DB->get_records_sql($sql,array());
if($rs) {
    $html = ' <div class="alphabet-in">
       <div id="myContactWrapIn" class="scrollBox4 nav-list-wrap" style="height:650px;">
        <ul id="myContactIn" class="nav nav-contact list-bordered dotted thumb-small">
       ';
 
foreach ($rs as $log){
    if($log->userid != -1) {

        $html .= '<li><span class=" pull-left"><a class="deleterow" id="' . $log->id . '" href="javascript:void(0);"><br><i class="fa fa-trash" aria-hidden="true"></i>
</a></span>';
        $html .= '<a class="media" href="javascript:void(0);">';
        $html .= '<span class="media-thumb media-left img-shadow">
                 <img class="media-object thumb" src="' . $CFG->wwwroot . '/user/pix.php/' . $log->userid . '/f1.jpg">
                 </span>';
        $html .= '<div class="media-body">';
        $html .= '<span class="hidden">' . $log->firstname[0] . '</span>';
        $html .= '<h4 class="media-heading name">' . $log->firstname . ' ' . $log->lastname . '</h4>';
        $html .= '<span class="phone">';
        $html .= '<abbr title="Veli Telefonu">Tel:&nbsp;</abbr>' . $log->phone1 . '</span>';
        $html .= '</div>';
        $html .= '<span class="sinif">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>';
        $html .= '<span class="kurum">' . $log->institution . '</span>&nbsp; |&nbsp;&nbsp;';
        $html .= '<span class="sinif">' . $log->department . '</span>';
        $html .= '</a>';
        $html .= '</li>';
    }else{
        $html .= '<li><span class=" pull-left"><a class="deleterow" id="' . $log->id . '" href="javascript:void(0);"><br><i class="fa fa-trash" aria-hidden="true"></i>
</a></span>';
        $html .= '<a class="media" href="javascript:void(0);">';
        $html .= '<span class="media-thumb media-left img-shadow">
                 <img class="media-object thumb" src="">
                 </span>';
        $html .= '<div class="media-body">';
        $html .= '<span class="hidden">X</span>';
        $html .= '<h4 class="media-heading name">Kayıt Dışı</h4>';
        $html .= '<span class="phone">';
        $html .= '<abbr title="Veli Telefonu">Tel:&nbsp;</abbr>' . $log->tel . '</span>';
        $html .= '</div>';
        $html .= '<span class="sinif">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>';
        $html .= '<span class="kurum">Yok</span>&nbsp; |&nbsp;&nbsp;';
        $html .= '<span class="sinif">Yok</span>';
        $html .= '</a>';
        $html .= '</li>';
    }


             }
       $html .=' </ul>
  </div>
        <div id="myContactIn-nav" class="nav-alphabet alphabet-list"></div>
     </div>
 ';
  } else{
    $html = okulsis_mesajyaz('info','Liste Boş');
}
echo($html);

