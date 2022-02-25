<?php

namespace App\Http\Controllers;

use App\Interfaces\IUserDataRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserDashboardController extends Controller
{

    public $userRepo;

    public function __construct(IUserDataRepository $userDataRepo)
    {
        $this->userRepo = $userDataRepo;


    }

    public function getUserInformation(){
        return view('pages.dashboard',['data' => ['usersCollection' => $this->userRepo->getAllUsers()->toArray(), 'statsCollection' => $this->userRepo->getSumAllConversions()]]);
    }

    public function getUserFilterData(Request $req){
    
        $filterKey = $req->get('filterKey',null);

        if($filterKey == "name"){
            $userCollection = [];
            foreach($this->userRepo->getAllUsers()->sortBy('name') as $key => $val){
                $userCollection["user".$val['id']."key"]['id'] = $val['id'];
                $userCollection["user".$val['id']."key"]['avatar'] = $val['avatar']; 
                $userCollection["user".$val['id']."key"]['name'] = $val['name']; 
                $userCollection["user".$val['id']."key"]['occupation'] = $val['occupation'];  
            }
    
            return response()->json(['data' => ['usersCollection' => $userCollection, 'statsCollection' => $this->userRepo->getSumAllConversions()]]);
        }
        else if($filterKey == "impressions"){
            $userCollection = $this->userRepo->ConvertDataCollection($this->userRepo->OrderByImpressions());
            return response()->json(['data' => ['usersCollection' => $userCollection, 'statsCollection' => $this->userRepo->OrderByImpressions()]]);
        }
        else if($filterKey == "conversions"){
            $userCollection = $this->userRepo->ConvertDataCollection($this->userRepo->OrderByConversions());
            return response()->json(['data' => ['usersCollection' => $userCollection, 'statsCollection' => $this->userRepo->OrderByConversions()]]);
        }
        else if($filterKey == "revenue"){
            $userCollection = $this->userRepo->ConvertDataCollection($this->userRepo->OrderByRevenue());
            return response()->json(['data' => ['usersCollection' => $userCollection, 'statsCollection' => $this->userRepo->OrderByRevenue()]]);
        }
       
    }

}
