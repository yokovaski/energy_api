<?php
/**
 * Created by PhpStorm.
 * User: erwin
 * Date: 25-5-17
 * Time: 22:51
 */

namespace App\Http\Controllers;


use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use League\Fractal\Manager;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;
use Symfony\Component\HttpFoundation\Response;

trait ResponseTrait
{
    /**
     * Fractal manager instance
     *
     * @var Manager
     */
    protected $fractal;

    /**
     * Set fractal Manager instance
     *
     * @param Manager $fractal
     * @return void
     */
    public function setFractal(Manager $fractal)
    {
        $this->fractal = $fractal;
    }

    /**
     * Send custom data response
     *
     * @param $status
     * @param $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendCustomResponse($status, $message)
    {
        return response()->json(['status' => $status, 'message' => $message], $status);
    }

    /**
     * Wrapper for error responses
     *
     * @param $response
     * @return \Illuminate\Http\JsonResponse
     */
    private function sendErrorResponse($response)
    {
        return response()->json(['error' => $response], $response['status']);
    }

    /**
     * Send a custom error response
     *
     * @param $status
     * @param $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendCustomErrorResponse($status, $message)
    {
        $response = ['status' => $status, 'message' => $message];
        return $this->sendErrorResponse($response);
    }

    /**
     * Send this response when api user provides not existing fields
     *
     * @param $errors
     * @return mixed
     */
    public function sendUnknownFieldResponse($errors)
    {
        $response = ['status' => Response::HTTP_BAD_REQUEST, 'unknown_fields' => $errors];
        return $this->sendErrorResponse($response);
    }

    /**
     * Send this response when a user provides not existing filter
     *
     * @param $errors
     * @return mixed
     */
    public function sendInvalidFilterResponse($errors)
    {
        $response = ['status' => Response::HTTP_BAD_REQUEST, 'invalid_filters' => $errors];
        return $this->sendErrorResponse($response);
    }

    /**
     * Send this response when a user does not use JSON as content-type
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendInvalidContentTypeResponse()
    {
        $response = ['status' => Response::HTTP_BAD_REQUEST, 'message' => 'Use of JSON as content-type is mandatory'];
        return $this->sendErrorResponse($response);
    }

    /**
     * Send this response when a user provide incorrect data type for the field
     *
     * @param $errors
     * @return mixed
     */
    public function sendInvalidFieldResponse($errors)
    {
        $response = ['status' => Response::HTTP_FORBIDDEN, 'invalid_fields' => $errors];
        return $this->sendErrorResponse($response);
    }

    /**
     * Send this response when a user tries to execute a forbidden action
     *
     * @return string
     */
    public function sendForbiddenResponse()
    {
        $response = ['status' => Response::HTTP_FORBIDDEN, 'message' => 'Forbidden'];
        return $this->sendErrorResponse($response);
    }

    public function sendRejectedResponse()
    {
        $response = ['status' => Response::HTTP_FORBIDDEN, 'message' => 'Request has been rejected'];
        return $this->sendErrorResponse($response);
    }

    /**
     * Send this response when a resource is not found
     *
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendNotFoundResponse($message = '')
    {
        if (empty($message)) {
            $message = 'The requested resource was not found';
        }

        $response = ['status' => Response::HTTP_NOT_FOUND, 'message' => $message];
        return $this->sendErrorResponse($response);
    }

    /**
     * Send this response when a user tries to access a feature that is not yet implemented
     *
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendNotYetImplementedResponse($message = '')
    {
        if (empty($message)) {
            $message = 'This feature is not yet implemented';
        }

        $response = ['status' => Response::HTTP_NOT_IMPLEMENTED, 'message' => $message];
        return $this->sendErrorResponse($response);
    }

    /**
     * Send empty data response
     *
     * @return string
     */
    public function sendEmptyDataResponse()
    {
        return response()->json(['data' => new \StdClass()]);
    }

    /**
     * Return collection response from the application
     *
     * @param array|LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection $collection
     * @param \Closure|TransformerAbstract $callback
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithCollection($collection, $callback, $statusCode = null)
    {
        $resource = new Collection($collection, $callback);

        //set empty data pagination
        if (empty($collection)) {
            $collection = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);
            $resource = new Collection($collection, $callback);
        }
        $resource->setPaginator(new IlluminatePaginatorAdapter($collection));

        $rootScope = $this->fractal->createData($resource);

        return $this->respondWithArray($rootScope->toArray(), $statusCode);
    }

    /**
     * Return single item response from the application
     *
     * @param Model $item
     * @param \Closure|TransformerAbstract $callback
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithItem($item, $callback, $statusCode = null)
    {

        $resource = new Item($item, $callback);
        $rootScope = $this->fractal->createData($resource);

        return $this->respondWithArray($rootScope->toArray(), $statusCode);
    }

    /**
     * Return a json response from the application
     *
     * @param array $array
     * @param array $headers
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithArray(array $array, $statusCode, array $headers = [])
    {
        if (empty($statusCode)) {
            $statusCode = Response::HTTP_OK;
        }

        return response()->json($array, $statusCode, $headers);
    }
}