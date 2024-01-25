<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['filterdate'])) {
    $filterDate = $_POST['filterdate'];
    $rootFolder = $_SERVER['DOCUMENT_ROOT'] . '/axportal/';
    $logFilePath =$rootFolder.'Logs/' . date('Y/F/m-d-Y', strtotime($filterDate)) . '.txt';

    if (file_exists($logFilePath)) {
        $logContent = file_get_contents($logFilePath);
        $response = [
            'success' => true,
            'message' => $logContent
        ];
    } else {
        $response = [
            'success' => false,
            'message' => 'No logs on the specified date.'
        ];
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
?>
