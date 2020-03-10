<?php


namespace App\Http\Controllers;

use Services\GuessTheNameService;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class RiddleController
 * @package App\Http\Controllers
 */
class RiddleController extends Controller
{
    private $guessService;

    public function __construct(GuessTheNameService $guessService)
    {
        $this->guessService = $guessService;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function guessPhoto()
    {
        try {
            $riddle = $this->guessService->getRiddle();
        } catch (HttpException $exception){
            return response()->json(
                ['error' => $exception->getMessage()]);
        }
        catch (\RedisException $exception) {
            return response()->json(
                ['error' => 'internal error']);
        }
        return response()->json($riddle);
    }

}
