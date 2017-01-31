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

$settings = null;
defined('MOODLE_INTERNAL') || die;
if ($hassiteconfig) {

    $ADMIN->add('blocksettings', new admin_category('block_okulsis', 'Okul Yönetim Sistemi'));
    // SMS MODÜLÜ
    $temp = new admin_settingpage('block_okulsis_sms','SMS Modülü');
    //aç/kapa
    $name = 'block_okulsis_sms_onoff';
    $title = 'SMS servisi Aç/Kapat';
    $description = '*Bu ayar sms servisinizi aktif veya deaktif etmeye yarar.';
    $defvalue = 1;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $defvalue);
    $temp->add($setting);

    //bakiye
    $name = 'block_okulsis_sms_bakiye';
    $title = 'Bakiye Gösterilsin mi?';
    $description = '*Bu ayar  bakiyenin gösterilmesini ayarlar';
    $defvalue = 1;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $defvalue);
    $temp->add($setting);
    //username
    $name = 'block_okulsis_sms_username';
    $title = 'Kullanıcı Adı:';
    $description = '*Servis sağlayıcınızda ki kullanıcı adı';
    $defvalue = '';
    $setting = new admin_setting_configtext($name, $title, $description, $defvalue,PARAM_TEXT);
    $temp->add($setting);
    //password
    $name = 'block_okulsis_sms_password';
    $title =  'Şifre:';
    $description = '*Servis sağlayıcınızda ki kullanıcı şifresi';
    $defvalue = '';
    $setting = new admin_setting_configtext($name, $title, $description, $defvalue,PARAM_TEXT);
    $temp->add($setting);
    //servis seç
    $name = 'block_okulsis_sms_api';
    $title = 'SMS Servisiniz:';
    $description = '*Servis sağlayıcı seçiniz';
    $defvalue = 0;
    $setting = new admin_setting_configselect($name, $title, $description, $defvalue,array('NetGsm'));
    $temp->add($setting);
    //mesaj ön şablonu
    $name = 'block_okulsis_sms_ilksablon';
    $title = 'Mesaj Başı Şablonu:';
    $description = '*Mesajın giriş kısmı(seçenekler arasına | işareti koyunuz)';
    $defvalue = 'Sayın velimiz bugün yapılacak ödevlerimiz:|Devamsızlık bilgisi:';
    $setting = new admin_setting_configtextarea($name, $title, $description, $defvalue,PARAM_TEXT, '50', '5');
    $temp->add($setting);
    //mesaj sonu şablonu
    $name = 'block_okulsis_sms_sonsablon';
    $title = 'Mesaj sonu Şablonu:';
    $description = '*Mesajın Son kısmı(seçenekler arasına | işareti koyunuz)';
    $defvalue =  'Bilginize Bilişim Koleji|Saygılarımızla';
    $setting = new admin_setting_configtextarea($name, $title, $description, $defvalue,PARAM_TEXT, '50', '5');
    $temp->add($setting);

    $ADMIN->add('block_okulsis', $temp);







}