<?php

defined('MOODLE_INTERNAL') || die();

$capabilities = array(
        'block/okulsis:myaddinstance' => array(
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

        'block/okulsis:addinstance' => array(
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
        'block/okulsis:sendsms' => array(
                'riskbitmask' => RISK_SPAM | RISK_XSS,
                'captype' => 'write',
                'contextlevel' =>  CONTEXT_SYSTEM,
                'archetypes' => array(
                        'guest' => CAP_PREVENT,
                        'user' => CAP_PREVENT,
                        'student' => CAP_PREVENT,
                        'teacher' => CAP_PREVENT,
                        'editingteacher' => CAP_PREVENT,
                        'manager' => CAP_ALLOW,
                        'coursecreator' => CAP_ALLOW
                )

        ),
        'block/okulsis:smssetting' => array(
                'riskbitmask' => RISK_SPAM | RISK_XSS,
                'captype' => 'write',
                'contextlevel' =>  CONTEXT_SYSTEM,
                'archetypes' => array(
                        'guest' => CAP_PREVENT,
                        'user' => CAP_PREVENT,
                        'student' => CAP_PREVENT,
                        'teacher' => CAP_PREVENT,
                        'editingteacher' => CAP_PREVENT,
                        'manager' => CAP_ALLOW,
                        'coursecreator' => CAP_PREVENT
                )

        )
       ,
        'block/okulsis:tanimlamalar' => array(
                'riskbitmask' => RISK_SPAM | RISK_XSS,
                'captype' => 'write',
                'contextlevel' =>  CONTEXT_SYSTEM,
                'archetypes' => array(
                        'guest' => CAP_PREVENT,
                        'user' => CAP_PREVENT,
                        'student' => CAP_PREVENT,
                        'teacher' => CAP_PREVENT,
                        'editingteacher' => CAP_PREVENT,
                        'manager' => CAP_ALLOW,
                        'coursecreator' => CAP_PREVENT
                )

        ) /*,
        'block/okulsis:viewogretmen' => array(
                'riskbitmask' => RISK_SPAM | RISK_XSS,
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
        'block/okulsis:ogretmennisanatama' => array(
                'riskbitmask' => RISK_SPAM | RISK_XSS,
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
        'block/okulsis:nisanduzenlesil' => array(
                'riskbitmask' => RISK_SPAM | RISK_XSS,
                'captype' => 'write',
                'contextlevel' =>  CONTEXT_SYSTEM,
                'archetypes' => array(
                        'guest' => CAP_PREVENT,
                        'user' => CAP_PREVENT,
                        'student' => CAP_PREVENT,
                        'teacher' => CAP_PREVENT,
                        'editingteacher' => CAP_PREVENT,
                        'manager' => CAP_ALLOW,
                        'coursecreator' => CAP_ALLOW
                )

        ),
        'block/okulsis:nisanyonetimbakma' => array(
                'riskbitmask' => RISK_SPAM | RISK_XSS,
                'captype' => 'write',
                'contextlevel' =>  CONTEXT_SYSTEM,
                'archetypes' => array(
                        'guest' => CAP_PREVENT,
                        'user' => CAP_PREVENT,
                        'student' => CAP_PREVENT,
                        'teacher' => CAP_PREVENT,
                        'editingteacher' => CAP_PREVENT,
                        'manager' => CAP_ALLOW,
                        'coursecreator' => CAP_ALLOW
                )

        ),'block/okulsis:nisanview' => array(
                'riskbitmask' => RISK_SPAM | RISK_XSS,
                'captype' => 'write',
                'contextlevel' =>  CONTEXT_SYSTEM,
                'archetypes' => array(
                        'guest' => CAP_ALLOW,
                        'user' => CAP_ALLOW,
                        'student' => CAP_ALLOW,
                        'teacher' => CAP_ALLOW,
                        'editingteacher' => CAP_ALLOW,
                        'manager' => CAP_ALLOW,
                        'coursecreator' => CAP_ALLOW
                )

        ),'block/okulsis:othernisanview' => array(
                'riskbitmask' => RISK_SPAM | RISK_XSS,
                'captype' => 'write',
                'contextlevel' =>  CONTEXT_USER,
                'archetypes' => array(
                        'guest' => CAP_PREVENT,
                        'user' => CAP_ALLOW,
                        'student' => CAP_ALLOW,
                        'teacher' => CAP_ALLOW,
                        'editingteacher' => CAP_ALLOW,
                        'manager' => CAP_ALLOW,
                        'coursecreator' => CAP_ALLOW
                )

        ),
        'block/okulsis:othernisanviewlist' => array(
                'riskbitmask' => RISK_SPAM | RISK_XSS,
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

        )*/
        
        

);