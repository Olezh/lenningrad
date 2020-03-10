<?php

namespace Services;

use http\Exception\BadUrlException;
use Ixudra\Curl\Facades\Curl;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class PhotoService
 * @package Services
 */
class PopularPeopleService
{
    private $url;
    private $apiKey;

    public function __construct()
    {
        $this->url = \config('variables.popular_people_url');
        $this->apiKey = \config('variables.api_key');
    }

    /**
     * @param int $count
     * @return array
     */
    public function getSomeRandomPeople(int $count):array
    {
        $result = [];
        $names = [];

        while (count($result) < $count){
            $people = $this->getOneRandomPeople();
            if($people['profile_path'] === null || in_array($people['name'], $names)){
                continue;
            }
            $result[] = $people;
            $names[] = $people['name'];
        }
        return $result;
    }

    /**
     * @return object
     */
    public function getOneRandomPeople():array
    {
        $page = rand(1, 500);
        $index = rand(0, 19);

        $rawData = Curl::to($this->url)
            ->withHeaders([
                'Authorization: Bearer ' . $this->apiKey,
                'Content-Type: application/json;charset=utf-8'
            ])
            ->withData(['page' => $page])
            ->get();

        $rawData = json_decode($rawData);

        if ($rawData === null || isset($rawData->errors)){
            throw new HttpException(400,'bad request');
        }
        return [
            'name' => $rawData->results[$index]->name,
            'profile_path' => $rawData->results[$index]->profile_path,
        ];
    }
}

