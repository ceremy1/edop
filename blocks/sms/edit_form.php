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

class block_sms_edit_form extends block_edit_form {

    protected function specific_definition($mform) {
        
        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));
       
        $mform->addElement('textarea', 'config_text', 'Embeded Code');
        $mform->setDefault('config_text', 'default value');
        $mform->setType('config_text', PARAM_TEXT);
        $mform->addElement('text', 'config_title', get_string('blocktitle', 'block_sms'));
        $mform->setDefault('config_title', 'default value');
        $mform->setType('config_title', PARAM_TEXT);


    }
    
    

}