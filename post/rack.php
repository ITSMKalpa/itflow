<?php

/*
 * ITFlow - GET/POST request handler for client racks
 */

if (isset($_POST['add_rack'])) {

    validateTechRole();

    $client_id = intval($_POST['client_id']);
    $name = sanitizeInput($_POST['name']);
    $description = sanitizeInput($_POST['description']);
    $type = sanitizeInput($_POST['type']);
    $model = sanitizeInput($_POST['model']);
    $depth = sanitizeInput($_POST['depth']);
    $units = intval($_POST['units']);
    $physical_location = sanitizeInput($_POST['physical_location']);
    $location = intval($_POST['location']);
    $notes = sanitizeInput($_POST['notes']);

    mysqli_query($mysqli,"INSERT INTO racks SET rack_name = '$name', rack_description = '$description', rack_type = '$type', rack_model = '$model', rack_depth = '$depth', rack_units = $units, rack_location_id = $location, rack_physical_location = '$physical_location', rack_notes = '$notes', rack_client_id = $client_id");

    $rack_id = mysqli_insert_id($mysqli);

    // Add Photo
    if ($_FILES['file']['tmp_name'] != '') {
        if ($new_file_name = checkFileUpload($_FILES['file'], array('jpg', 'jpeg', 'gif', 'png'))) {

            $file_tmp_path = $_FILES['file']['tmp_name'];

            // directory in which the uploaded file will be moved
            if (!file_exists("uploads/clients/$client_id")) {
                mkdir("uploads/clients/$client_id");
            }
            $upload_file_dir = "uploads/clients/$client_id/";
            $dest_path = $upload_file_dir . $new_file_name;
            move_uploaded_file($file_tmp_path, $dest_path);

            mysqli_query($mysqli,"UPDATE racks SET rack_photo = '$new_file_name' WHERE rack_id = $rack_id");
        }
    }

    //Logging
    mysqli_query($mysqli,"INSERT INTO logs SET log_type = 'Rack', log_action = 'Create', log_description = '$session_name created rack $name', log_ip = '$session_ip', log_user_agent = '$session_user_agent', log_client_id = $client_id, log_user_id = $session_user_id, log_entity_id = $rack_id");

    $_SESSION['alert_message'] = "Rack <strong>$name</strong> created";

    header("Location: " . $_SERVER["HTTP_REFERER"]);

}

if (isset($_POST['edit_rack'])) {

    validateTechRole();

    $rack_id = intval($_POST['rack_id']);
    $client_id = intval($_POST['client_id']);
    $name = sanitizeInput($_POST['name']);
    $description = sanitizeInput($_POST['description']);
    $type = sanitizeInput($_POST['type']);
    $model = sanitizeInput($_POST['model']);
    $depth = sanitizeInput($_POST['depth']);
    $units = intval($_POST['units']);
    $physical_location = sanitizeInput($_POST['physical_location']);
    $location = intval($_POST['location']);
    $notes = sanitizeInput($_POST['notes']);

    mysqli_query($mysqli,"UPDATE racks SET rack_name = '$name', rack_description = '$description', rack_type = '$type', rack_model = '$model', rack_depth = '$depth', rack_units = $units, rack_location_id = $location, rack_physical_location = '$physical_location', rack_notes = '$notes' WHERE rack_id = $rack_id");

    // Add Photo
    if ($_FILES['file']['tmp_name'] != '') {
        if ($new_file_name = checkFileUpload($_FILES['file'], array('jpg', 'jpeg', 'gif', 'png'))) {

            $file_tmp_path = $_FILES['file']['tmp_name'];

            // directory in which the uploaded file will be moved
            if (!file_exists("uploads/clients/$client_id")) {
                mkdir("uploads/clients/$client_id");
            }
            $upload_file_dir = "uploads/clients/$client_id/";
            $dest_path = $upload_file_dir . $new_file_name;
            move_uploaded_file($file_tmp_path, $dest_path);

            mysqli_query($mysqli,"UPDATE racks SET rack_photo = '$new_file_name' WHERE rack_id = $rack_id");
        }
    }

    //Logging
    mysqli_query($mysqli,"INSERT INTO logs SET log_type = 'Rack', log_action = 'Edit', log_description = '$session_name edited rack $name', log_ip = '$session_ip', log_user_agent = '$session_user_agent', log_client_id = $client_id, log_user_id = $session_user_id, log_entity_id = $rack_id");

    $_SESSION['alert_message'] = "Rack <strong>$name</strong> edited";

    header("Location: " . $_SERVER["HTTP_REFERER"]);

}

if (isset($_GET['archive_rack'])) {

    validateTechRole();

    $rack_id = intval($_GET['archive_rack']);

    // Get Name and Client ID for logging and alert message
    $sql = mysqli_query($mysqli,"SELECT rack_name, rack_client_id FROM racks WHERE rack_id = $rack_id");
    $row = mysqli_fetch_array($sql);
    $rack_name = sanitizeInput($row['rack_name']);
    $client_id = intval($row['asset_client_id']);

    mysqli_query($mysqli,"UPDATE racks SET rack_archived_at = NOW() WHERE rack_id = $rack_id");

    //logging
    mysqli_query($mysqli,"INSERT INTO logs SET log_type = 'Rack', log_action = 'Archive', log_description = '$session_name archived rack $rack_name', log_ip = '$session_ip', log_user_agent = '$session_user_agent', log_client_id = $client_id, log_user_id = $session_user_id, log_entity_id = $rack_id");

    $_SESSION['alert_type'] = "error";
    $_SESSION['alert_message'] = "Asset <strong>$rack_name</strong> archived";

    header("Location: " . $_SERVER["HTTP_REFERER"]);

}

if (isset($_GET['unarchive_rack'])) {

    validateTechRole();

    $rack_id = intval($_GET['unarchive_rack']);

    // Get Name and Client ID for logging and alert message
    $sql = mysqli_query($mysqli,"SELECT rack_name, rack_client_id FROM racks WHERE rack_id = $rack_id");
    $row = mysqli_fetch_array($sql);
    $rack_name = sanitizeInput($row['rack_name']);
    $client_id = intval($row['rack_client_id']);

    mysqli_query($mysqli,"UPDATE racks SET rack_archived_at = NULL WHERE rack_id = $rack_id");

    //logging
    mysqli_query($mysqli,"INSERT INTO logs SET log_type = 'Asset', log_action = 'Unarchive', log_description = '$session_name restored rack $rack_name', log_ip = '$session_ip', log_user_agent = '$session_user_agent', log_client_id = $client_id, log_user_id = $session_user_id, log_entity_id = $asset_id");

    $_SESSION['alert_message'] = "Rack <strong>$rack_name</strong> Unarchived";

    header("Location: " . $_SERVER["HTTP_REFERER"]);

}

if (isset($_GET['delete_rack'])) {

    validateAdminRole();

    $rack_id = intval($_GET['delete_rack']);

    // Get Name and Client ID for logging and alert message
    $sql = mysqli_query($mysqli,"SELECT rack_name, rack_client_id, rack_photo FROM racks WHERE rack_id = $rack_id");
    $row = mysqli_fetch_array($sql);
    $rack_name = sanitizeInput($row['rack_name']);
    $rack_photo = sanitizeInput($row['rack_photo']);
    $client_id = intval($row['rack_client_id']);

    mysqli_query($mysqli,"DELETE FROM racks WHERE rack_id = $rack_id");

    // Delete Photo if exists
    if ($rack_photo) {
        unlink("uploads/clients/$client_id/$rack_photo");
    }

    //Logging
    mysqli_query($mysqli,"INSERT INTO logs SET log_type = 'Rack', log_action = 'Delete', log_description = '$session_name deleted rack $rack_name', log_ip = '$session_ip', log_user_agent = '$session_user_agent', log_client_id = $client_id, log_user_id = $session_user_id, log_entity_id = $rack_id");

    $_SESSION['alert_type'] = "error";
    $_SESSION['alert_message'] = "Rack <strong>$rack_name</strong> deleted";

    header("Location: " . $_SERVER["HTTP_REFERER"]);

}

if (isset($_POST['add_rack_unit'])) {

    validateTechRole();

    $client_id = intval($_POST['client_id']);
    $rack_id = intval($_POST['rack_id']);
    $name = sanitizeInput($_POST['name']);
    $unit_start = intval($_POST['unit_start']);
    $unit_end = intval($_POST['unit_end']);
    $asset = intval($_POST['asset']);

    // Check if the unit range is already occupied
    $check_sql = mysqli_query($mysqli, "SELECT * FROM rack_units WHERE unit_rack_id = $rack_id AND 
        ((unit_start_number <= $unit_start AND unit_end_number >= $unit_start) OR 
        (unit_start_number <= $unit_end AND unit_end_number >= $unit_end) OR 
        ($unit_start <= unit_start_number AND $unit_end >= unit_start_number))");

    if (mysqli_num_rows($check_sql) > 0) {
        // If there is an overlap, return an error message
        $_SESSION['alert_type'] = "error";
        $_SESSION['alert_message'] = "Units $unit_start to $unit_end are already in use by another device.";
        header("Location: " . $_SERVER["HTTP_REFERER"]);
        exit();
    }

    // If no overlap, proceed with the insertion
    mysqli_query($mysqli, "INSERT INTO rack_units SET unit_device = '$name', unit_asset_id = $asset, unit_start_number = $unit_start, unit_end_number = $unit_end, unit_rack_id = $rack_id");

    $unit_id = mysqli_insert_id($mysqli);

    // Logging
    mysqli_query($mysqli, "INSERT INTO logs SET log_type = 'Rack Unit', log_action = 'Create', log_description = '$session_name added a unit the rack', log_ip = '$session_ip', log_user_agent = '$session_user_agent', log_client_id = $client_id, log_user_id = $session_user_id, log_entity_id = $rack_id");

    $_SESSION['alert_message'] = "Device Added to Unit $unit_start - $unit_end to rack";

    header("Location: " . $_SERVER["HTTP_REFERER"]);
}

if (isset($_POST['edit_rack_unit'])) {

    validateTechRole();

    $unit_id = intval($_POST['unit_id']);
    $client_id = intval($_POST['client_id']);
    $rack_id = intval($_POST['rack_id']);
    $name = sanitizeInput($_POST['name']);
    $unit_start = intval($_POST['unit_start']);
    $unit_end = intval($_POST['unit_end']);
    $asset = intval($_POST['asset']);

    mysqli_query($mysqli,"UPDATE rack_units SET unit_device = '$name', unit_asset_id = $asset, unit_start_number = $unit_start, unit_end_number = $unit_end WHERE unit_id = $unit_id");

    //Logging
    mysqli_query($mysqli,"INSERT INTO logs SET log_type = 'Rack Unit', log_action = 'Edit', log_description = '$session_name edited a unit on the rack', log_ip = '$session_ip', log_user_agent = '$session_user_agent', log_client_id = $client_id, log_user_id = $session_user_id, log_entity_id = $rack_id");

    $_SESSION['alert_message'] = "Device edited on the rack";

    header("Location: " . $_SERVER["HTTP_REFERER"]);

}

if (isset($_GET['remove_rack_unit'])) {

    validateTechRole();

    $unit_id = intval($_GET['remove_rack_unit']);

    // Get Name and Client ID for logging and alert message
    $sql = mysqli_query($mysqli,"SELECT rack_name, rack_id, rack_client_id FROM racks LEFT JOIN rack_units ON unit_rack_id = rack_id WHERE unit_id = $unit_id");
    $row = mysqli_fetch_array($sql);
    $rack_name = sanitizeInput($row['rack_name']);
    $client_id = intval($row['rack_client_id']);
    $rack_id = intval($row['rack_id']);

    mysqli_query($mysqli,"DELETE FROM rack_units WHERE unit_id = $unit_id");

    //Logging
    mysqli_query($mysqli,"INSERT INTO logs SET log_type = 'Rack Unit', log_action = 'Delete', log_description = '$session_name removed device from rack', log_ip = '$session_ip', log_user_agent = '$session_user_agent', log_client_id = $client_id, log_user_id = $session_user_id, log_entity_id = $rack_id");

    $_SESSION['alert_type'] = "error";
    $_SESSION['alert_message'] = "You removed device from the rack";

    header("Location: " . $_SERVER["HTTP_REFERER"]);

}