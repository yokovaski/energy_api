<?php
/**
 * Created by PhpStorm.
 * User: erwin
 * Date: 27-1-19
 * Time: 17:15
 */

namespace App\Http\Controllers;


use App\Models\RaspberryPi;
use App\Models\TenSecondMetric;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnergyController extends Controller
{
    /**
     * EnergyController constructor.
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

        $raspberryPiId = $requestData['raspberry_pi_id'];

        $dataPresent = $request->has('data');

        if(!$dataPresent)
            return $this->sendJsonResponse(Response::HTTP_BAD_REQUEST, 'Field data is missing');

        $data = $requestData['data'];

        $raspberryPi = RaspberryPi::find($raspberryPiId);

        if (!($raspberryPi instanceof RaspberryPi)) {
            return $this->sendNotFoundResponse("Raspberry Pi could not be found with id: {$raspberryPiId}");
        }

//        // In order to tackle a bug of python script that wraps the array in another array
//        if (!array_key_exists('raspberry_pi_id', $data)) {
//            $data = $data[0];
//        }

        foreach ($data as $dataRow) {
            $metric = new TenSecondMetric;

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
            } elseif (isset($dataRow['unix_timestamp'])) {
                $createdAt = Carbon::createFromTimestamp($dataRow['unix_timestamp'], env("APP_TIMEZONE"))->toDateTimeString();
                $metric->created_at = $createdAt;
                $metric->updated_at = $createdAt;
            }

            $success = $metric->save();

            if (!$success) {
                return response()->json([
                    'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                    'message' => 'Energy data could not be saved'
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        return $this->sendJsonResponse(Response::HTTP_CREATED, 'Stored energy data');
    }

    private function sendJsonResponse($status, $message)
    {
        return response()->json(['status' => $status, 'message' => $message], $status);
    }

}