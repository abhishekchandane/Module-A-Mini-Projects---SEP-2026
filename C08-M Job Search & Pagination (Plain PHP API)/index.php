<?php
header('Content-Type: application/json');

if (parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) !== '/jobs') {
    http_response_code(404);
    echo json_encode(['error' => 'Not Found']);
    exit;
}

$jobsData = json_decode(file_get_contents('data/jobs.json'), true);

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) {
    $page = 1;
}

$filteredJobs = [];
if ($search !== '') {
    foreach ($jobsData as $job) {
        if (stripos($job['title'], $search) !== false || stripos($job['company'], $search) !== false) {
            $filteredJobs[] = $job;
        }
    }
} else {
    $filteredJobs = $jobsData;
}

$total = count($filteredJobs);
$perPage = 10;
$totalPages = ceil($total / $perPage);
if ($totalPages < 1) {
    $totalPages = 1;
}

$offset = ($page - 1) * $perPage;
$paginatedData = array_slice($filteredJobs, $offset, $perPage);

echo json_encode([
    "data" => array_values($paginatedData),
    "meta" => [
        "total" => $total,
        "page" => $page,
        "per_page" => $perPage,
        "total_pages" => $totalPages
    ]
]);
