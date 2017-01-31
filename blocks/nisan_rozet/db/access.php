<?php
/**
 * Created by PhpStorm.
 * User: Çaglar
 * Date: 05.09.2016
 * Time: 17:21
 */
defined('MOODLE_INTERNAL') || die();

$capabilities = array(
        'block/nisan_rozet:myaddinstance' => array(
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

        'block/nisan_rozet:addinstance' => array(
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
        'block/nisan_rozet:view' => array(
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
        'block/nisan_rozet:managenisan' => array(
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

        ),
        'block/nisan_rozet:manageduyuru' => array(
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

        ),
        'block/nisan_rozet:viewogretmen' => array(
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
        'block/nisan_rozet:ogretmennisanatama' => array(
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
        'block/nisan_rozet:nisanduzenlesil' => array(
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
        'block/nisan_rozet:nisanyonetimbakma' => array(
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

        ),'block/nisan_rozet:nisanview' => array(
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

        ),'block/nisan_rozet:othernisanview' => array(
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
        'block/nisan_rozet:othernisanviewlist' => array(
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

        )
        
        

);