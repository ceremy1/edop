<?php


function xmldb_block_okulsis_upgrade($oldversion) {


    global $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 2017010300) {

        // Define field ownerid to be added to block_okulsis_sms_basket.
        $table = new xmldb_table('block_okulsis_sms_basket');
        $field = new xmldb_field('ownerid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null, 'userid');

        // Conditionally launch add field ownerid.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }


        // Nisan_rozet savepoint reached.
        upgrade_block_savepoint(true, 2017010300, 'okulsis');
    }
    if ($oldversion < 2017010302) {

        // Define field tel to be added to block_okulsis_sms_basket.
        $table = new xmldb_table('block_okulsis_sms_basket');
        $field = new xmldb_field('tel', XMLDB_TYPE_CHAR, '15', null, null, null, null, 'ownerid');

        // Conditionally launch add field tel.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Okulsis savepoint reached.
        upgrade_block_savepoint(true, 2017010302, 'okulsis');
    }
    if ($oldversion < 2017010303) {

        // Define key userid (unique) to be dropped form block_okulsis_sms_basket.
        $table = new xmldb_table('block_okulsis_sms_basket');
        $key = new xmldb_key('userid', XMLDB_KEY_UNIQUE, array('userid'));

        // Launch drop key userid.
        $dbman->drop_key($table, $key);

        // Okulsis savepoint reached.
        upgrade_block_savepoint(true, 2017010303, 'okulsis');
    }
    if ($oldversion < 2017010305) {

        // Define field kurum to be added to block_okulsis_sms_setting.
        $table = new xmldb_table('block_okulsis_sms_setting');
        $field = new xmldb_field('kurum', XMLDB_TYPE_CHAR, '50', null, null, null, null, 'bolum');
        $field1 = new xmldb_field('bolum', XMLDB_TYPE_CHAR, '50', null, null, null, null, 'ogretmen_id');
        // Conditionally launch add field kurum.
        if (!$dbman->field_exists($table, $field1)) {
            $dbman->add_field($table, $field1);
        }
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        // Okulsis savepoint reached.
        upgrade_block_savepoint(true, 2017010305, 'okulsis');
    }
    if ($oldversion < 2017013000) {

        // Define table block_okulsis_tanim_kurum to be created.
        $table = new xmldb_table('block_okulsis_tanim_kurum');

        // Adding fields to table block_okulsis_tanim_kurum.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('name', XMLDB_TYPE_CHAR, '100', null, null, null, null);

        // Adding keys to table block_okulsis_tanim_kurum.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

        // Conditionally launch create table for block_okulsis_tanim_kurum.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Okulsis savepoint reached.
        upgrade_block_savepoint(true, 2017013000, 'okulsis');
    }
    if ($oldversion < 2017013001) {

        // Define table block_okulsis_tanim_derslik to be created.
        $table = new xmldb_table('block_okulsis_tanim_derslik');

        // Adding fields to table block_okulsis_tanim_derslik.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('name', XMLDB_TYPE_CHAR, '50', null, null, null, null);
        $table->add_field('kurum_id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);

        // Adding keys to table block_okulsis_tanim_derslik.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

        // Conditionally launch create table for block_okulsis_tanim_derslik.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Okulsis savepoint reached.
        upgrade_block_savepoint(true, 2017013001, 'okulsis');
    }
    if ($oldversion < 2017013002) {

        // Define table block_okulsis_tanim_sinif to be created.
        $table = new xmldb_table('block_okulsis_tanim_sinif');

        // Adding fields to table block_okulsis_tanim_sinif.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('name', XMLDB_TYPE_CHAR, '100', null, null, null, null);

        // Adding keys to table block_okulsis_tanim_sinif.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

        // Conditionally launch create table for block_okulsis_tanim_sinif.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Okulsis savepoint reached.
        upgrade_block_savepoint(true, 2017013002, 'okulsis');
    }




    return true;

}

