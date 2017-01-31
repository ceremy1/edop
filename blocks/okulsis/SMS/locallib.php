<?php
/**
 * Created by PhpStorm.
 * User: SUAL
 * Date: 08.11.2016
 * Time: 14:25
 */
require_once(__DIR__ . "/../lib.php");
require_once(__DIR__ . "/../locallib.php");
//select html generator

/**
 *
 */
function sepetbosalt() {
    global $DB;
    $DB->delete_records('block_okulsis_sms_basket', array());

}

/**
 * @return string
 */
function rolsec() {
    global $DB;
    $rs = $DB->get_records('role', array(), null, 'id,shortname,archetype', null, null);
    $html = ' <select id="rolsec" class="span6" >
        <option value="-1">Rol Seçiniz:</option>';
    if ($rs) {
        foreach ($rs as $log) {
            $html .= '<option value="' . $log->archetype . '">' . $log->shortname . '</option>';
        }
    } else {
        $html .= '<option value="-2">Rol Bulunamadı</option>';
    }
    $html .= '</select>';
    return $html;
}

/**
 * @return string
 */
function kurumsec() {
    global $DB,$USER;
    if(is_siteadmin()){
        $sql = "SELECT DISTINCT institution FROM {user}";
    }else{
        $sql = "SELECT DISTINCT kurum AS institution
                FROM {block_okulsis_sms_setting} WHERE ogretmen_id = $USER->id AND yetki_tur=1";
    }

    $rs = $DB->get_records_sql($sql, array());
    $html = ' <select id="kurumsec" class="span6" >
        <option value="-1">Kurum Seçiniz:</option>';
    if ($rs) {
        foreach ($rs as $log) {
            if (!empty($log->institution)) {
                $html .= '<option value="' . $log->institution . '">' . $log->institution . '</option>';
            }

        }
    } else {
        $html .= '<option value="-2">Yetkili Olduğunuz Kurum Bulunamadı!</option>';
    }
    $html .= '</select>';
    return $html;
}

/**
 * @param string $sql
 * @return string
 */
function bolumsec($sql) {
    global $DB;

    $rs = $DB->get_records_sql($sql, array());
    $html = ' <select id="bolumsec" class="span6" >
             <option value="-1">Bölüm Seçiniz:</option>';
    if(is_siteadmin()){
        $html .= '<option value="-3">Tüm Bölümler</option>';
    }

    if ($rs) {
        foreach ($rs as $log) {
            if (empty($log->department)) {
                $html .= '<option value="-2">Bölüm Bilgisi Olmayanlar</option>';
            } else {
                $html .= '<option value="' . $log->department . '">' . $log->department . '</option>';

            }
        }
    } else {
        $html .= '<option value="-2">Bölüm Bulunamadı!</option>';
    }
    $html .= '</select>';
    return $html;
}

function bolumsecsetting($kurum, $ogretmen_id) {
    global $DB;
    $sql = "SELECT DISTINCT u.department AS bolum,u.institution,s.ogretmen_id 
          FROM {user} u
          LEFT JOIN {block_okulsis_sms_setting} s ON s.bolum=u.department AND s.kurum = u.institution AND s.yetki_tur=1
          WHERE institution=? ";
    $sql1 = "SELECT DISTINCT u.department AS bolum,u.institution,s.ogretmen_id 
          FROM {user} u
          LEFT JOIN {block_okulsis_sms_setting} s ON s.bolum=u.department AND s.kurum = u.institution AND s.yetki_tur=1
          WHERE institution=? AND ogretmen_id=? AND u.department=? ";
    $rs = $DB->get_records_sql($sql, array($kurum));
    if ($rs) {
        $html = '<ul class="unstyled">';
        foreach ($rs as $log) {
            if (!empty($log->bolum)) {
                $html .= '<li><input type="checkbox" name="siniflar[]" style="margin-top:0px;" value="' . $log->bolum . '"';
                if ($DB->record_exists_sql($sql1, array($kurum, $ogretmen_id, $log->bolum))) {
                    $html .= 'checked="checked"';
                }
                $html .= ' >' . $log->bolum . '</li>';
            }
        }
        $html .= '</ul>';
    } else {
        $html = okulsis_mesajyaz('warning', 'Bu Kuruma ait Sınıf Bilgisi Bulunamadı!');
    }
    return $html;
}

/**
 * @return string
 */
function kurssec() {
    global $DB;
    $rs = $DB->get_records_sql('SELECT id,fullname FROM {course}', array());
    $html = ' <select id="kurssec"  style="width:100%">
             <option value="-1">Kurs Seçiniz:</option>';
    if ($rs) {
        foreach ($rs as $log) {
            $html .= '<option value="' . $log->id . '">' . $log->fullname . '</option>';
        }
    } else {
        $html .= '<option value="-2">Kurs Bulunamadı!</option>';
    }
    $html .= '</select>';
    return $html;

}

/**
 * @param string $sql
 * @return string $html
 */
function altbolumsec($sql) {
    global $DB;
    $rs = $DB->get_records_sql($sql, array());
    $html = ' <select id="altbolumsec" style="width:100%">
             <option value="-1">Alt Bölüm Seçiniz:</option>';
    if ($rs) {
        foreach ($rs as $log) {
            if (!empty($log->name)) {
                $html .= '<option value="' . $log->id . '">' . $log->name . '</option>';
            }
        }
    } else {
        $html .= '<option value="-2">Bölüm Bulunamadı!</option>';
    }
    $html .= '</select>';
    return $html;
}

/**
 * @param $sql
 * @return string
 */
function sinavsec($sql) {
    global $DB;
    $rs = $DB->get_records_sql($sql, array());
    $html = ' <select id="sinavsec"   style="width:100%">
             <option value="-1">Sınav Seçiniz:</option>';
    if ($rs) {
        foreach ($rs as $log) {
            if (!empty($log->name)) {
                $html .= '<option value="' . $log->id . '">' . $log->name . '</option>';
            }
        }
    } else {
        $html .= '<option value="-2">Sınav Bulunamadı!</option>';
    }
    $html .= '</select>';
    return $html;

}

/**
 * @param int $kursid
 * @param int $altbolum
 * @param int $sinavid
 * @param string $rdnfiltre
 * @param int $from
 * @param int $to
 * @return int|string
 * @throws coding_exception
 */
function okulsis_gelismisfitreleme($kursid, $altbolum, $sinavid, $rdnfiltre, $from, $to) {
    global $USER;
    switch ($rdnfiltre) {
        case 1:
            if ($kursid > 0 and $sinavid > 0) {
                if(is_siteadmin()){
                    $sql = "SELECT DISTINCT
   user2.id ,
   user2.phone1,
   user2.department
  FROM {user_enrolments} AS ue
  JOIN  {enrol} AS e ON e.id = ue.enrolid
  JOIN  {course} AS c ON c.id = e.courseid
  JOIN  {user} AS user2 ON user2 .id = ue.userid
  WHERE c.id=$kursid AND user2.deleted=0 AND user2.suspended=0 AND e.status=0  
  AND ue.userid
  NOT IN (SELECT qa.userid FROM  {quiz_attempts} AS qa
  JOIN  {quiz} AS q ON qa.quiz = q.id
  JOIN  {course} AS c ON q.course = c.id
  WHERE c.id = $kursid AND q.id=$sinavid AND qa.state='finished')
  AND ue.userid
  IN (SELECT DISTINCT u.id 
  FROM {user} u
  JOIN {user_enrolments} ue ON ue.userid = u.id
  JOIN {enrol} e ON e.id = ue.enrolid
  JOIN {role_assignments} ra ON ra.userid = u.id
  JOIN {context} ct ON ct.id = ra.contextid AND ct.contextlevel = 50
  JOIN {course} c ON c.id = ct.instanceid AND e.courseid = c.id
  JOIN {role} r ON r.id = ra.roleid AND r.archetype = 'student'
  WHERE e.status = 0 AND u.suspended = 0 AND u.deleted = 0
  AND (ue.timeend = 0 OR ue.timeend > NOW()) AND ue.status = 0
  AND c.id=$kursid)
  ORDER BY user2.department
  ";
                }else{
                    $sql = "SELECT DISTINCT
   user2.id ,
   user2.phone1,
   user2.department
  FROM {user_enrolments} AS ue
  JOIN  {enrol} AS e ON e.id = ue.enrolid
  JOIN  {course} AS c ON c.id = e.courseid
  JOIN  {user} AS user2 ON user2 .id = ue.userid
  WHERE c.id=$kursid AND user2.deleted=0 AND user2.suspended=0 AND e.status=0  
  AND ue.userid
  NOT IN (SELECT qa.userid FROM  {quiz_attempts} AS qa
  JOIN  {quiz} AS q ON qa.quiz = q.id
  JOIN  {course} AS c ON q.course = c.id
  WHERE c.id = $kursid AND q.id=$sinavid AND qa.state='finished')
  AND ue.userid
  IN (SELECT DISTINCT u.id 
  FROM {user} u
  JOIN {user_enrolments} ue ON ue.userid = u.id
  JOIN {enrol} e ON e.id = ue.enrolid
  JOIN {role_assignments} ra ON ra.userid = u.id
  JOIN {context} ct ON ct.id = ra.contextid AND ct.contextlevel = 50
  JOIN {course} c ON c.id = ct.instanceid AND e.courseid = c.id
  JOIN {role} r ON r.id = ra.roleid AND r.archetype = 'student'
  WHERE e.status = 0 AND u.suspended = 0 AND u.deleted = 0
  AND (ue.timeend = 0 OR ue.timeend > NOW()) AND ue.status = 0
  AND c.id=$kursid)
  AND user2.department
  IN(SELECT DISTINCT bolum FROM {block_okulsis_sms_setting} WHERE ogretmen_id = $USER->id AND yetki_tur=1)
  ORDER BY user2.department
  ";
                }

                return tabloyaz($sql, 'filtrelistele');
            } else {
                return 1;
            }

            break;
        case 2:
            if ($kursid > 0 and $sinavid > 0) {
                if(is_siteadmin()){
                    $sql = "SELECT DISTINCT
   user2.id ,
   user2.department,
   user2.phone1,
   g.grade AS puan
  FROM {user_enrolments} AS ue
  JOIN  {enrol} AS e ON e.id = ue.enrolid
  JOIN  {course} AS c ON c.id = e.courseid
  JOIN  {user} AS user2 ON user2 .id = ue.userid
  JOIN {quiz_grades} g ON g.userid=user2.id
  JOIN  {quiz} AS q ON g.quiz = $sinavid
  WHERE c.id=$kursid AND user2.deleted=0 AND user2.suspended=0 AND e.status=0  AND (g.grade BETWEEN $from AND $to )
  AND user2.id
  IN (SELECT DISTINCT u.id 
  FROM {user} u
  JOIN {user_enrolments} ue ON ue.userid = u.id
  JOIN {enrol} e ON e.id = ue.enrolid
  JOIN {role_assignments} ra ON ra.userid = u.id
  JOIN {context} ct ON ct.id = ra.contextid AND ct.contextlevel = 50
  JOIN {course} c ON c.id = ct.instanceid AND e.courseid = c.id
  JOIN {role} r ON r.id = ra.roleid AND r.archetype = 'student'
  WHERE e.status = 0 AND u.suspended = 0 AND u.deleted = 0
  AND (ue.timeend = 0 OR ue.timeend > NOW()) AND ue.status = 0
  AND c.id=$kursid)
  ORDER BY user2.department";
                }else{
                    $sql = "SELECT DISTINCT
   user2.id ,
   user2.department,
   user2.phone1,
   g.grade AS puan
  FROM {user_enrolments} AS ue
  JOIN  {enrol} AS e ON e.id = ue.enrolid
  JOIN  {course} AS c ON c.id = e.courseid
  JOIN  {user} AS user2 ON user2 .id = ue.userid
  JOIN {quiz_grades} g ON g.userid=user2.id
  JOIN  {quiz} AS q ON g.quiz = $sinavid
  WHERE c.id=$kursid AND user2.deleted=0 AND user2.suspended=0 AND e.status=0  AND (g.grade BETWEEN $from AND $to )
  AND user2.id
  IN (SELECT DISTINCT u.id 
  FROM {user} u
  JOIN {user_enrolments} ue ON ue.userid = u.id
  JOIN {enrol} e ON e.id = ue.enrolid
  JOIN {role_assignments} ra ON ra.userid = u.id
  JOIN {context} ct ON ct.id = ra.contextid AND ct.contextlevel = 50
  JOIN {course} c ON c.id = ct.instanceid AND e.courseid = c.id
  JOIN {role} r ON r.id = ra.roleid AND r.archetype = 'student'
  WHERE e.status = 0 AND u.suspended = 0 AND u.deleted = 0
  AND (ue.timeend = 0 OR ue.timeend > NOW()) AND ue.status = 0
  AND c.id=$kursid)
  AND user2.department
  IN(SELECT DISTINCT bolum FROM {block_okulsis_sms_setting} WHERE ogretmen_id = $USER->id AND yetki_tur=1)
  ORDER BY user2.department";
                }

                return ortalamatabloyaz($sql, 'filtrelistele');
            } else {
                return 1;
            }
            break;
        case 3:
            if ($kursid > 0 and $altbolum > 0) {
                global $DB;
                $sql1 = "SELECT COUNT(q.id) AS id 
    FROM {course_modules} cm
    JOIN {modules} m ON m.id= cm.module
    LEFT JOIN {quiz} q ON q.id = cm.instance
    WHERE m.name = 'quiz' AND cm.section=?";
                $quizsayisi = $DB->count_records_sql($sql1, array($altbolum));
                if(is_siteadmin()){
                    $sql = "
SELECT DISTINCT
  user2.id ,
  user2.department,
  user2.phone1,
 (CASE WHEN ISNULL(SUM(qg.grade)) THEN 0 ELSE SUM(qg.grade)/$quizsayisi END) AS puan
  FROM {user_enrolments} AS ue
  JOIN  {enrol} AS e ON e.id = ue.enrolid
  JOIN  {course} AS c ON c.id = e.courseid
  JOIN {course_modules} AS cm ON cm.course=c.id
  JOIN  {user} AS user2 ON user2.id = ue.userid
  JOIN {modules} AS m ON m.id=cm.module
  JOIN {quiz} AS q ON q.course = c.id AND q.id=cm.instance
  LEFT JOIN  {quiz_grades} AS qg ON  qg.quiz=q.id AND qg.userid=ue.userid
  WHERE c.id=$kursid AND m.name = 'quiz' AND cm.section=$altbolum AND user2.deleted=0 AND user2.suspended=0 AND e.status=0 
       AND ue.userid
          IN (SELECT DISTINCT u.id
              FROM {user} u
                JOIN {user_enrolments} ue ON ue.userid = u.id
                JOIN {enrol} e ON e.id = ue.enrolid
                JOIN {role_assignments} ra ON ra.userid = u.id
                JOIN {context} ct ON ct.id = ra.contextid AND ct.contextlevel = 50
                JOIN {course} c ON c.id = ct.instanceid AND e.courseid = c.id
                JOIN {role} r ON r.id = ra.roleid AND r.archetype = 'student'
              WHERE e.status = 0 AND u.suspended = 0 AND u.deleted = 0
                    AND (ue.timeend = 0 OR ue.timeend > NOW()) AND ue.status = 0
                    AND c.id=$kursid)
GROUP BY user2.id HAVING (puan >= $from AND puan <= $to)";
                }else{
                    $sql = "
SELECT DISTINCT
  user2.id ,
  user2.department,
  user2.phone1,
 (CASE WHEN ISNULL(SUM(qg.grade)) THEN 0 ELSE SUM(qg.grade)/$quizsayisi END) AS puan
  FROM {user_enrolments} AS ue
  JOIN  {enrol} AS e ON e.id = ue.enrolid
  JOIN  {course} AS c ON c.id = e.courseid
  JOIN {course_modules} AS cm ON cm.course=c.id
  JOIN  {user} AS user2 ON user2.id = ue.userid
  JOIN {modules} AS m ON m.id=cm.module
  JOIN {quiz} AS q ON q.course = c.id AND q.id=cm.instance
  LEFT JOIN  {quiz_grades} AS qg ON  qg.quiz=q.id AND qg.userid=ue.userid
  WHERE c.id=$kursid AND m.name = 'quiz' AND cm.section=$altbolum AND user2.deleted=0 AND user2.suspended=0 AND e.status=0 
       AND ue.userid
          IN (SELECT DISTINCT u.id
              FROM {user} u
                JOIN {user_enrolments} ue ON ue.userid = u.id
                JOIN {enrol} e ON e.id = ue.enrolid
                JOIN {role_assignments} ra ON ra.userid = u.id
                JOIN {context} ct ON ct.id = ra.contextid AND ct.contextlevel = 50
                JOIN {course} c ON c.id = ct.instanceid AND e.courseid = c.id
                JOIN {role} r ON r.id = ra.roleid AND r.archetype = 'student'
              WHERE e.status = 0 AND u.suspended = 0 AND u.deleted = 0
                    AND (ue.timeend = 0 OR ue.timeend > NOW()) AND ue.status = 0
                    AND c.id=$kursid)
                    AND user2.department
  IN(SELECT DISTINCT bolum FROM {block_okulsis_sms_setting} WHERE ogretmen_id = $USER->id AND yetki_tur=1)
GROUP BY user2.id HAVING (puan >= $from AND puan <= $to)
";
                }



                return ortalamatabloyaz($sql, 'filtrelistele');
            } else {
                return 1;
            }
            break;
        case 4:
            if ($kursid > 0 and $altbolum > 0) {
                global $DB;
                $sql1 = "SELECT q.id AS id ,q.name AS name
    FROM {course_modules} cm
    JOIN {modules} m ON m.id= cm.module
    LEFT JOIN {quiz} q ON q.id = cm.instance
    WHERE m.name = 'quiz' AND cm.section=?";
                $sinavlar = $DB->get_records_sql($sql1, array($altbolum));
                if(is_siteadmin()){
                    $sql2 = "SELECT DISTINCT
   user2.id ,
   user2.phone1,
   user2.department
   FROM {user_enrolments} AS ue
  JOIN  {enrol} AS e ON e.id = ue.enrolid
  JOIN  {course} AS c ON c.id = e.courseid
  JOIN  {user} AS user2 ON user2 .id = ue.userid
  WHERE c.id=$kursid AND user2.deleted=0 AND user2.suspended=0 AND e.status=0
  AND ue.userid
  IN (SELECT DISTINCT u.id 
  FROM {user} u
  JOIN {user_enrolments} ue ON ue.userid = u.id
  JOIN {enrol} e ON e.id = ue.enrolid
  JOIN {role_assignments} ra ON ra.userid = u.id
  JOIN {context} ct ON ct.id = ra.contextid AND ct.contextlevel = 50
  JOIN {course} c ON c.id = ct.instanceid AND e.courseid = c.id
  JOIN {role} r ON r.id = ra.roleid AND r.archetype = 'student'
  WHERE e.status = 0 AND u.suspended = 0 AND u.deleted = 0
  AND (ue.timeend = 0 OR ue.timeend > NOW()) AND ue.status = 0
  AND c.id=$kursid)
  ORDER BY user2.department";
                }else{
                    $sql2 = "SELECT DISTINCT
   user2.id ,
   user2.phone1,
   user2.department
   FROM {user_enrolments} AS ue
  JOIN  {enrol} AS e ON e.id = ue.enrolid
  JOIN  {course} AS c ON c.id = e.courseid
  JOIN  {user} AS user2 ON user2 .id = ue.userid
  WHERE c.id=$kursid AND user2.deleted=0 AND user2.suspended=0 AND e.status=0
  AND ue.userid
  IN (SELECT DISTINCT u.id 
  FROM {user} u
  JOIN {user_enrolments} ue ON ue.userid = u.id
  JOIN {enrol} e ON e.id = ue.enrolid
  JOIN {role_assignments} ra ON ra.userid = u.id
  JOIN {context} ct ON ct.id = ra.contextid AND ct.contextlevel = 50
  JOIN {course} c ON c.id = ct.instanceid AND e.courseid = c.id
  JOIN {role} r ON r.id = ra.roleid AND r.archetype = 'student'
  WHERE e.status = 0 AND u.suspended = 0 AND u.deleted = 0
  AND (ue.timeend = 0 OR ue.timeend > NOW()) AND ue.status = 0
  AND c.id=$kursid)
  AND user2.department
  IN(SELECT DISTINCT bolum FROM {block_okulsis_sms_setting} WHERE ogretmen_id = $USER->id AND yetki_tur=1)
  ORDER BY user2.department
  ";
                }

                $ogrenciler = $DB->get_records_sql($sql2, array());
                return gelismistabloyaz($ogrenciler, $sinavlar, 'filtrelistele');
            } else {
                return 1;
            }

            break;

    }

}

/**
 * @return string
 */
function okulsis_ogretmensec() {
    global $DB;
    $sql = "SELECT DISTINCT u.id,u.firstname,u.lastname
  FROM {user} u
  JOIN {role_assignments} ra ON ra.userid = u.id
  JOIN {role} r ON r.id = ra.roleid AND r.archetype = 'coursecreator'
  WHERE u.suspended = 0 AND u.deleted = 0 
 ";
    $rs = $DB->get_records_sql($sql, array());
    if ($rs) {
        $html = '<select id="ogretmensec">';
        foreach ($rs as $log) {
            $html .= '<option value="' . $log->id . '">' . $log->firstname . ' ' . $log->lastname . '</option>';

        }
        $html .= '</select>';

    } else {
        $html = okulsis_mesajyaz('warning', 'Sistemde Kursoluşturucu rolünde öğretmen bulunamadı!');
    }

    return $html;
}

/**
 * @return string
 */
function okulsis_bakiye() {
    global $CFG;
    if ($CFG->block_okulsis_sms_api == 0) {
        if ($CFG->block_okulsis_sms_bakiye == 1) {
            // Username.
            $username = $CFG->block_okulsis_sms_username;
            // Password
            $password = $CFG->block_okulsis_sms_password;
            $url = "http://api.netgsm.com.tr/get_kredi.asp?usercode=" . $username . "&password=" . $password;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            $output = curl_exec($ch);
            curl_close($ch);
            $bulkarray = explode(" ", $output);
            $url2 = "http://api.netgsm.com.tr/get_kredi.asp?usercode=" . $username . "&password=" . $password . "&tip=1";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url2);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            $output = curl_exec($ch);
            curl_close($ch);
            $bulkarray2 = explode("|", $output);

            $html = 'Bakiyeniz:&nbsp;<span class="badge badge-info">' . $bulkarray[1] . '&nbsp;TL</span>&nbsp;|&nbsp;Kalan Paket:&nbsp;<span class="badge badge-warning">
' . $bulkarray2[0] . '&nbsp;&nbsp;</span>' . $bulkarray2[1] . $bulkarray2[2];
            return $html;
        } else {
            return '';
        }
    }
}

/**
 * @param string $tur
 * @return string
 */
function okulsis_sablonlar($tur) {
    global $CFG;
    switch ($tur) {
        case 'ilk':
            $sablanlar = explode('|', $CFG->block_okulsis_sms_ilksablon);
            $html = '<select id="ilksablon">';
            $html .= '<option value="">Kullanılmasın</option>';
            foreach ($sablanlar as $sablon) {
                $html .= '<option value="' . $sablon . '">' . $sablon . '</option>';
            }
            $html .= '</select>';
            return $html;
            break;
        case 'son':
            $sablanlar = explode('|', $CFG->block_okulsis_sms_sonsablon);
            $html = '<select id="sonsablon">';
            $html .= '<option value="">Kullanılmasın</option>';
            foreach ($sablanlar as $sablon) {
                $html .= '<option value="' . $sablon . '">' . $sablon . '</option>';
            }
            $html .= '</select>';
            return $html;
            break;
    }
}

/**
 * @return string $html
 */
function okulsis_getmsgheader() {
    global $CFG;
    if ($CFG->block_okulsis_sms_api == 0) {
        // Username.
        $username = $CFG->block_okulsis_sms_username;
        // Password
        $password = $CFG->block_okulsis_sms_password;
        $url = "https://api.netgsm.com.tr/get_msgheader.asp?usercode=" . urlencode($username) . "&password=" . urldecode($password);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $output = curl_exec($ch);
        curl_close($ch);
        $html = '<select id="msgheader">';
        if ($output != 30) {
            $msgheaders = explode("<br>", $output, -1);
            foreach ($msgheaders as $item) {
                $html .= '<option value="' . $item . '">' . $item . '</option>';
            }
        } else {
            $html .= '<option value="-1">Bilgi Alınamadı!</option>';
        }
        $html .= '</select>';
        return $html;
    } else {
        return '';
    }

}

function replaceSpace($string) {
    $string = preg_replace("/\s+/", " ", $string);
    $string = trim($string);
    return $string;
}

function block_okulsis_sms_rapor($bulk, $v) {
    global $CFG;
    // Username.
    $username = $CFG->block_okulsis_sms_username;
    // Password
    $password = $CFG->block_okulsis_sms_password;

    $url = "https://api.netgsm.com.tr/httpbulkrapor.asp?usercode=" . $username . "&password=" . $password . "&bulkid=" . $bulk .
            "&type=0&status=100&version=" . $v;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $output = curl_exec($ch);
    curl_close($ch);
    $bulkarray = explode("<br>", $output, -1);
    // foreach ($bulkarray as $item){
    // $arraysatir[]=$item;
    // }

    return $bulkarray;

}

function block_okulsis_sms_durum($durumid) {
    if ($durumid == 0) {
        $message = '<span class="label label-success">İletilmeyi Bekliyor..</span>';
    } else {
        if ($durumid == 1) {
            $message = '<span class="label label-success">İletildi</span>';
        } else {
            if ($durumid == 2) {
                $message = '<span class="label label-warning">Zaman Aşımı</span>';
            } else {
                if ($durumid == 3) {
                    $message = '<span class="label label-important">Hatalı veya Kısıtlı Numara</span>';
                } else {
                    if ($durumid == 4) {
                        $message = '<span class="label label-important">Operatöre Gönderilemedi</span>';
                    } else {
                        if ($durumid == 11) {
                            $message = '<span class="label label-important">Operatör Kabul etmedi</span>';
                        } else {
                            if ($durumid == 12) {
                                $message = '<span class="label label-important">Gönderim Hatası</span>';
                            } else {
                                if ($durumid == 13) {
                                    $message = '<span class="label label-warning">Mükerrer Gönderim</span>';
                                } else {
                                    if ($durumid == 103) {
                                        $message = '<span class="label label-warning">Tüm Görev Başarısız</span>';
                                    } else {
                                        $message = 'Diğer';
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    return $message;

}

function block_okulsis_sms_operator($durumid) {
    global $CFG;
    if ($durumid == 10) {
        $message = '<img src="' . $CFG->wwwroot . '/blocks/okulsis/pic/vodafone.png"></img>';
    } else {
        if ($durumid == 20) {
            $message = '<img src="' . $CFG->wwwroot . '/blocks/okulsis/pic/avea.png"></img>';
        } else {
            if ($durumid == 30) {
                $message = '<img src="' . $CFG->wwwroot . '/blocks/okulsis/pic/turkcell.png"></img>';
            } else {
                if ($durumid == 40) {
                    $message = '<span class="label label-info">Diğer</span>';
                } else {
                    if ($durumid == 50) {
                        $message = '<img src="' . $CFG->wwwroot . '/blocks/okulsis/pic/ttnet.png"></img>';
                    } else {
                        if ($durumid == 60) {
                            $message = '<span class="label label-info">Diğer</span>';
                        } else {
                            if ($durumid == 70) {
                                $message = '<span class="label label-info">Diğer</span>';
                            } else {
                                $message = '<span class="label label-info">Diğer</span>';
                            }
                        }
                    }
                }
            }
        }
    }

    return $message;

}

function block_okulsis_sms_hata($durumid) {
    if ($durumid == 0) {
        $message = '<span class="label label-success">Hata Yok</span>';
    } else {
        if ($durumid == 101) {
            $message = '<span class="label label-warning">Mesaj kutusu Dolu</span>';
        } else {
            if ($durumid == 102) {
                $message = '<span class="label label-warning">kapalı yada kapsama dışında</span>';
            } else {
                if ($durumid == 103) {
                    $message = '<span class="label label-warning">Meşgul</span>';
                } else {
                    if ($durumid == 104) {
                        $message = '<span class="label label-warning">Hat aktif değil</span>';
                    } else {
                        if ($durumid == 105) {
                            $message = '<span class="label label-warning">Hatalı Numara</span>';
                        } else {
                            if ($durumid == 106) {
                                $message = '<span class="label label-warning">SMS red ,Karaliste</span>';
                            } else {
                                if ($durumid == 111) {
                                    $message = '<span class="label label-warning">Zaman Aşımı</span>';
                                } else {
                                    if ($durumid == 112) {
                                        $message = '<span class="label label-warning">SMS gönderimine kapalı</span>';
                                    } else {
                                        if ($durumid == 113) {
                                            $message = '<span class="label label-warning">Mobil Cihaz Desteklemiyor</span>';
                                        } else {
                                            if ($durumid == 114) {
                                                $message = '<span class="label label-warning">Yönlendirme Başarısız</span>';
                                            } else {
                                                if ($durumid == 115) {
                                                    $message = '<span class="label label-warning">Çağrı Yasaklandı</span>';
                                                } else {
                                                    if ($durumid == 116) {
                                                        $message = '<span class="label label-important">Hatalı No</span>';
                                                    } else {
                                                        if ($durumid == 117) {
                                                            $message = '<span class="label label-warning">Yasadışı Abone</span>';
                                                        } else {
                                                            if ($durumid == 119) {
                                                                $message =
                                                                        '<span class="label label-important">Sistem Hatası</span>';
                                                            } else {
                                                                $message = '<span class="label label-important">Diğer</span>';
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    return $message;

}