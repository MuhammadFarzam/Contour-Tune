<?php

namespace App\Interfaces;


interface IJsonFetchRecord{

    /**
     * This is the Interface for fetching records through Json file
     * Same will be for Database if we have in future
     */
    public function getUserLogsFromJSON();

    public function getUsersFromJSON();

    public function getUserCollection();

    public function getUserLogsCollection();

   
}
