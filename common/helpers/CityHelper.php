<?php

namespace common\helpers;

use Yii;

class CityHelper
{
    // public static function extractCityFromAddress($address) 
    // {
    //     // Split the address by commas
    //     $addressParts = explode(',', $address);
        
    //     // Reverse the array to find the city, which is usually the second-to-last element
    //     $reversedParts = array_reverse($addressParts);
        
    //     // Find the city by checking the part before the postal code or 'Saudi Arabia'
    //     foreach ($reversedParts as $part) {
    //         // Remove any whitespace from the start and end of the part
    //         $part = trim($part);
            
    //         // If the part is not numeric (like postal code) and not the country, assume it's the city
    //         if (!is_numeric($part) && stripos($part, 'Saudi Arabia') === false) {
    //             return $part; // Return the first part that fits this condition, which should be the city
    //         }
    //     }

    //     return null; // Return null if no city is found
    // }

    public static function getCityFromAddress($address)
    {
        // Retrieve the API key
        $apiKey = env('GOOGLE_MAP_API_KEY');
        if (!$apiKey) {
            return 'API key not found';
        }

        // Prepare the API URL
        $url = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($address) . '&key=' . $apiKey;

        // Make the API request using file_get_contents or CURL
        $response = file_get_contents($url);
        $data = json_decode($response, true);

        // Check if the API returned a valid result
        if ($data['status'] === 'OK') {
            // Loop through the address components and find the 'locality' type (city)
            foreach ($data['results'][0]['address_components'] as $component) {
                if (in_array('locality', $component['types'])) {
                    return $component['long_name']; // Return the city name
                }
            }
        }

        return Yii::t('common', 'Unknown');
    }

}
