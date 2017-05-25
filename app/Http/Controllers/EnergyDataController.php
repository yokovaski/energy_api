<?php

namespace App\Http\Controllers;


use App\Models\TenSecondMetric;
use Illuminate\Http\Request;

class EnergyDataController extends Controller
{

    public function insertEnergyData(Request $request)
    {
        $requestData = $request->all();
        $requestData = $requestData['data'];

        $metric = new TenSecondMetric;

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

        return $this->sendJsonResponse(200, 'received data', $requestData);
    }

    private function sendJsonResponse($status, $message, $data = [])
    {
        return response()->json(['status' => $status, 'message' => $message, 'data' => $data], $status);
    }
}