<?php

namespace App\Http\Controllers;


use App\Models\RaspberryPi;
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

        $raspberryPi = RaspberryPi::create([
                'ip_address' => $ipAddress,
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
}