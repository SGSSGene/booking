<?PHP
    $entrypoint=1;
    include "config.php";

    $lvkennung    = $_POST["lvkennung_check"];
    $lvnbr        = $_POST["lvnbr_check"];
    $lvtype       = $_POST["lvtype_check"];
    $lvsemester   = $_POST["lvsemester_check"];
    $lvformat     = $_POST["lvformat_check"];
    $lvdate       = $_POST["lvdate_check"];
    $lvduration   = $_POST["lvduration_check"];
    $lvattendees  = $_POST["lvattendees_check"];


    echo $lvkennung."<br>";
    echo $lvnbr."<br>";
    echo $lvtype."<br>";
    echo $lvsemester."<br>";
    echo $lvformat."<br>";
    echo $lvdate."<br>";
    echo $lvduration."<br>";
    echo $lvattendees."<br>";

    //!TODO all variables require validation

    $stmt = $db->prepare('INSERT INTO "main"."Exams" ("name", "roomId", "start", "duration", "attendees") VALUES (:name, :roomId, :start, :duration, :attendees)');
    if (!$stmt) die('invalid statement '. $db->error);
    $r = $stmt->bindValue(':name', $lvkennung . $lvnbr . $lvtype . $lvsemester, SQLITE3_TEXT);
    if (!$r) die('invalid statement '. $db->error);
    $r = $stmt->bindValue(':roomId', 1, SQLITE3_INTEGER);
    if (!$r) die('invalid statement '. $db->error);
    $r = $stmt->bindValue(':start', $lvdate, SQLITE3_TEXT);
    if (!$r) die('invalid statement '. $db->error);
    $r = $stmt->bindValue(':duration', $lvduration, SQLITE3_INTEGER);
    if (!$r) die('invalid statement '. $db->error);
    $r = $stmt->bindValue(':attendees', $lvattendees, SQLITE3_INTEGER);
    if (!$r) die('invalid statement '. $db->error);
    $result = $stmt->execute();
    if (!$result) {
        echo "failed:<br>";
        echo $db->error;
        echo $stmt->fullQuery;
    }
    echo "<br><a href=\"/\">Zurück zur Übersicht</a>";
?>
