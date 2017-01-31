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



defined('MOODLE_INTERNAL') || die();

$capabilities = array(
        'block/sms:myaddinstance' => array(
                'captype' => 'write',
                'contextlevel' => CONTEXT_SYSTEM,
                'archetypes' => array(
            'guest' => CAP_PREVENT,
			'user' => CAP_PREVENT,
            'student' => CAP_PREVENT,
            'teacher' => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW,
            'manager' => CAP_ALLOW,
            'coursecreator' => CAP_ALLOW
                )


        ),
 
    'block/sms:addinstance' => array(
        'riskbitmask' => RISK_SPAM | RISK_XSS,
 
        'captype' => 'write',
        'contextlevel' =>  CONTEXT_SYSTEM,
        'archetypes' => array(
                'coursecreator' => CAP_ALLOW,
            'manager' => CAP_ALLOW
            ),
 
        'clonepermissionsfrom' => 'moodle/site:manageblocks'
    ),

    'block/sms:viewpages' => array(

        'captype' => 'write',
        'contextlevel' =>  CONTEXT_SYSTEM,
            'archetypes' => array(
            'guest' => CAP_PREVENT,
			'user' => CAP_PREVENT,
            'student' => CAP_PREVENT,
            'teacher' => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW,
            'manager' => CAP_ALLOW,
            'coursecreator' => CAP_ALLOW
            
        )

    ),
	'block/sms:view' => array(

        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
            'archetypes' => array(
            'guest' => CAP_PREVENT,
			'user' => CAP_PREVENT,
            'student' => CAP_PREVENT,
            'teacher' => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW,
            'manager' => CAP_ALLOW,
            'coursecreator' => CAP_ALLOW
            
        )

    ),

    'block/sms:managepages' => array(

        'captype' => 'write',
        'contextlevel' =>  CONTEXT_SYSTEM,
            'archetypes' => array(
            'guest' => CAP_PREVENT,
			'user' => CAP_PREVENT,
            'student' => CAP_PREVENT,
            'teacher' => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW,
            'coursecreator' => CAP_ALLOW,
            'manager' => CAP_ALLOW
        )

    ),
        'block/sms:viewyonetim' => array(

        'captype' => 'write',
        'contextlevel' =>  CONTEXT_SYSTEM,
        'archetypes' => array(
                'guest' => CAP_PREVENT,
                'user' => CAP_PREVENT,
                'student' => CAP_PREVENT,
                'teacher' => CAP_PREVENT,
                'editingteacher' => CAP_PREVENT,
                'coursecreator' => CAP_PREVENT,
                'manager' => CAP_ALLOW
        )

        ),
        'block/sms:managemessage' => array(

                'captype' => 'write',
                'contextlevel' =>  CONTEXT_SYSTEM,
                'archetypes' => array(
                        'guest' => CAP_PREVENT,
                        'user' => CAP_PREVENT,
                        'student' => CAP_PREVENT,
                        'teacher' => CAP_PREVENT,
                        'editingteacher' => CAP_PREVENT,
                        'coursecreator' => CAP_PREVENT,
                        'manager' => CAP_ALLOW
                )

        ),
        'block/sms:managesetting' => array(

                'captype' => 'write',
                'contextlevel' =>  CONTEXT_SYSTEM,
                'archetypes' => array(
                        'guest' => CAP_PREVENT,
                        'user' => CAP_PREVENT,
                        'student' => CAP_PREVENT,
                        'teacher' => CAP_PREVENT,
                        'editingteacher' => CAP_PREVENT,
                        'coursecreator' => CAP_PREVENT,
                        'manager' => CAP_ALLOW
                )

        )
);