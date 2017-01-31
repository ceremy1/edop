<?php
/**
 * Created by PhpStorm.
 * User: Çaglar
 * Date: 09.09.2016
 * Time: 22:39
 */
global $CFG;
require_once(__DIR__. '/../../../config.php');
require_once ("$CFG->dirroot/blocks/nisan_rozet/locallib.php");
$filtre  = required_param('filtre',PARAM_TEXT);
$nisanid = optional_param('nisanid',null,PARAM_INT);
$sinifid = optional_param('sinifid',null,PARAM_INT);
$qid = optional_param('qid',null,PARAM_INT);
$bid = optional_param('bid',null,PARAM_INT);
$from = optional_param('from',null,PARAM_INT);
$to = optional_param('to',null,PARAM_INT);
if ($filtre == 'nisancontent'){
  global $DB;
    if($nisanid != -1){
        $rs =$DB->get_record('block_nisan_rozet_nisan',array('id'=>$nisanid),'*');
        echo '<div class="row-fluid" ><div class="span3"><dl><dt>Resim :</dt><dt>'.block_nisan_rozet_nisanresimal($nisanid).'</dt></dl></div>
        <div class="span9"><dl><dt>Tanım :</dt><dd>'.$rs->tanim.'</dd></div></dl>
        <div>';
    }else{
        echo '';
    }
}
else if ($filtre == 'sinif'){
 if ($sinifid == -1){
   $sql = null;
 }else if($sinifid == -2){
     $sql="SELECT usr.firstname, usr.id, usr.lastname,c.name AS sinif
        FROM {cohort} c
        INNER JOIN {cohort_members} cm ON c.id = cm.cohortid
        INNER JOIN  {user} usr ON cm.userid = usr.id";
    } else {
        $sql = "SELECT usr.firstname, usr.id, usr.lastname,c.name AS sinif
        FROM {cohort} c
        INNER JOIN {cohort_members} cm ON c.id = cm.cohortid
        INNER JOIN  {user} usr ON cm.userid = usr.id
        WHERE c.id = $sinifid
        
        ";
    }
     echo (block_nisan_rozet_ogrlistetablo($sql));
     
 }   
else if ($filtre == 'not'){
    if($qid != -1) {
        global $DB;
        $courseid = $DB->get_field("quiz", "course", array("id" => $qid), $strictness = IGNORE_MISSING);
        $sql = "SELECT DISTINCT
   user2.id ,
   user2.firstname,
   user2.lastname ,
   coh.name AS sinif
  FROM {user_enrolments} AS ue
  JOIN  {enrol} AS e ON e.id = ue.enrolid
  JOIN  {course} AS c ON c.id = e.courseid
  JOIN  {user} AS user2 ON user2 .id = ue.userid
  LEFT JOIN {cohort_members} co ON co.userid = user2.id
  LEFT JOIN {cohort} coh ON coh.id = co.cohortid
  WHERE c.id=$courseid AND user2.deleted=0 AND user2.suspended=0 AND e.status=0  
  AND ue.userid
   IN (SELECT qg.userid FROM  {quiz_grades} AS qg
  JOIN  {quiz} AS q ON qg.quiz = q.id
  JOIN  {course} AS c ON q.course = c.id
  WHERE c.id = $courseid AND q.id=$qid  AND (qg.grade BETWEEN $from AND $to ))
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
  AND c.id=$courseid)
  AND co.cohortid
  IN (SELECT DISTINCT MIN(c.cohortid) FROM {cohort_members} c WHERE c.userid=user2.id)
  
  ";

    }else{
       $sql =null;
    }
    echo(block_nisan_rozet_ogrlistetablo($sql));
    
}
else if ($filtre == 'ortalama'){
    if($bid != -1) {
        $courseid = $DB->get_field("course_sections","course",array("id"=>$bid), $strictness=IGNORE_MISSING);
        $sql1="SELECT COUNT(q.id) AS id 
    FROM {course_modules} cm
    JOIN {modules} m ON m.id= cm.module
    LEFT JOIN {quiz} q ON q.id = cm.instance
    WHERE m.name = 'quiz' AND cm.section=?";
        $quizsayisi=$DB->count_records_sql($sql1,array($bid));
        $sql="
SELECT DISTINCT
  user2.id ,
  user2.firstname,
  user2.lastname ,
  coh.name AS sinif,
  (CASE WHEN ISNULL(SUM(qg.grade)) THEN 0 ELSE SUM(qg.grade)/$quizsayisi END) AS puan

FROM {user_enrolments} AS ue
  JOIN  {enrol} AS e ON e.id = ue.enrolid
  JOIN  {course} AS c ON c.id = e.courseid
  JOIN {course_modules} AS cm ON cm.course=c.id
  JOIN  {user} AS user2 ON user2.id = ue.userid
  JOIN {modules} AS m ON m.id=cm.module
  JOIN {quiz} AS q ON q.course = c.id AND q.id=cm.instance
  LEFT JOIN  {quiz_grades} AS qg ON  qg.quiz=q.id AND qg.userid=ue.userid
  LEFT JOIN {cohort_members} co ON co.userid = user2.id
  LEFT JOIN {cohort} coh ON coh.id = co.cohortid
WHERE c.id=$courseid AND m.name = 'quiz' AND cm.section=$bid AND user2.deleted=0 AND user2.suspended=0 AND e.status=0
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
                    AND c.id=$courseid)
                    AND co.cohortid
IN (SELECT DISTINCT MIN(c.cohortid) FROM {cohort_members} c WHERE c.userid=user2.id)
GROUP BY user2.id";

    }else{
        $sql =null;
    }
    echo(block_nisan_rozet_ogrlistetablo($sql,$from,$to));

}




