<?php


function xmldb_block_sms_upgrade($oldversion) {


    global $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 2016081406) {

        // Define field sinif to be added to block_sms_kaydet.
        $table = new xmldb_table('block_sms_kaydet');
        $field = new xmldb_field('sinif', XMLDB_TYPE_CHAR, '50', null, null, null, null, '');

        // Conditionally launch add field sinif.
        $dbman->change_field_notnull($table, $field);

        // Sms savepoint reached.

    }
    if ($oldversion < 2016081406) {

        // Define field ders to be added to block_sms_kaydet.
        $table = new xmldb_table('block_sms_kaydet');
        $field = new xmldb_field('ders', XMLDB_TYPE_CHAR, '50', null, null, null, null, '');

        // Conditionally launch add field ders.
        $dbman->change_field_notnull($table, $field);

        // Sms savepoint reached.

    }
    if ($oldversion < 2016081406) {

        // Define field soyad to be added to block_sms_kaydet.
        $table = new xmldb_table('block_sms_kaydet');
        $field = new xmldb_field('soyad', XMLDB_TYPE_CHAR, '100', null, null, null, null, '');

        // Conditionally launch add field soyad.
        $dbman->change_field_notnull($table, $field);

        // Sms savepoint reached.

    }
    if ($oldversion < 2016081406) {

        // Define field ad to be added to block_sms_kaydet.
        $table = new xmldb_table('block_sms_kaydet');
        $field = new xmldb_field('ad', XMLDB_TYPE_CHAR, '100', null, null, null, null, '');

        // Conditionally launch add field ad.
        $dbman->change_field_notnull($table, $field);

        // Sms savepoint reached.
        upgrade_block_savepoint(true, 2016081406, 'sms');
    }
    if ($oldversion < 2016082501) {

        // Changing the default of field sinif on table block_sms_kaydet to BK.
        $table = new xmldb_table('block_sms_kaydet');
        $field = new xmldb_field('sinif', XMLDB_TYPE_CHAR, '50', null, null, null, 'BK','');

        // Launch change of default for field sinif.
        $dbman->change_field_default($table, $field);

        // Sms savepoint reached.
        upgrade_block_savepoint(true, 2016082501, 'sms');
    }
    if ($oldversion < 2016082504) {

        // Changing nullability of field sinif on table block_sms_kaydet to not null.
        $table = new xmldb_table('block_sms_kaydet');
        $field = new xmldb_field('sinif', XMLDB_TYPE_CHAR, '50', null, XMLDB_NOTNULL, null, null, 'tel');

        // Launch change of nullability for field sinif.
        $dbman->change_field_notnull($table, $field);
        $dbman->change_field_default($table, $field);
        // Sms savepoint reached.
        upgrade_block_savepoint(true, 2016082504, 'sms');
    }
    if ($oldversion < 2016083000) {

        // Define field ogrenci_id to be added to block_sms_kaydet.
        $table = new xmldb_table('block_sms_kaydet');
        $field = new xmldb_field('ogrenci_id', XMLDB_TYPE_INTEGER, '10', null, null, null, null, '');
        $field1 = new xmldb_field('gonderen_ad', XMLDB_TYPE_CHAR, '100', null, null, null, null, '');
        $field2 = new xmldb_field('gonderen_soyad', XMLDB_TYPE_CHAR, '100', null, null, null, null, '');
        $field3 = new xmldb_field('gonderen_id', XMLDB_TYPE_INTEGER, '10', null, null, null, null, '');
        // Conditionally launch add field ogrenci_id.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        if (!$dbman->field_exists($table, $field1)) {
            $dbman->add_field($table, $field1);
        }
        if (!$dbman->field_exists($table, $field2)) {
            $dbman->add_field($table, $field2);
        }
        if (!$dbman->field_exists($table, $field3)) {
            $dbman->add_field($table, $field3);
        }
        // Sms savepoint reached.
        upgrade_block_savepoint(true, 2016083000, 'sms');
    }
    if ($oldversion < 2016090101) {

        // Define table block_sms_settings to be created.
        $table = new xmldb_table('block_sms_settings');

        // Adding fields to table block_sms_settings.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('ogretmen_id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('ogretmen_ad', XMLDB_TYPE_CHAR, '100', null, null, null, null);
        $table->add_field('ogretmen_soyad', XMLDB_TYPE_CHAR, '100', null, null, null, null);
        $table->add_field('yetkisi', XMLDB_TYPE_CHAR, '100', null, XMLDB_NOTNULL, null, null);
        $table->add_field('yetki_tur', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, null);

        // Adding keys to table block_sms_settings.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

        // Conditionally launch create table for block_sms_settings.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Sms savepoint reached.
        upgrade_block_savepoint(true, 2016090101, 'sms');
    }
    if ($oldversion < 2016090104) {

        // Define field gonderildimi to be added to block_sms_kaydet.
        $table = new xmldb_table('block_sms_kaydet');
        $field = new xmldb_field('gonderildimi', XMLDB_TYPE_INTEGER, '1', null, null, null, '0', 'gonderen_id');

        // Conditionally launch add field gonderildimi.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Sms savepoint reached.
        upgrade_block_savepoint(true, 2016090104, 'sms');
    }



    return true;

}

