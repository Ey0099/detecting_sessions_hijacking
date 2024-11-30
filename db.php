<?php
// Database connection
function getDBConnection() {
    $host = 'localhost';
    $db = 'session_monitoring';
    $user = 'root'; // Change this to your MySQL username
    $pass = ''; // Change this to your MySQL password
    return new PDO("mysql:host=$host;dbname=$db", $user, $pass);
}

//function logUserActivity($userId, $monitoring = false) {
//    try {
//        $db = getDBConnection();
//        // Get geolocation data using an external API
//        $geoInfo = file_get_contents("http://ip-api.com/json/");
//        $geoData = json_decode($geoInfo, true);
//
//        if ($geoData['status'] === 'fail') {
//            if ($geoData['message'] === 'reserved range') {
//                $geolocation = "Local Network, Localhost";
//            } else {
//                throw new Exception("Geolocation lookup failed: " . $geoData['message']);
//            }
//        } else {
//            $geolocation = $geoData['city'] . ", " . $geoData['country'];
//        }
//        // Fetch the last log to compare IP and geolocation
//        $stmt = $db->prepare("SELECT * FROM logs WHERE user_id = ? ORDER BY timestamp DESC LIMIT 1");
//        $stmt->execute([$userId]);
//        $lastLog = $stmt->fetch(PDO::FETCH_ASSOC);
//        // Use IP from the API result
//        $ip_address = $geoData['query'];
//        // Prepare data for anomaly detection
////        $input_data = [
////            'ip_address' => $ip_address,
////            'last_ip' => $lastLog ? $lastLog['ip_address'] : $ip_address,
////            'geolocation' => $geolocation,
////            'last_geo' => $lastLog ? $lastLog['geolocation'] : $geolocation,
////            'device_info' => $_SERVER['HTTP_USER_AGENT'],
////            'user_id' => $userId,
////        ];
//
//        $input_data = [
//            'dur' => 0,  // Replace with actual data if available
//            'spkts' => 0,
//            'dpkts' => 0,
//            'sbytes' => 0,
//            'dbytes' => 0,
//            'rate' => 0,
//            'sttl' => 0,
//            'dttl' => 0,
//            'sload' => 0,
//            'dload' => 0,
//            'sinpkt' => 0,
//            'dinpkt' => 0,
//            'sjit' => 0,
//            'djit' => 0,
//            'swin' => 0,
//            'stcpb' => 0,
//            'dtcpb' => 0,
//            'dwin' => 0,
//            'tcprtt' => 0,
//            'synack' => 0,
//            'ackdat' => 0,
//            'smean' => 0,
//            'dmean' => 0,
//            'trans_depth' => 0,
//            'response_body_len' => 0,
//            'ct_srv_src' => 0,
//            'ct_state_ttl' => 0,
//            'ct_dst_ltm' => 0,
//            'ct_src_dport_ltm' => 0,
//            'ct_dst_sport_ltm' => 0,
//            'ct_dst_src_ltm' => 0,
//            'is_ftp_login' => 0,
//            'ct_ftp_cmd' => 0,
//            'ct_flw_http_mthd' => 0,
//            'ct_src_ltm' => 0,
//            'ct_srv_dst' => 0,
//            'is_sm_ips_ports' => 0,
//            'user_id' => $userId,  // Example of additional data for logging
//            'ip_address' => $ip_address,
//            'last_ip' => $lastLog ? $lastLog['ip_address'] : $ip_address,
//            'geolocation' => $geolocation,
//            'last_geo' => $lastLog ? $lastLog['geolocation'] : $geolocation,
//            'device_info' => $_SERVER['HTTP_USER_AGENT']
//        ];
//
//        // Call the Python API using cURL
//        $url = 'http://127.0.0.1:5000/detect_anomaly';
//        $ch = curl_init($url);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//        curl_setopt($ch, CURLOPT_POST, true);
//        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
//        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($input_data));
//        $response = curl_exec($ch);
//        curl_close($ch);
//
//        // Decode the API response
//        $result = json_decode($response, true);
//var_dump($result);
//die();
//        // If monitoring (polling), return the result without logging every time
//        if ($monitoring) {
//            return $result;
//        }
//        // Check the result of anomaly detection
//        if ($result['status'] == 'anomaly') {
//            foreach ($result['anomalies'] as $anomaly) {
//                logAnomaly($userId, $anomaly);
//            }
//            $stmt = $db->prepare("UPDATE users SET locked = 1 WHERE user_id = ?");
//            $stmt->execute([$userId]);
//            terminateSession($userId);
//        } else {
//            // No anomaly detected, log the session
//            $stmt = $db->prepare("INSERT INTO logs (user_id, session_id, ip_address, geolocation, device_info) VALUES (?, ?, ?, ?, ?)");
//            $stmt->execute([$userId, session_id(), $ip_address, $geolocation, $_SERVER['HTTP_USER_AGENT']]);
//        }
//    } catch (PDOException $e) {
//        echo "Logging error: " . $e->getMessage();
//    } catch (Exception $e) {
//        echo "Error: " . $e->getMessage();
//    }
//}
function logUserActivity($userId, $monitoring = false) {
    try {
        $db = getDBConnection();

        // Prepare all session data for anomaly detection
        $input_data = prepareSessionData($userId);

        // Call the Python API using cURL
        $url = 'http://127.0.0.1:5000/detect_anomaly';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($input_data));
        $response = curl_exec($ch);
        curl_close($ch);

        // Decode the API response
        $result = json_decode($response, true);

        // If monitoring (polling), return the result without logging every time
        if ($monitoring) {
            return $result;
        }

        // Check the result of anomaly detection
        if ($result['status'] == 'anomaly') {
            foreach ($result['anomalies'] as $anomaly) {
                logAnomaly($userId, $anomaly);
            }
            $stmt = $db->prepare("UPDATE users SET locked = 1 WHERE user_id = ?");
            $stmt->execute([$userId]);
            terminateSession($userId);
        } else {
            // No anomaly detected, log the session with additional data
            $stmt = $db->prepare("INSERT INTO logs 
                (user_id, session_id, ip_address, geolocation, device_info, dur, spkts, dpkts, sbytes, dbytes, rate) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $userId,
                session_id(),
                $input_data['ip_address'],
                $input_data['geolocation'],
                $input_data['device_info'],
                $input_data['dur'],
                $input_data['spkts'],
                $input_data['dpkts'],
                $input_data['sbytes'],
                $input_data['dbytes'],
                $input_data['rate']
            ]);
        }
    } catch (PDOException $e) {
        echo "Logging error: " . $e->getMessage();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}


// Log detected anomaly
function logAnomaly($userId, $anomalyType) {
    try {
        $db = getDBConnection();
        $stmt = $db->prepare("INSERT INTO anomalies (user_id, anomaly_type) VALUES (?, ?)");
        $stmt->execute([$userId, $anomalyType]);
    } catch (PDOException $e) {
        echo "Anomaly logging error: " . $e->getMessage();
    }
}

// Terminate the session if an anomaly is detected
function terminateSession($userId) {
    // End the session
    session_destroy();

    // Redirect the user to the login page with a message about suspicious activity and an unlock link
    header("Location: login.php?error=suspicious_activity&unlock=true&user_id=" . urlencode($userId));
    exit();
}
function getUserIP() {
    // Check if the IP is passed from a proxy
    if (isset($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];  // Can return multiple IPs, in which case use the first one
    } elseif (isset($_SERVER['REMOTE_ADDR'])) {
        return $_SERVER['REMOTE_ADDR'];
    } else {
        return 'UNKNOWN';
    }
}




function getDuration($userId) {
    $db = getDBConnection();
    // Fetch the first and last log timestamps for the user’s current session
    $stmt = $db->prepare("SELECT MIN(timestamp) AS start, MAX(timestamp) AS end FROM logs WHERE user_id = ?");
    $stmt->execute([$userId]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result && $result['start'] && $result['end']) {
        // Calculate duration in seconds
        $start = strtotime($result['start']);
        $end = strtotime($result['end']);
        return $end - $start;
    }
    return 0; // Default value if no data available
}

function getPacketCount($userId, $type) {
    $db = getDBConnection();
    // Assume there’s a column 'packets_sent' and 'packets_received' in the logs table
    $column = $type === 'sent' ? 'spkts' : 'dpkts';
    $stmt = $db->prepare("SELECT SUM($column) AS packet_count FROM logs WHERE user_id = ?");
    $stmt->execute([$userId]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ? $result['packet_count'] : 0;
}

function getByteCount($userId, $type) {
    $db = getDBConnection();
    // Assume there’s a column 'bytes_sent' and 'bytes_received' in the logs table
    $column = $type === 'sent' ? 'sbytes' : 'dbytes';
    $stmt = $db->prepare("SELECT SUM($column) AS byte_count FROM logs WHERE user_id = ?");
    $stmt->execute([$userId]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result ? $result['byte_count'] : 0;
}

function calculateRate($userId) {
    $duration = getDuration($userId);
    $bytesSent = getByteCount($userId, 'sent');
    $bytesReceived = getByteCount($userId, 'received');
    $totalBytes = $bytesSent + $bytesReceived;

    if ($duration > 0) {
        return $totalBytes / $duration;
    }
    return 0;
}
function prepareSessionData($userId) {
    try {
        $db = getDBConnection();
        $geoInfo = file_get_contents("http://ip-api.com/json/");
        $geoData = json_decode($geoInfo, true);
        if ($geoData['status'] === 'fail') {
            if ($geoData['message'] === 'reserved range') {
                $geolocation = "Local Network, Localhost";
            } else {
                throw new Exception("Geolocation lookup failed: " . $geoData['message']);
            }
        } else {
            $geolocation = $geoData['city'] . ", " . $geoData['country'];
        }
        $stmt = $db->prepare("SELECT * FROM logs WHERE user_id = ? ORDER BY timestamp DESC LIMIT 1");
        $stmt->execute([$userId]);
        $lastLog = $stmt->fetch(PDO::FETCH_ASSOC);
        $ip_address = $geoData['query'];
        $input_data = [
            'dur' => getDuration($userId),
            'spkts' => getPacketCount($userId, 'sent'),
            'dpkts' => getPacketCount($userId, 'received'),
            'sbytes' => getByteCount($userId, 'sent'),
            'dbytes' => getByteCount($userId, 'received'),
            'rate' => calculateRate($userId),
            'sttl' => 0,
            'dttl' => 0,
            'sload' => 0,
            'dload' => 0,
            'sinpkt' => 0,
            'dinpkt' => 0,
            'sjit' => 0,
            'djit' => 0,
            'swin' => 0,
            'stcpb' => 0,
            'dtcpb' => 0,
            'dwin' => 0,
            'tcprtt' => 0,
            'synack' => 0,
            'ackdat' => 0,
            'smean' => 0,
            'dmean' => 0,
            'trans_depth' => 0,
            'response_body_len' => 0,
            'ct_srv_src' => 0,
            'ct_state_ttl' => 0,
            'ct_dst_ltm' => 0,
            'ct_src_dport_ltm' => 0,
            'ct_dst_sport_ltm' => 0,
            'ct_dst_src_ltm' => 0,
            'is_ftp_login' => 0,
            'ct_ftp_cmd' => 0,
            'ct_flw_http_mthd' => 0,
            'ct_src_ltm' => 0,
            'ct_srv_dst' => 0,
            'is_sm_ips_ports' => 0,
            'user_id' => $userId,
            'ip_address' => $ip_address,
            'last_ip' => $lastLog ? $lastLog['ip_address'] : $ip_address,
            'geolocation' => $geolocation,
            'last_geo' => $lastLog ? $lastLog['geolocation'] : $geolocation,
            'device_info' => $_SERVER['HTTP_USER_AGENT']
        ];
        return $input_data;
    } catch (\Exception $e) {
        var_dump($e->getMessage());
        die();
    }

}
