<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;

class WeatherController extends Controller
{
	public function getWeather($latitude, $longitude)
    {
		$cacheKey = "{$latitude}_{$longitude}";
        $data = Cache::get($cacheKey);
        $apiKey = env('OPENWEATHER_API_KEY');
        $apiUrl = env('OPENWEATHER_URL');

        if (empty($data)) {
            $param = http_build_query(array(
                "lat" => $latitude,
                "lon" => $longitude,
                "appid" => $apiKey,
				"units" => "metric"
            ));
            $url = "{$apiUrl}?{$param}";

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json'));
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

            $result = curl_exec($curl);
            $info = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            if ($info !== 200) {
                curl_close($curl);
                return response()->json([
                    'status' => 'failed',
                ]);
            }

            $result = json_decode($result);
            if (!empty($result)) {
                Cache::put($cacheKey, json_encode($result), 60); // cache the data for 1 hour
            }

            return response()->json(array(
                'status' => 'success',
                'data' => $result
            ));
        }

        return response()->json(array(
            'status' => 'success',
            'data' => json_decode($data, true)
        ));
    }
}
