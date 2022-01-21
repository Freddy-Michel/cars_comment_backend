<?php

namespace App\Controller;

use App\Entity\Cars;

class CarsPublishController{
    public function __invoke(Cars $data): Cars{
        $data->setPublished(true);
        return $data;
    }
}