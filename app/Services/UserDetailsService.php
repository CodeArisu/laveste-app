<?php

namespace App\Services;

use App\Models\Auth\UserDetail;
use Illuminate\Support\Facades\DB;


class UserDetailsService 
{   
    public function userDetailsRequest($request)
    {   
        try {
            return DB::transaction( function () use ($request) {
                $userDetailsData = $this->createUserDetails($request);

                if (!$userDetailsData) throw new \RuntimeException('No user details found');

                return $userDetailsData;
            });
        } catch (\Exception $e)
        {
            throw new \Exception($e->getMessage());
        }
    }

    private function filterUserDetails(array $request)
    {
        return [
            'first_name' => $request['first_name'],
            'last_name' => $request['last_name'],
            'address' => $request['address'],
            'contact' => $request['contact'],
        ];
    }
    
    private function createUserDetails($request)
    {   
        $filteredData = $this->filterUserDetails($request);
        
        $userDetails = $this->handleUserDetails([
            'first_name' => $filteredData['first_name'],
            'last_name' => $filteredData['last_name'],
            'address' => $filteredData['address'],
            'contact' => $filteredData['contact'],
        ]);

        return $userDetails;
    }

    private function handleUserDetails(array $data)
    {    
        return UserDetail::updateOrCreate([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'address' => $data['address'],
            'contact' => $data['contact'],
        ]);
    }
}