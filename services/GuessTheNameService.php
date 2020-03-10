<?php


namespace Services;


use Illuminate\Support\Facades\Cache;

class GuessTheNameService
{
    private const COUNT_PEOPLE = 5;
    private $peopleService;

    public function __construct(PopularPeopleService $peopleService)
    {
        $this->peopleService = $peopleService;
    }

    /**
     * @return array
     */
    public function getRiddle():array
    {
        $persons = $this->peopleService->getSomeRandomPeople($this::COUNT_PEOPLE);

        $person = rand(0,4);

        $names = array_map(function ($item){
            return $item['name'];
        }, $persons);

        $photoLink = $persons[$person]['profile_path'];

        Cache::put($photoLink, $persons[$person]['name'], 600);

        return [
          'names' => $names,
          'photo' => $photoLink
        ];
    }

}
