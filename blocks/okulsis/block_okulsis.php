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
/**
 * Created by PhpStorm.
 * User: Çaglar
 * Date: 05.09.2016
 * Time: 17:26
 */
class block_okulsis extends block_base {
    public function init() {
        $this->title = 'Okul Yönetim Sistemi';
    }
    function get_required_javascript() {
        parent::get_required_javascript();

        $this->page->requires->jquery();
        $this->page->requires->jquery_plugin('ui');
        $this->page->requires->jquery_plugin('ui-css');
        $this->page->requires->jquery_plugin('datatables', 'block_okulsis');
        $this->page->requires->jquery_plugin('datatables_dt', 'block_okulsis');
        $this->page->requires->jquery_plugin('ion.rangeSlider', 'block_okulsis');
    }

    public function get_content() {
        
        if ($this->content !== null) {
            return $this->content;
        }
        $context = context_system::instance();
        $this->content = new stdClass;
        if (has_capability('block/okulsis:sendsms', $context)) {
            $this->content->text ='<i class="fa fa-mobile fa-2x pull-left" aria-hidden="true"></i>&nbsp;'.
            html_writer::link(new moodle_url('/blocks/okulsis/view.php', array('module'=>'sms','viewpage' => 'send')),'Sms Yönetimi').'<br>';
        }

            return $this->content;
        }
    public function specialization() {
        // blok başlığı
        if (empty($this->config->title)) {
            $this->title = 'Okul Yönetim Sistemi';
        } else {
            $this->title = $this->config->title;
        }

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
