<?php

if (!function_exists('getMinSharingPrice')) {
    function getMinSharingPrice(array $sharingPrices)
    {
        return collect($sharingPrices)
            ->filter(fn ($price) => $price !== null && $price !== '')
            ->map(fn ($price) => (float) $price)
            ->min();
    }
}

if (!function_exists('calculateDistance')) {
    function calculateDistance($lat1, $lon1, $lat2, $lon2, $unit = 'km') 
    {
        $earthRadius = ($unit === 'km') ? 6371 : 3959; // Kilometers or Miles

        $latDelta = deg2rad($lat2 - $lat1);
        $lonDelta = deg2rad($lon2 - $lon1);

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($lonDelta / 2) * sin($lonDelta / 2);
            
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c; // Returns distance
    }
}
if(!function_exists('pr')){
    function pr($array){
        echo "<pre>";print_r($array);echo "</pre>";
    }
}