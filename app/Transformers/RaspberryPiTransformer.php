<?php

namespace App\Transformers;

use App\Models\RaspberryPi;
use Illuminate\Support\Facades\DB;
use League\Fractal\TransformerAbstract;

class RaspberryPiTransformer extends TransformerAbstract
{
    public function transform(RaspberryPi $user)
    {
        $formattedRaspberryPi = [
            'id'                    => $user->id,
            'ip_address'            => $user->ip_address,
            'name'                  => $user->name,
            'createdAt'             => (string) $user->created_at,
            'updatedAt'             => (string) $user->updated_at
        ];

        $results = DB::select("SELECT * FROM oauth_clients WHERE oauth_clients.personal_access_client = 1");
        $formattedRaspberryPi['client_id'] = $results{0}->id;
        $formattedRaspberryPi['client_secret'] = $results{0}->secret;

        return $formattedRaspberryPi;
    }
}