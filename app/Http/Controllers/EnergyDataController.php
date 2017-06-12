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
        $data = $requestData['data'];

        foreach ($data as $dataRow) {
            $metric = new TenSecondMetric;

            $raspberryPi = RaspberryPi::find($dataRow['raspberry_pi_id']);

            $metric->raspberryPi()->associate($raspberryPi);
            $metric->mode = $dataRow['mode'];
            $metric->usage_now = $dataRow['usage_now'];
            $metric->redelivery_now = $dataRow['redelivery_now'];
            $metric->solar_now = $dataRow['solar_now'];
            $metric->usage_total_high = $dataRow['usage_total_high'];
            $metric->redelivery_total_high = $dataRow['redelivery_total_high'];
            $metric->usage_total_low = $dataRow['usage_total_low'];
            $metric->redelivery_total_low = $dataRow['redelivery_total_low'];
            $metric->solar_total = $dataRow['solar_total'];
            $metric->usage_gas_now = $dataRow['usage_gas_now'];
            $metric->usage_gas_total = $dataRow['usage_gas_total'];

            if(isset($dataRow['created_at']) && isset($dataRow['updated_at'])) {
                $metric->created_at = $dataRow['created_at'];
                $metric->updated_at = $dataRow['updated_at'];
            }

            $success = $metric->save();

            if (!$success) {
                return response()->json([
                    'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                    'message' => 'Energy data could not be saved'
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        return $this->sendInsertJsonResponse(Response::HTTP_CREATED, 'Stored energy data');
    }

    private function sendInsertJsonResponse($status, $message)
    {
        return response()->json(['status' => $status, 'message' => $message], $status);
    }
}