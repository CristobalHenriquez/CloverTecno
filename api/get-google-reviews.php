<?php
require_once '../includes/db_connection.php';

function getGoogleReviews() {
    $place_id = 'ChIJz2DCcQJNtpURwquKy2cni6s'; // Replace with your actual Place ID
    $api_key = 'AIzaSyB_4x-7hSyo8Wff9O82Zy3TzbtgdT61rzQ'; // You'll need to get this from Google Cloud Console
    
    // Check if we have cached reviews
    $cache_file = '../cache/google_reviews.json';
    if (file_exists($cache_file) && (time() - filemtime($cache_file) < 24 * 3600)) {
        return json_decode(file_get_contents($cache_file), true);
    }
    
    // If no cache, fetch from Google
    $url = "https://maps.googleapis.com/maps/api/place/details/json?place_id={$place_id}&fields=reviews&key={$api_key}";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($ch);
    curl_close($ch);
    
    $result = json_decode($response, true);
    
    if (isset($result['result']['reviews'])) {
        // Cache the results
        if (!is_dir('../cache')) {
            mkdir('../cache', 0755, true);
        }
        file_put_contents($cache_file, json_encode($result['result']['reviews']));
        
        return $result['result']['reviews'];
    }
    
    return [];
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    header('Content-Type: application/json');
    echo json_encode(getGoogleReviews());
}