<?php


function xmldb_block_nisan_rozet_upgrade($oldversion) {


    global $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 2016090502) {

        // Define key fk_atama (foreign) to be added to block_nisan_rozet_atama.
        $table = new xmldb_table('block_nisan_rozet_atama');
        $key = new xmldb_key('fk_atama', XMLDB_KEY_FOREIGN, array('nisan_id'), 'block_nisan_rozet_nisan', array('id'));
        $key1 = new xmldb_key('fk_giver', XMLDB_KEY_FOREIGN, array('giverid'), 'user', array('id'));
        // Launch add key fk_atama.
        $dbman->add_key($table, $key);
        $dbman->add_key($table, $key1);

        // Nisan_rozet savepoint reached.
        upgrade_block_savepoint(true, 2016090502, 'nisan_rozet');
    }
    if ($oldversion < 2016102601) {

        // Define field multi to be added to block_nisan_rozet_kriter.
        $table = new xmldb_table('block_nisan_rozet_kriter');
        $field = new xmldb_field('multi', XMLDB_TYPE_INTEGER, '1', null, null, null,'0', 'aktif');

        // Conditionally launch add field multi.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Nisan_rozet savepoint reached.
        upgrade_block_savepoint(true, 2016102601, 'nisan_rozet');
    }



    return true;

}

