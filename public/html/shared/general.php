<?php 

// function to fetch data from an API using GET method
function fetch_data($url, $default = []) {
    $url = "http://localhost/api/" . $url;

    $json = @file_get_contents($url);

    if ($json === false) {
        return $default;
    }
        
    $response = json_decode($json, true);
        
    if (json_last_error() !== JSON_ERROR_NONE) {
        return $default;
    }
    
    return $response['data'] ?? $default;

}