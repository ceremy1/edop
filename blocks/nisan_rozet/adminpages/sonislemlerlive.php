<?php
/**
 * Created by PhpStorm.
 * User: Çaglar
 * Date: 18.09.2016
 * Time: 23:15
 */
global $CFG,$DB;
require_once(__DIR__. '/../../../config.php');
require_once ("$CFG->dirroot/blocks/nisan_rozet/locallib.php");
          echo '<div class="widget">
                <div class="widget-header">
                  <div class="title">
                    <span class="fs1" aria-hidden="true" ><i class="fa fa-spinner fa-spin fa-2x fa-fw"></i></span>SİSTEMDEKİ SON İŞLEMLER (CANLI)
                  </div>
                </div>
                <div class="widget-body-live">
               <div id="livecontent"></div>
                 
                </div>
              </div>';