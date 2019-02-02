<?php

namespace App\Http\Controllers;


use App\Models\RaspberryPi;
use App\Models\RaspberryPiError;
use App\Transformers\RaspberryPiTransformer;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


class RaspberryPiController extends Controller
{
    private $raspberryPiTransformer;

    /**
     * EnergyDataController constructor.
     */
    public function __construct(RaspberryPiTransformer $raspberryPiTransformer)
    {
        $this->raspberryPiTransformer = $raspberryPiTransformer;

        parent::__construct();
    }

    /**
     * Store a new Raspberry Pi
     *
     * @param $ipAddress
     */
    public function store(Request $request)
    {
        $requestInput = $request->all();

        // Validation
        $validatorResponse = $this->validateRequest($request, $this->storeRequestValidationRules());

        // Send failed response if validation fails
        if ($validatorResponse !== true) {
            return $this->sendInvalidFieldResponse($validatorResponse);
        }

        $ipAddress = $requestInput['ip_address'];

        if ($ipAddress != $request->getClientIp()) {
            return $this->sendCustomErrorResponse(Response::HTTP_BAD_REQUEST, 'Invalid IP');
        }

        $requestKey = $requestInput['key'];

        if ($requestKey != env('RPI_KEY'))
        {
            return $this->sendCustomErrorResponse(Response::HTTP_BAD_REQUEST, 'Invalid key');
        }

        $raspberryPi = RaspberryPi::where('ip_address', $ipAddress)->first();

        if ($raspberryPi instanceof RaspberryPi) {
            return $this->respondWithItem($raspberryPi, $this->raspberryPiTransformer, Response::HTTP_OK);
        }

        $raspberryPi = RaspberryPi::where('mac_address', $requestInput['mac_address'])->first();

        if ($raspberryPi instanceof RaspberryPi) {
            return $this->respondWithItem($raspberryPi, $this->raspberryPiTransformer, Response::HTTP_OK);
        }

        $raspberryPi = RaspberryPi::create([
                'ip_address' => $ipAddress,
                'mac_address' => $requestInput['mac_address']
            ]
        );

        if ($raspberryPi instanceof RaspberryPi) {
            return $this->respondWithItem($raspberryPi, $this->raspberryPiTransformer, Response::HTTP_CREATED);
        } else {
            return $this->sendCustomErrorResponse(Response::HTTP_INTERNAL_SERVER_ERROR,
                'Raspberry Pi instance could not be saved');
        }
    }

    /**
     * Store Request Validation Rules
     *
     * @return array
     */
    private function storeRequestValidationRules()
    {
        $rules = [
            'ip_address' => 'required',
            'key' => 'required',
        ];

        return $rules;
    }

    public function update(Request $request)
    {
        return $this->sendNotYetImplementedResponse();
    }

    public function reportError(Request $request)
    {
        $requestData = $request->all();
        $data = $requestData['data'];

        foreach ($data as $dataRow) {
            $raspberryPiId = 0;

            if(isset($dataRow['raspberry_pi_id'])) {
                $raspberryPiId = $dataRow['raspberry_pi_id'];
            } else {
                $raspberryPiId = RaspberryPi::where('ip_address', '=', $request->getClientIp())->first()->id;
            }

            if($raspberryPiId < 1 || !is_int($raspberryPiId)) {
                return $this->sendCustomResponse(Response::HTTP_BAD_REQUEST,
                    "Invalid Raspberry Pi id: {$raspberryPiId}");
            }

            $raspberryPi = RaspberryPi::find($raspberryPiId);

            if (!($raspberryPi instanceof RaspberryPi)) {
                return $this->sendNotFoundResponse("Raspberry Pi could not be found with id: {$raspberryPiId}");
            }

            $error = new RaspberryPiError();

            $error->raspberryPi()->associate($raspberryPi);
            $error->message = $dataRow['message'];
            $error->endpoint = $dataRow['endpoint'];
            $error->data_send = $dataRow['data_send'];

            $success = $error->save();

            if (!$success) {
                return response()->json([
                    'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                    'message' => 'Error could not be saved'
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        return $this->sendInsertJsonResponse(Response::HTTP_CREATED, 'Stored error');
    }
}