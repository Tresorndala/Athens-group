<?php
include 'connection.php';

if (isset($_GET['id'])) {
    $report_id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT report_id, description, status FROM report WHERE report_id = ?");
    $stmt->bind_param("i", $report_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $report = $result->fetch_assoc();
        echo json_encode([
            'success' => true,
            'data' => [
                'report_id' => $report['report_id'],
                'description' => $report['description'],
                'status' => $report['status'],
            ]
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Report not found.'
        ]);
    }
    $stmt->close();
} else {
    echo json_encode([
        'success' => false,
        'message' => 'No report ID provided.'
    ]);
}

$conn->close();
?>
