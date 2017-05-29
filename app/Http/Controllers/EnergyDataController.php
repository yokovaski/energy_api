<?php

namespace App\Http\Controllers;


use App\Models\RaspberryPi;
use App\Models\TenSecondMetric;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnergyDataController extends Controller
{
    /**
     * EnergyDataController constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Insert new energy data
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $requestData = $request->all();
        $requestData = $requestData['data'];

        $metric = new TenSecondMetric;

        $raspberryPi = RaspberryPi::find($requestData['raspberry_pi_id']);

        $metric->raspberryPi()->associate($raspberryPi);
        $metric->mode = $requestData['mode'];
        $metric->usage_now = $requestData['usage_now'];
        $metric->redelivery_now = $requestData['redelivery_now'];
        $metric->solar_now = $requestData['solar_now'];
        $metric->usage_total_high = $requestData['usage_total_high'];
        $metric->redelivery_total_high = $requestData['redelivery_total_high'];
        $metric->usage_total_low = $requestData['usage_total_low'];
        $metric->redelivery_total_low = $requestData['redelivery_total_low'];
        $metric->solar_total = $requestData['solar_total'];
        $metric->usage_gas_now = $requestData['usage_gas_now'];
        $metric->usage_gas_total = $requestData['usage_gas_total'];

        $metric->save();

        return $this->sendInsertJsonResponse(Response::HTTP_CREATED, 'received data', $requestData);
    }

    private function sendInsertJsonResponse($status, $message, $data = [])
    {
        return response()->json(['status' => $status, 'message' => $message, 'data' => $data], $status);
    }
}