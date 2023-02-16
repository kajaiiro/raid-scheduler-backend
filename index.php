<?php
// Error reporting and display
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Headers for cross-origin
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");

// Include database connection file
include 'DbConnect.php';

// Create it as object and connect to the database
$objDb = new DbConnect;
$conn = $objDb->connect();

// Get the HTTP method used in the request 
$method = $_SERVER['REQUEST_METHOD'];
// Based on the HTTP method, perform CRUD operation
switch($method) {
    case "GET":
        // Retrieve events based on ID, on specified URI
        $sql = "SELECT * FROM events";
        $path = explode('/', $_SERVER['REQUEST_URI']);
        if(isset($path[3]) && is_numeric($path[3])) {
            $sql .= " WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $path[3]);
            $stmt->execute();
            $events = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        // Encode the retrieved events as JSON and output 
        echo json_encode($events);
        break;
    case "POST":
        // create a new event record with data from the request body
        $event = json_decode( file_get_contents('php://input') );
        $sql = "INSERT INTO events(id, `group`, date, status, created_at) VALUES(null, :group, :date, :status, :created_at)";
        $stmt = $conn->prepare($sql);
        $created_at = date('Y-m-d');
        $stmt->bindParam(':group', $event->group);
        $stmt->bindParam(':date', $event->date);
        $stmt->bindParam(':status', $event->status);
        $stmt->bindParam(':created_at', $created_at);

        // check if the event record was successfully created and output response accordingly
        if($stmt->execute()) {
            $response = ['status' => 1, 'message' => 'Record created successfully.'];
        } else {
            $response = ['status' => 0, 'message' => 'Failed to create record.'];
        }
        echo json_encode($response);
        break;

    case "PUT":
        // update an existing event record with data from the request body
        $event = json_decode( file_get_contents('php://input') );
        $sql = "UPDATE events SET `group`= :group, date =:date, status =:status, updated_at =:updated_at WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $updated_at = date('Y-m-d');
        $stmt->bindParam(':id', $event->id);
        $stmt->bindParam(':group', $event->group);
        $stmt->bindParam(':date', $event->date);
        $stmt->bindParam(':status', $event->status);
        $stmt->bindParam(':updated_at', $updated_at);
    
        if($stmt->execute()) {
            $response = ['status' => 1, 'message' => 'Record updated successfully.'];
        } else {
            $response = ['status' => 0, 'message' => 'Failed to update record.'];
        }
        echo json_encode($response);
        break;

    case "DELETE":
        $sql = "DELETE FROM events WHERE id = :id";
        $path = explode('/', $_SERVER['REQUEST_URI']);

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $path[3]);

        if($stmt->execute()) {
            $response = ['status' => 1, 'message' => 'Record deleted successfully.'];
        } else {
            $response = ['status' => 0, 'message' => 'Failed to delete record.'];
        }
        echo json_encode($response);
        break;
}