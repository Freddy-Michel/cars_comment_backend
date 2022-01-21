<?php

namespace App\Controller;

use App\Repository\CarsRepository;
use Symfony\Component\HttpFoundation\Request;

class CarCountController
{

    public function __construct(private CarsRepository $repos){

    }
    
    public function __invoke(Request $request): int
    {
        $publisedQuery = $request->get('published');

        // dd($publisedQuery);
        $condition = [];

        if($publisedQuery !== null){
            $condition = ['published' => $publisedQuery == 1 ? true : false];
        }
        return $this->repos->count($condition);
    }    
}
