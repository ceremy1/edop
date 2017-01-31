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

/* SMS_NetGsm Block
 * SMS_NetGsm block eklentisi tek yollu istenen kullanıcı rollerine sms göndermeye yarar
 * @package blocks
 * @author: Çağlar Mersinli
 * @date: 03.07.2016
*/

$plugin->version   = 2016102900;        // mevcut version (Date: YYYYMMDDXX)
$plugin->requires  = 2014051200;        // gerekli Moodle version 2.7.0 task api kullanıldığı için
$plugin->component = 'block_sms';
$plugin->release = 'v2.7-r2';
$plugin->maturity = MATURITY_BETA;       //henüz beta :)
$plugin->dependencies = array('block_nisan_rozet' => 2016102801);