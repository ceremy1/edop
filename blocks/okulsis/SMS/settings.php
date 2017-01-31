<?php
/**
 * Created by PhpStorm.
 * User: SUAL
 * Date: 29.12.2016
 * Time: 11:38
 */
global $CFG,$DB;
require_once (__DIR__."/locallib.php");
$context = context_system::instance();
require_capability('block/okulsis:smssetting',$context);
echo '<div class="row-fluid page-head">
                    <h2 class="page-title heading-icon"  aria-hidden="true"><i class="fa fa-phone-square" aria-hidden="true"></i>
     SMS AYARLARI <small>Kısa Mesaj Ayar Ekranı</small></h2>
               </div></br>
               ';
echo'<div id="page-content" class="page-content "><section> ';
echo'<div class="row-fluid">
<div class="span12 well well-nice bg-blue-light">
<h5 class="simple-header"><i class="fa fa-cog" aria-hidden="true"></i>
&nbsp;&nbsp;Öğretmenlere SMS gönderme yetkisi verme <img id ="loadingyetki" class="hidden pull-right" src="'.$CFG->wwwroot.'/blocks/okulsis/pic/squares.svg"></h5>
<div class="row-fluid">
<div class="span2">
'.okulsis_ogretmensec().'
</div>
<div class="span3">
'.kurumsec().'
</div>
<div class="span2">
<div id="bolumdoldur"></div>
</div>
</div>

</div></div>';
echo'</section></div>';