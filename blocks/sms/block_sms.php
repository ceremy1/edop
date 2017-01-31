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

class block_sms extends block_base {

    public function init() {
        $this->title = get_string('sms', 'block_sms');
    }

    public function get_content() {
        global $CFG, $USER, $COURSE;
        if ($this->content !== null) {
            return $this->content;
        }
        $context = context_system::instance();
        $this->content = new stdClass;
		if (has_capability('block/sms:view', $context)) {
            $this->content->text = html_writer::link(new moodle_url('/blocks/sms/view.php', array('viewpage' => '6')), get_string('admin_panel', 'block_sms')) . '<br>';
           // $this->content->text .= html_writer::link(new moodle_url('/blocks/sms/view.php', array('viewpage' => '2')), get_string('sms_send', 'block_sms')) . '<br>';
            $this->content->text .= html_writer::link(new moodle_url('/blocks/sms/view.php', array('viewpage' => '5')), get_string('mesaj_kaydet', 'block_sms')) . '<br>';
            $this->content->text .= html_writer::link(new moodle_url('/blocks/sms/view.php', array('viewpage' => '3')), get_string('sms_template', 'block_sms')) . '<br>';
            $this->content->text .= html_writer::link(new moodle_url('/blocks/sms/view.php', array('viewpage' => '4')), get_string('sms_rapor', 'block_sms')) . '<br>';
            $this->content->text .= html_writer::link(new moodle_url('/blocks/sms/view.php', array('viewpage' => '8')), get_string('sms_ayarlar', 'block_sms')) . '<br>';


        }
        return $this->content;
    }

    public function has_config() {
        return true;
    }

    public function applicable_formats() {
        return array('all' => true);
    }

    public function instance_allow_config() {
        return true;
    }

}
