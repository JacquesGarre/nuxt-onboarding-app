<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Entity\UserNode;

class OpenTripApiService {

    private HttpClientInterface $client;
    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client->withOptions([
            'max_redirects' => 5,
            'max_duration' => 10
        ]);
    }

    public function getPlacesAroundUserNode(UserNode $userNode)
    {
        $centerLat = $userNode->getLatitude();
        $centerLong = $userNode->getLongitude();
        [$minLat, $minLong] = $this->getDestinationPoint($centerLat, $centerLong, 45); 
        [$maxLat, $maxLong] = $this->getDestinationPoint($centerLat, $centerLong, 225);
        $headers = $this->getQueryHeaders();
        $url = $this->getQueryUrl($minLat, $minLong, $maxLat, $maxLong);
        $response = $this->client->request('GET', $url, $headers);   
        if(!empty($response) && $response->getStatusCode()){
            $content = $response->toArray();
            if(!empty($content['features'])){
                return $content['features'];
            }
        }
        return [];
    }

    private function getQueryUrl($minLat, $minLong, $maxLat, $maxLong)
    {
        if($minLong > $maxLong){
            $tmp = $maxLong;
            $maxLong = $minLong;
            $minLong = $tmp;
        }
        if($minLat > $maxLat){
            $tmp = $maxLat;
            $maxLat = $minLat;
            $minLat = $tmp;
        }

        $params = [
            'lon_min' => $minLong,
            'lon_max' => $maxLong,
            'lat_min' => $minLat,
            'lat_max' => $maxLat,
            'apikey'  => $_ENV['OPEN_TRIP_API_KEY'],
            'rate'    => '3h',
            'limit'   => $_ENV['ITEMS_TO_FETCH_LIMIT'],
            'kinds'   => 'architecture,cultural,historic,natural,view_points,religion'
        ];
        $url = $_ENV['OPEN_TRIP_API_URL'] .'?' . http_build_query($params);
        return $url;
    }

    private function getQueryHeaders()
    {
        return [
            'headers' => [
                'Accept' => 'application/json',
            ],
        ];
    }

    private function getDestinationPoint($alat, $alon, $bearing)
    {
        $distance = $_ENV['RADIUS_TO_FIND_PLACES'] / 1000;
        $pi = pi();
        $alatRad = $alat*$pi/180;
        $alonRad = $alon*$pi/180;
        $bearing = $bearing*$pi/180;
        $alatRadSin = sin($alatRad);
        $alatRadCos = cos($alatRad);
        $angularDistance = $distance/6370.997;
        $angDistSin = sin($angularDistance);
        $angDistCos = cos($angularDistance);
        $xlatRad = asin($alatRadSin*$angDistCos+$alatRadCos*$angDistSin*cos($bearing));
        $xlonRad = $alonRad + atan2(
            sin($bearing)*$angDistSin*$alatRadCos,
            $angDistCos-$alatRadSin*sin($xlatRad)
        );

        // Return latitude and longitude as two element array in degrees
        $xlat=$xlatRad*180/$pi;
        $xlon=$xlonRad*180/$pi;
        if($xlat>90)$xlat=90;
        if($xlat<-90)$xlat=-90;
        while($xlat>180)$xlat-=360;
        while($xlat<=-180)$xlat+=360;
        while($xlon>180)$xlon-=360;
        while($xlon<=-180)$xlon+=360;
        return array($xlat,$xlon);
    }

    public function getItemDescription($itemID)
    {
        $headers = $this->getQueryHeaders();
        $url = $this->getItemDescriptionQueryUrl($itemID);
        $response = $this->client->request('GET', $url, $headers);   
        if(!empty($response) && $response->getStatusCode()){
            $content = $response->toArray();
            if(!empty($content['wikipedia_extracts']['text'])){
                return $content['wikipedia_extracts']['text'];
            }
        }
  
        return array($xlat,$xlon);
    }

    private function getItemDescriptionQueryUrl($itemID)
    {
        $params = [
            'apikey'  => $_ENV['OPEN_TRIP_API_KEY'],
        ];
        $url = $_ENV['OPEN_TRIP_API_DETAILS_URL'].$itemID.'?' . http_build_query($params);
        return $url;
    }


}
