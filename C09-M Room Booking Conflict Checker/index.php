<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) !== '/book') {
    http_response_code(404);
    echo json_encode(['error' => 'Not Found']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$room_id = $input['room_id'] ?? null;
$date = $input['date'] ?? null;
$start = $input['start'] ?? null;
$end = $input['end'] ?? null;
$attendees = (int)($input['attendees'] ?? 0);

$rooms = json_decode(file_get_contents('data/rooms.json'), true);
$bookings = json_decode(file_get_contents('data/bookings.json'), true);

// 1. Room exists
$targetRoom = null;
foreach ($rooms as $room) {
    if ($room['id'] == $room_id) {
        $targetRoom = $room;
        break;
    }
}
if (!$targetRoom) {
    http_response_code(404);
    echo json_encode(['error' => 'Room not found']);
    exit;
}

// 2. Capacity
if ($attendees < 1 || $attendees > $targetRoom['capacity']) {
    http_response_code(422);
    echo json_encode(['error' => 'Room capacity exceeded']);
    exit;
}

// 3. Opening hours
if ($start < '08:00' || $end > '20:00' || $start > '20:00' || $end < '08:00') {
    http_response_code(422);
    echo json_encode(['error' => 'Outside opening hours']);
    exit;
}

// 4. Time range
if ($end <= $start) {
    http_response_code(422);
    echo json_encode(['error' => 'Invalid time range']);
    exit;
}

// 5. No overlap
foreach ($bookings as $b) {
    if ($b['room_id'] == $room_id && $b['date'] === $date) {
        if (!($end <= $b['start'] || $start >= $b['end'])) {
            http_response_code(409);
            echo json_encode(['error' => "Time slot conflicts with booking {$b['id']}"]);
            exit;
        }
    }
}

// 6. Success
$maxId = 0;
foreach ($bookings as $b) {
    if ($b['id'] > $maxId) {
        $maxId = $b['id'];
    }
}
$newId = $maxId + 1;

$newBooking = [
    "id" => $newId,
    "room_id" => $room_id,
    "date" => $date,
    "start" => $start,
    "end" => $end,
    "attendees" => $attendees
];

http_response_code(201);
echo json_encode(["data" => $newBooking]);
