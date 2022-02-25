<?php

namespace App\Repositories;

use App\Interfaces\IJsonFetchRecord;
use Illuminate\Support\Facades\File;

class JsonDataRepository implements IJsonFetchRecord{

    public $userCollections;
    public $userLogsCollections;

    public function __construct()
    {
        $this->userCollections = $this->getUsersFromJSON();
        $this->userLogsCollections = $this->getUserLogsFromJSON();
    }

    public function getUsersFromJSON(){

        $filename = "users";
        $path = storage_path() . "//app//public//${filename}.json"; 
        $Collections = [];
        if(File::exists($path)){

            $json = json_decode(file_get_contents($path), true);

            $Collections = collect($json);
        }
        return $Collections;
    }

    public function getUserLogsFromJSON(){

        $filename = "logs";
        $path = storage_path() . "//app//public//${filename}.json"; 
        $Collections = [];
        if(File::exists($path)){

            $json = json_decode(file_get_contents($path), true);

            $Collections = collect($json);

        }

        return $Collections;
    }

    public function getUserCollection(){
        return $this->userCollections;
    }

    public function getUserLogsCollection(){
        return $this->userLogsCollections;
    }
}