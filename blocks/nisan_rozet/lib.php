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
 * Time: 17:25
 */
global $CFG;
require_once($CFG->dirroot.'/repository/lib.php');
require_once($CFG->dirroot.'/lib/filelib.php');
require_once "locallib.php";
function block_nisan_rozet_myprofile_navigation(\core_user\output\myprofile\tree $tree, $user, $iscurrentuser, $course) {
    global $CFG, $PAGE, $USER, $OUTPUT;
    $category = new core_user\output\myprofile\category('nisan', get_string('nisanmyprofile', 'block_nisan_rozet'), 'contact');
    $tree->add_category($category);
    $context = context_user::instance($user->id);
    if ($USER->id == $user->id || has_capability('block/nisan_rozet:othernisanview', $context)) {
        $content= block_nisan_rozet_profilenode($user->id);
        $localnode = $mybadges = new core_user\output\myprofile\node('nisan', 'kazanilan', null, null, null, $content);
        $tree->add_node($localnode);
        $icerik = block_nisan_rozet_profilenodewinnerbadge($user->id);
        $iceriknode = new core_user\output\myprofile\node('nisan','winner','Rozete Dönüşen Nişanlar',null,null,$icerik);
        $tree->add_node($iceriknode);
        $url = new moodle_url('/blocks/nisan_rozet/view.php', array('viewpage' => 4));
        $node = new core_user\output\myprofile\node('nisan','kriterler',get_string('sitekriter', 'block_nisan_rozet'),null,$url,null);
        $tree->add_node($node);
    }

}
function block_nisan_rozet_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options=array()) {
    global $DB;
    if ($context->contextlevel != CONTEXT_SYSTEM) {
        return false;
    }
    require_login();
    if ($filearea != 'attachment') {
        return false;
    }
    $itemid = (int)array_shift($args);
    if ($itemid == 0) {
        return false;
    }
    $fs = get_file_storage();
    $filename = array_pop($args);
    if (empty($args)) {
        $filepath = '/';
    } else {
        $filepath = '/'.implode('/', $args).'/';
    }
    $file = $fs->get_file($context->id, 'block_nisan_rozet', $filearea, $itemid, $filepath, $filename);
    if (!$file) {
        return false;
    }
    // finally send the file
   send_stored_file($file, 0, 0, true, $options); // download MUST be forced - security!
}


