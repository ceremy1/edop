<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

//TODO:acaba tebrik mesajlarındaki mesajlar ayarlara yazılsamı
defined('MOODLE_INTERNAL') || die;
if($ADMIN->fulltree) {
    
   $settings->add( new admin_setting_configtext('block_nisan_rozet_setimage','Nişan Resim Ebadı','*Nişan resimlerinin büyüklüğünü px cinsinden ayarlayabilirsiniz'
   ,'50px',PARAM_TEXT,null));
   $settings->add( new admin_setting_configcheckbox('block_nisan_rozet_notificationbar', 'Bilgi Barı Gösterilsin mi?','*Sayfa başında ki istatistikler gösterilsin mi', 1) );
   $settings->add( new admin_setting_configtext('block_nisan_rozet_setdbrecord','Son kaç işlem Listelensin','*Anasayfadaki Son işlemelerde kaç kayıt gösterileceğini ayarlayabilirsiniz'
           ,'30',PARAM_INT,null));
   $settings->add(new admin_setting_configselect('block_nisan_rozet_oldlog','Log Kayıtlarını Sil', '*Eski Log Kayıtlarını temizleme ', '2',
           array('6 Aydan Önce','9 Aydan Önce','1 yıldan Önce','2 Yıldan Önce')));
   
}