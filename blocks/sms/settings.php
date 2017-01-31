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


defined('MOODLE_INTERNAL') || die;
if($ADMIN->fulltree) {
    
   $settings->add( new admin_setting_configcheckbox('block_sms_api_onoff', get_string('onoff', 'block_sms'),  get_string('onofftext', 'block_sms'), 1) );
   $settings->add( new admin_setting_configcheckbox('block_sms_api_bakiye', 'Bakiye Gösterilsin mi?',  '*Bu ayar rapor kısmında bakiyenin gösterilmesini ayarlar', 1) );
   
   
    $settings->add(new admin_setting_configtext(get_string('block_sms_api_username', 'block_sms'),
                                                        'Kullanıcı Adı:',
                                                        '*Servis sağlayıcınızda ki kullanıcı adı',
                                                        '', PARAM_TEXT));
    $settings->add(new admin_setting_configtext(get_string('block_sms_api_password', 'block_sms'),
                                                        'Şifre:',
                                                        '*Servis sağlayıcınızda ki şifre',
                                                        '', PARAM_TEXT));
	$settings->add(new admin_setting_configselect('block_sms_api_method','Gönderim Methodu', '*Gönderim Methodu seçiniz', '0', array('XML')));
    $settings->add(new admin_setting_configselect('block_sms_api_oldmessage','Mesaj Kayıtlarını Sil', '*Eski Mesaj Kayıtlarını temizleme ', '3',
            array('1 Aydan Önce','3 Aydan Önce','6 Aydan Önce','1 Yıldan Önce','Hiçbirzaman')));
	$settings->add(new admin_setting_configtext('block_sms_api_time',
                                                        'Süre(sn):',
                                                        '*Rapor alırken beklemesi gereken süre',
                                                        '3', PARAM_TEXT));

    $settings->add(new admin_setting_configselect('block_sms_api','SMS Servisiniz', 'Servis sağlayıcı seçiniz', '', array('NetGsm')));
    $settings ->add(new admin_setting_configtextarea('block_sms_api_ders','Dersler:'
            ,'*Dersleri belirleyin(seçenekler arasına , işareti koyunuz)'
            ,'GENEL,MATEMATİK,TÜRKÇE,FEN BİLGİSİ,SOSYAL BİLGİLER,HAYAT BİLGİSİ,İNGİLİZCE,DİN KÜLTÜRÜ,VATANDAŞLIK,RESİM,BİLGİSAYAR'
            ,PARAM_TEXT, '50', '5'));
    $settings ->add(new admin_setting_configtextarea('block_sms_api_ilksablon','Mesaj Başı Şablonu:','*Mesajın giriş kısmı(seçenekler arasına | işareti koyunuz)',
                                                    'Sayın velimiz bugün yapılacak ödevlerimiz:.|Devamsızlık bilgisi:.|'
                                                      ,PARAM_TEXT, '50', '5'));
    $settings ->add(new admin_setting_configtextarea('block_sms_api_sonsablon','Mesaj sonu Şablonu:',
            '*Mesajın Son kısmı(seçenekler arasına | işareti koyunuz)',
            'Bilginize Bilişim Koleji|Saygılarımızla|'
            ,PARAM_TEXT, '50', '5'));
}