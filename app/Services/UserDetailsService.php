<?php

namespace App\Services;

use App\Models\Auth\UserDetail;
use Illuminate\Support\Facades\DB;


class UserDetailsService
{
    public function userDetailsRequest($validated, $user)
    {
        try {
            return DB::transaction(function () use ($validated, $user) {

                $userDetailsData = $this->createUserDetails($validated, $user);

                if (!$userDetailsData) throw new \RuntimeException('No user details found');

                return $userDetailsData;
            });
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    private function filterUserDetails(array $request, $user)
    {
        return [
            'user_id' => $user->id,
            'first_name' => $request['first_name'],
            'last_name' => $request['last_name'],
            'address' => $request['address'],
            'contact' => $request['contact'],
        ];
    }

    private function createUserDetails($validated, $user)
    {
        $filteredData = $this->filterUserDetails($validated, $user);

        $userDetails = $this->handleUserDetails([
            'user_id' => $filteredData['user_id'],
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
            'user_id' => $data['user_id'],
        ]);
    }
}
