<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\RaspberryPi;
use Illuminate\Http\Request;

class RpiKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $requestData = $request->all();
        $key_is_present = $request->has('rpi_key') ? true: false;

        if(!$key_is_present)
            return response()->json((['status' => 401, 'message' => 'Unauthorized']), 401);

        $key = $requestData["rpi_key"];
        $id = RaspberryPi::where('rpi_key', $key)->first()["id"];

        if(empty($id))
            return response()->json((['status' => 401, 'message' => 'Unauthorized']), 401);

        $request->request->add(['raspberry_pi_id' => $id]);

        return $next($request);
    }
}
