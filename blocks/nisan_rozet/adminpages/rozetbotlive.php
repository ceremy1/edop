<?php
/**
 * Created by PhpStorm.
 * User: Çaglar
 * Date: 21.09.2016
 * Time: 13:47
 */
global $CFG,$DB;
require_once(__DIR__. '/../../../config.php');
require_once ("$CFG->dirroot/blocks/nisan_rozet/locallib.php");
echo '<div class="widget">
                <div class="widget-header">
                  <div class="title">
                    <span class="fs1" aria-hidden="true" ><i class="fa fa-refresh fa-spin fa-2x fa-fw"></i></span>ROZETBOT SON İŞLEMLER (CANLI)
                  </div>
                  <div class="tools pull-right">
                    <select id="rozetbotselect">
                     <option value="tumloglar">TÜM İŞLEMLER</option>
                     <option value="calisma">ÇALIŞMA İŞLEMLERİ</option>
                     <option value="rozetkazanma">ROZET KAZANMA İŞLEMLERİ</option>
                     <option value="dagitimuyari"> DAĞITMA UYARILARI</option>
                    </select>
                  </div>
                </div>
                <div class="widget-body-live">
               <div id="liverozetbot"></div>
                 
                </div>
              </div>';