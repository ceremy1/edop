<?php
namespace block_sms\task;
class deletemessage extends \core\task\scheduled_task {
    public function get_name() {
        // Shown in admin screens
        return get_string('deletemessage', 'block_sms');
    }

    public function execute() {
        global $CFG;

        require_once($CFG->dirroot . '/blocks/sms/lib.php');
        if($CFG->block_sms_api_oldmessage == 4 ){

            mtrace('NetGsm eski mesaj silme işlemi config den iptal edildi!');
            return true;


        }else{

            deloldmessage();

        }

    }
}



?>