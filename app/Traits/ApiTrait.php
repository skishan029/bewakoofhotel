<?php 
namespace App\Traits;

use Illuminate\Http\JsonResponse;
trait ApiTrait
{
    const HTTP_OK = 200;
    const HTTP_CREATED = 201;
    const HTTP_BAD_REQUEST = 400;
    const HTTP_UNAUTHORIZED = 401;
    const HTTP_FORBIDDEN = 403;
    const HTTP_NOT_FOUND = 404;
    const HTTP_INTERNAL_ERROR = 500;
    const HTTP_VALIDATION_ERROR = 422;
    
    /**
     * Standard API Response format.
     *
     * @param int $status        HTTP status code.
     * @param string $message    Response message, can be localized.
     * @param mixed $data        Data payload, can be null.
     * @param mixed $error       Error details, array or string. If string, it will be wrapped.
     * @param array $meta        Additional metadata for pagination, etc. (optional).
     * @param array $info        Additional info fields (optional).
     * @return \Illuminate\Http\JsonResponse
     * 
     * @example
     * {
     *   "status": 200,
     *   "message": "Success",
     *   "data": {...},
     *   "meta": {...},
     *   "error": [{"field": "field_name", "errors": ["error_detail"]}],
     *   "info": {...}
     * }
     */
    public function apiResponse(
        int $status, 
        string $message = '', 
        $data = null, 
        $error = [], 
        array $meta = [], 
        array $info = []
    ) : \Illuminate\Http\JsonResponse {
        // Check if $error is an array or string, and format accordingly
        $formattedError = is_array($error) 
            ? $error 
            : [
                [
                    'field'  => 'generic',
                    'errors' => [$error],
                ],
            ];

        // Handle pagination meta if data is paginated
        if ($data instanceof \Illuminate\Pagination\LengthAwarePaginator) {
            $meta = array_merge($meta, [
                'total' => $data->total(),
                'per_page' => $data->perPage(),
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
            ]);
        }
        
        // Return structured JSON response
        return response()->json([
            'status'  => $status,
            'message' => __($message), // Localized message
            'data'    => $data,
            'meta'    => $meta,        // Optional meta information
            'error'   => $formattedError,
            'info'    => $info,        // Optional additional info
        ], $status);
    }

    /**
     * 200 OK
     */
    public function apiSuccess(
        string $message = 'Success', 
        $data = null, 
        array $meta = [], 
        array $info = []
    ) : JsonResponse {
        return $this->apiResponse(self::HTTP_OK, $message, $data, [], $meta, $info);
    }

    /**
     * 200 OK (alias for simple success without data)
    */
    public function apiOk(string $message = 'OK') : JsonResponse {
        return $this->apiSuccess($message);
    }

    /**
     * 201 Created
     */
    public function apiCreated(
        string $message = 'Created successfully', 
        $data = null, 
        array $meta = [], 
        array $info = []
    ) : JsonResponse {
        return $this->apiResponse(self::HTTP_CREATED, $message, $data, [], $meta, $info);
    }

    /**
     * 400/Other Error
     */
    public function apiError(
        string $message = 'Bad request', 
        $error = [],
        int $status = self::HTTP_BAD_REQUEST, 
    ) : JsonResponse {
        return $this->apiResponse($status, $message, null, $error, [], []);
    }

     /**
     * 422 Validation Error
     */
    public function apiValidationError(
        string $message = 'Validation failed', 
        $data = null, 
        array $meta = [], 
        array $info = []
    ) : JsonResponse {
        return $this->apiResponse(self::HTTP_VALIDATION_ERROR, $message, $data, [], $meta, $info);
    }

     /**
     * 404 Not Found
     */
    public function apiNotFound(
        string $message = 'Resource not found', 
        array $info = []
    ) : JsonResponse {
        return $this->apiResponse(self::HTTP_NOT_FOUND, $message, null, [], [], $info);
    }
}
