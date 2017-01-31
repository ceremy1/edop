<?php
/**
 * Created by PhpStorm.
 * User: Çaglar
 * Date: 02.10.2016
 * Time: 15:48
 */
global $CFG,$DB;
require_once(__DIR__. '/../../../config.php');
require_once ("$CFG->dirroot/blocks/nisan_rozet/locallib.php");
$context = context_system::instance();
if(has_capability('block/nisan_rozet:view',$context)) {
    echo '<div class="dashboard-wrapper">';
    echo '<div class="main-container">';
    echo '      <div class="row-fluid">
            <div class="span2">
              <div class="widget">
                <div class="widget-header">
                  <div class="title">
                    <span class="fs1" aria-hidden="true"><i class="fa fa-users" aria-hidden="true"></i></span> Toplam Öğrenci
                  </div>
                </div>
                <div class="widget-body">
                  <div class="current-statistics">
                    <div class="clients">
                      <h3>' . toplamogrenci() . '</h3>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="span2">
              <div class="widget">
                <div class="widget-header">
                  <div class="title">
                    <span class="fs1" aria-hidden="true" ><i class="fa fa-star-half-o" aria-hidden="true"></i>
</span>Nişanı Olanlar
                  </div>
                </div>
                <div class="widget-body">
                  <div class="current-statistics">
                    <div class="products">
                      <h3>' . nisantoplamogrenci() . '</h3>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="span2">
              <div class="widget">
                <div class="widget-header">
                  <div class="title">
                    <span class="fs1" aria-hidden="true"><i class="fa fa-star" aria-hidden="true"></i></span> Rozeti Olanlar
                  </div>
                </div>
                <div class="widget-body">
                  <div class="current-statistics">
                    <div class="sales">
                      <h3>' . rozettoplamogrenci() . '</h3>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="span2">
              <div class="widget">
                <div class="widget-header">
                  <div class="title">
                    <span class="fs1" aria-hidden="true"><i class="fa fa-bullhorn" aria-hidden="true"></i></span> Aktif Duyuru
                  </div>
                </div>
                <div class="widget-body">
                  <div class="current-statistics">
                    <div class="income">
                      <h3>' . aktifduyuru() . '</h3>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="span2">
              <div class="widget">
                <div class="widget-header">
                  <div class="title">
                    <span class="fs1" aria-hidden="true"><i class="fa fa-crosshairs" aria-hidden="true"></i>
</span> Aktif Kriter
                  </div>
                </div>
                <div class="widget-body">
                  <div class="current-statistics">
                    <div class="expenses">
                      <h3>' . aktifkriter() . '</h3>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="span2">
              <div class="widget">
                <div class="widget-header">
                  <div class="title">
                    <span class="fs1" aria-hidden="true"><i class="fa fa-cogs" aria-hidden="true"></i></span> Loglar
                  </div>
                </div>
                <div class="widget-body">
                  <div class="current-statistics">
                    <div class="signups">
                      <h3>' . tumlogs() . '</h3>
                    </div>
                  </div>
                </div>
              </div>
            </div>
           </div>';
    echo '</div>';
    echo '</div>';
}
function toplamogrenci(){
    global $DB;
    $sql="SELECT  COUNT(usr.id) AS toplam
        FROM {cohort} c
        INNER JOIN {cohort_members} cm ON c.id = cm.cohortid
        INNER JOIN  {user} usr ON cm.userid = usr.id
        WHERE  cm.cohortid
        IN (SELECT DISTINCT MIN(c.cohortid) FROM {cohort_members} c WHERE c.userid=usr.id)
        ";
    
    return $DB->get_field_sql($sql,array());

}
function nisantoplamogrenci() {
    global $DB;
    $sql = "SELECT COUNT(DISTINCT ogrid) AS toplam
FROM {block_nisan_rozet_atama} ";

    return $DB->get_field_sql($sql, array());

}
function rozettoplamogrenci() {
    global $DB;
    $sql = "SELECT COUNT(DISTINCT userid) AS toplam
FROM {badge_issued} ";

    return $DB->get_field_sql($sql, array());

}
function aktifduyuru() {
    global $DB;
    return $DB->count_records('block_nisan_rozet_duyuru',array('aktif'=>1));

}
function aktifkriter() {
    global $DB;
    return $DB->count_records('block_nisan_rozet_kriter',array('aktif'=>1));

}
function tumlogs() {
    global $DB;
    return $DB->count_records('block_nisan_rozet_log',array());

}