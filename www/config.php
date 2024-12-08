<?PHP
include "guard.php";

// create sql file, if not existing
$dbpath='../mydb.sqlite';
if (!file_exists($dbpath)) {
    $db = new SQLite3($dbpath, SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);
    $sql = file_get_contents('../create_db.sql');
    $db->exec($sql);

    //!TODO some default values
    $db->exec('INSERT INTO "main"."Room"("id","name","capacity") VALUES (1,"EEC1",50);');
    $db->exec('INSERT INTO "main"."Room"("id","name","capacity") VALUES (2,"EEC2",75);');
    $db->exec('INSERT INTO "main"."DaySchedule"("id","name") VALUES (1,"normalday");');
    $db->exec('INSERT INTO "main"."TimeSlots" ("Start", "Duration", "daySchedule") VALUES (10:00, 60, 1);');
    $db->exec('INSERT INTO "main"."TimeSlots" ("Start", "Duration", "daySchedule") VALUES (10:00, 120, 1);');
    $db->exec('INSERT INTO "main"."TimeSlots" ("Start", "Duration", "daySchedule") VALUES (11:00, 60, 1);');
    $db->exec('INSERT INTO "main"."TimeSlots" ("Start", "Duration", "daySchedule") VALUES (12:00, 60, 1);');
    $db->exec('INSERT INTO "main"."TimeSlots" ("Start", "Duration", "daySchedule") VALUES (12:00, 120, 1);');
    $db->exec('INSERT INTO "main"."TimeSlots" ("Start", "Duration", "daySchedule") VALUES (13:00, 60, 1);');
    $db->exec('INSERT INTO "main"."OperatingTime" ("day", "room", "daySchedule") VALUES ("2024-12-09", 1, 1);');
    $db->exec('INSERT INTO "main"."OperatingTime" ("day", "room", "daySchedule") VALUES ("2024-12-10", 1, 1);');
    $db->exec('INSERT INTO "main"."OperatingTime" ("day", "room", "daySchedule") VALUES ("2024-12-11", 1, 1);');
    $db->exec('INSERT INTO "main"."OperatingTime" ("day", "room", "daySchedule") VALUES ("2024-12-12", 1, 1);');
    $db->exec('INSERT INTO "main"."OperatingTime" ("day", "room", "daySchedule") VALUES ("2024-12-13", 1, 1);');
    $db->exec('INSERT INTO "main"."OperatingTime" ("day", "room", "daySchedule") VALUES ("2024-12-16", 1, 1);');
    $db->exec('INSERT INTO "main"."OperatingTime" ("day", "room", "daySchedule") VALUES ("2024-12-17", 1, 1);');
    $db->exec('INSERT INTO "main"."OperatingTime" ("day", "room", "daySchedule") VALUES ("2024-12-18", 1, 1);');
    $db->exec('INSERT INTO "main"."OperatingTime" ("day", "room", "daySchedule") VALUES ("2024-12-19", 1, 1);');
    $db->exec('INSERT INTO "main"."OperatingTime" ("day", "room", "daySchedule") VALUES ("2024-12-20", 1, 1);');

    $db->exec('INSERT INTO "main"."Exams" ("name", "roomId", "start", "duration", "attendees") VALUES ("Test Exam1", 1, "2024-12-09 10:00", 60, 30);');
    $db->exec('INSERT INTO "main"."Exams" ("name", "roomId", "start", "duration", "attendees") VALUES ("Test Exam2", 1, "2024-12-10 10:00", 120, 30);');
    $db->exec('INSERT INTO "main"."Exams" ("name", "roomId", "start", "duration", "attendees") VALUES ("Test Exam3", 1, "2024-12-11 11:00", 60, 30);');


}
$db = new SQLite3($dbpath, SQLITE3_OPEN_READWRITE);
$db->enableExceptions();

?>
