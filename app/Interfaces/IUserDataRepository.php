<?php

namespace App\Interfaces;


interface IUserDataRepository{


    public function getSumAllImpressions($userId);

    public function getSumAllConversions();

    public function getAllUsers();

    public function getAllUserLogs();

    public function getUserById($userId);
   
    public function OrderByImpressions();

    public function OrderByConversions();

    public function OrderByRevenue();

    public function ConvertDataCollection($Object);
}
