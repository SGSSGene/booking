<?PHP
    $entrypoint=1;
    include "config.php";
    $year = $_GET["year"];
    $month = $_GET["month"];
    $duration = $_GET["duration"];
    $capacity = $_GET["capacity"];

    $stmt = $db->prepare('SELECT datetime(ot.day, ts.start) AS start_time, ts.Duration, :duration AS requestedDuration, r.name AS location, COALESCE(t.unused_capacity, r.capacity) AS unused_capacity, r.capacity, COALESCE(t.numberOfExams, 0) AS number_of_other_exams
    FROM Room AS r
        JOIN OperatingTime AS ot ON r.id == ot.room
        JOIN DaySchedule AS ds ON ot.daySchedule == ds.id
        JOIN TimeSlots AS ts ON ts.daySchedule == ds.id
        LEFT OUTER JOIN (SELECT datetime(e2.start) AS exam_start, datetime(e2.start, "+" || e2.duration || " minutes") AS exam_end,
                r2.capacity-SUM(e2.attendees) AS unused_capacity, r2.capacity AS total_capacity, count(*) AS numberOfExams, r2.id AS roomId, e2.duration AS duration
             FROM Exams AS e2
                JOIN Room As r2 ON e2.roomId == r2.id
             GROUP BY e2.start, e2.duration, e2.roomId) AS t ON t.roomId == r.id AND t.exam_start == datetime(ot.day, ts.start)
    WHERE ts.Duration >= requestedDuration
        AND strftime("%Y-%m", ot.day) == :month
        AND :capacity <= COALESCE(t.unused_capacity, r.capacity)
        AND (t.duration IS NULL OR requestedDuration == t.duration)
        AND (t.duration IS NULL OR t.duration == ts.duration)');
    $stmt->bindValue(':month', $year . "-" . $month, SQLITE3_TEXT);
    $stmt->bindValue(':duration', $duration, SQLITE3_INTEGER);
    $stmt->bindValue(':capacity', $capacity, SQLITE3_INTEGER);
    $result = $stmt->execute();
    $output = array();
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        array_push($output, $row);
    }
    echo json_encode($output, JSON_PRETTY_PRINT);


/*
# all possible slots
SELECT strftime("%Y-%m-%d", ot.day) AS start_time, ts.Duration, r.name AS location
	FROM Room AS r
		JOIN OperatingTime AS ot ON r.id == ot.room
		JOIN DaySchedule AS ds ON ot.daySchedule == ds.id
		JOIN TimeSlots AS ts ON ts.daySchedule == ds.id
	WHERE ts.Duration >= 120
		AND strftime("%Y-%m-%d", ot.day) == "2024-12-11"
		AND r.capacity >= 50;

# all booked slots
SELECT strftime("%Y-%m-%d", e.start) AS start_time, e.duration, r.name AS location, r.capacity-sum(attendees) AS unused_capacity, count(*) AS number_of_exams
	FROM Room AS r
		JOIN Exams AS e ON r.id == e.roomId
	WHERE strftime("%Y-%m-%d", e.start) == "2024-12-11";xe


# all slots that havn't reached capacity yet
SELECT strftime("%Y-%m-%d", ot.day) AS start_time, ts.Duration, r.name AS location, *, r.capacity, 50 AS requestedDuration
	FROM Room AS r
		JOIN OperatingTime AS ot ON r.id == ot.room
		JOIN DaySchedule AS ds ON ot.daySchedule == ds.id
		JOIN TimeSlots AS ts ON ts.daySchedule == ds.id
	WHERE ts.Duration >= requestedDuration
		AND strftime("%Y-%m-%d", ot.day) == "2024-12-11"
		AND NOT EXISTS (
			SELECT
				datetime(e2.start) AS exam_start, datetime(e2.start, "+" || e2.duration || " minutes") AS exam_end,
				datetime(ot.day, "+" ||  ts.start) AS slot_start, datetime(ot.day, "+" || ts.start, "+" || ts.duration || " minutes") AS slot_end,
				r.capacity-SUM(e2.attendees) AS unused_capacity
			FROM Exams AS e2
			WHERE e2.roomId == r.id
				AND strftime("%Y-%m-%d", e2.start) == strftime("%Y-%m-%d", ot.day)
				AND exam_start < slot_end AND exam_end > slot_start
			 GROUP BY exam_start, e2.duration
			 HAVING  5 > unused_capacity OR requestedDuration != e2.duration
		)

// same but simpler?
SELECT datetime(ot.day, ts.start) AS start_time, ts.Duration, 60 AS requestedDuration, r.name AS location, COALESCE(t.unused_capacity, r.capacity), r.capacity, COALESCE(t.numberOfExams, 0) AS number_of_other_exams
	FROM Room AS r
		JOIN OperatingTime AS ot ON r.id == ot.room
		JOIN DaySchedule AS ds ON ot.daySchedule == ds.id
		JOIN TimeSlots AS ts ON ts.daySchedule == ds.id
		LEFT OUTER JOIN (SELECT datetime(e2.start) AS exam_start, datetime(e2.start, "+" || e2.duration || " minutes") AS exam_end,
				r2.capacity-SUM(e2.attendees) AS unused_capacity, r2.capacity AS total_capacity, count(*) AS numberOfExams, r2.id AS roomId, e2.duration AS duration
			 FROM Exams AS e2
				JOIN Room As r2 ON e2.roomId == r2.id
			 GROUP BY e2.start, e2.duration, e2.roomId) AS t ON t.roomId == r.id AND t.exam_start == datetime(ot.day, ts.start)
	WHERE ts.Duration >= requestedDuration
		AND strftime("%Y-%m-%d", ot.day) == "2024-12-09"
		AND 5 <= COALESCE(t.unused_capacity, r.capacity)
		AND (t.duration IS NULL OR requestedDuration == t.duration)
		AND (t.duration IS NULL OR t.duration == ts.duration)

;
*/
?>
