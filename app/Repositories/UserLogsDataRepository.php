<?php

namespace App\Repositories;

use App\Interfaces\IJsonFetchRecord;
use App\Interfaces\IUserDataRepository;
use Carbon\Carbon;

class UserLogsDataRepository implements IUserDataRepository{

    public $userDataRepo;

    public function __construct(IJsonFetchRecord $userDataRepo){
        $this->userDataRepo  = $userDataRepo;
    }


    public function getSumAllImpressions($userId){
        
        $totalImpression = $this->userDataRepo->getUserCollection();
    }

    public function getSumAllConversions(){

        $userGenCollection = [];

        foreach($this->userDataRepo->getUserLogsCollection() as $key => $val){

            /**
             * Initializing Array for starting value
             */
            if(!isset($userGenCollection["user".$val['user_id']."key"]['impressions'])){
                $userGenCollection["user".$val['user_id']."key"]['impressions'] = 0;
            }
            if(!isset($userGenCollection["user".$val['user_id']."key"]['conversions'])){
                $userGenCollection["user".$val['user_id']."key"]['conversions'] = 0;
            }  
            if(!isset($userGenCollection["user".$val['user_id']."key"]['revenue'])){
                $userGenCollection["user".$val['user_id']."key"]['revenue'] = 0;
            }
            if(!isset($userGenCollection["user".$val['user_id']."key"]['id'])){
                $userGenCollection["user".$val['user_id']."key"]['id'] = 0;
            }


            /**
             * Calculation Logs for Conversion Type and sum  revenue
             */
            $userGenCollection["user".$val['user_id']."key"]['id'] = $val['user_id'];
            if($val['type'] == 'conversion'){
                $userGenCollection["user".$val['user_id']."key"]['conversions'] += 1;
                $userGenCollection["user".$val['user_id']."key"]['revenue'] += $val['revenue']; 

                $date = Carbon::parse($val['time'])->format('Y-m-d');

                if(!isset($userGenCollection["user".$val['user_id']."key"]['mindate'])){
                    $userGenCollection["user".$val['user_id']."key"]['mindate'] = $date;   //handling Min Date Time
                }else{
                    if($date < $userGenCollection["user".$val['user_id']."key"]['mindate']){
                        $userGenCollection["user".$val['user_id']."key"]['mindate'] =  $date;
                    }
                }

                if(!isset($userGenCollection["user".$val['user_id']."key"]['maxdate'])){
                    $userGenCollection["user".$val['user_id']."key"]['maxdate'] = $date;        //handling Max Date Time
                }else{
                    if($date > $userGenCollection["user".$val['user_id']."key"]['maxdate']){
                        $userGenCollection["user".$val['user_id']."key"]['maxdate'] =  $date;
                    }
                }


                if(!isset($userGenCollection["user".$val['user_id']."key"]['time'][$date])){
                    $userGenCollection["user".$val['user_id']."key"]['time'][$date] = 0;        //Conversion per Day
                }
                $userGenCollection["user".$val['user_id']."key"]['time'][$date] += 1;  
            }
            /**
             * Calculation Logs for Impression Type and Sum Revenue
             */
            else if($val['type'] == 'impression'){
                $userGenCollection["user".$val['user_id']."key"]['impressions'] += 1;
                $userGenCollection["user".$val['user_id']."key"]['revenue'] += $val['revenue']; 
            }

        }

        return $userGenCollection;
    }


    /**
     * @param int UserId
     * return User 
     * getUserById
     */
    public function getUserById($userId){

        $specificUserCollection = $this->userDataRepo->getUserCollection()->firstWhere('id',$userId);
        return $specificUserCollection;
    }

    /**
     * return Collection Users 
     * getAllUsers
     */
    public function getAllUsers(){
        return $this->userDataRepo->getUserCollection();
    }

    /**
     * return Collection UserLogs
     * getAllUserLogs
     */
    public function getAllUserLogs(){
        return $this->userDataRepo->getUserLogsCollection();
    }

    /**
     * return Collection UserLogs
     * OrderByImpressions
     */
    public function OrderByImpressions(){
        $Collections = collect($this->getSumAllConversions())->sortBy('impressions')->toArray();
        return $Collections;
    }

    /**
     * return Collection UserLogs
     * OrderByConversions
     */
    public function OrderByConversions(){
        $Collections = collect($this->getSumAllConversions())->sortBy('conversions')->toArray();
        return $Collections;
    }

    /**
     * return Collection UserLogs
     * OrderByRevenue
     */
    public function OrderByRevenue(){
        $Collections = collect($this->getSumAllConversions())->sortBy('revenue')->all();
        return $Collections;
    }

    /**
     * @param Object SortCollection
     * return Collection Users
     * ConvertDataCollection
     */
    public function ConvertDataCollection($Object){
        $userCollection = [];
            foreach($Object as $val){
                $user = $this->getUserById($val['id']);

                //handling Empty User
                // if(empty($user->toArray())){
                //     $userCollection["user".$val['id']."key"]['id'] = $userCollection["user".$val['id']."key"]['avatar'] = $userCollection["user".$val['id']."key"]['name']  = $userCollection["user".$val['id']."key"]['occupation'] = '';
                // }

                //Collection for Response
                $userCollection["user".$val['id']."key"]['id'] = $user['id'];
                $userCollection["user".$val['id']."key"]['avatar'] = $user['avatar']; 
                $userCollection["user".$val['id']."key"]['name'] = $user['name']; 
                $userCollection["user".$val['id']."key"]['occupation'] = $user['occupation'];  
            }
        return $userCollection;
    }

    
 

}