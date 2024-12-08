<?PHP
    $entrypoint=1;
    include "config.php";
    $year = $_GET["year"];
    $month = $_GET["month"];

    $stmt = $db->prepare('SELECT strftime("%Y-%m-%d %H:%M", e.start) AS start_time, e.duration, r.name AS location, e.id, e.name, e.attendees
        FROM Room AS r
            JOIN Exams AS e ON r.id == e.roomId
        WHERE strftime("%Y-%m", e.start) == :month;'
    );

    $stmt->bindValue(':month', $year . "-" . $month, SQLITE3_TEXT);
    $result = $stmt->execute();
    $output = array();
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        array_push($output, $row);
    }
    echo json_encode($output, JSON_PRETTY_PRINT);
?>
