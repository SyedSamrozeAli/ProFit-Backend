<?php

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Contracts\Encryption\EncryptException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Stripe\Exception\ApiErrorException;

if (!function_exists('errorResponse')) {

    /**
     * errorResponse
     *
     * @param mixed $error
     * @param int $code
     * @param array $errorMessages
     * @return \Illuminate\Http\JsonResponse
     */
    function errorResponse($error, $code = 401, $errorMessages = [])
    {
        $statusCode = $code == 0 ? 401 : $code;
        $response = [
            'success' => false,
            'status_code' => $statusCode,
            'message' => is_array($error) ? $error : [$error],
            'data' => null
        ];

        return response()->json($response, $statusCode);
    }



    // function errorResponse($error, $code = 401, $errorMessages = [])
    // {
    //     // Default status code if none is provided
    //     $statusCode = $code == 0 ? 401 : $code;

    //     // Define SQL error messages based on SQLSTATE codes
    //     $sqlErrors = [
    //         '42000' => 'SQL syntax error. Please check the query structure.',
    //         '23000' => [
    //             'duplicate' => 'Duplicate data. The value for the [column_name] field already exists in the database.',
    //             'not_null' => 'Missing required field. The [column_name] field cannot be empty or null.'
    //         ],
    //         '42S22' => 'Column not found in the table. Please check your column names.',
    //         '42S02' => 'Table not found in the database. Ensure the table exists.',
    //         'HY000' => 'Database connection error. Please check your credentials or server status.',
    //         '22001' => 'Data truncation. The data exceeds the column size or type.',
    //         '1205' => 'Lock wait timeout. The query took too long to execute.',
    //         '1040' => 'Too many database connections. The server has exceeded the allowed connections.'
    //     ];

    //     // Define non-SQL error messages
    //     $nonSqlErrors = [
    //         'authentication' => [
    //             'invalid_credentials' => 'Invalid username or password. Please try again.',
    //             'token_expired' => 'Your session has expired. Please log in again.',
    //             'unauthorized' => 'You are not authorized to perform this action.'
    //         ],
    //         'authorization' => [
    //             'forbidden' => 'You do not have permission to perform this action.',
    //             'access_denied' => 'Access denied. Please contact the administrator.'
    //         ],
    //         'file' => [
    //             'file_not_found' => 'The requested file [file_name] could not be found.',
    //             'file_upload_failed' => 'An error occurred while uploading the file. Please try again.',
    //             'unsupported_file_type' => 'The file type [file_type] is not supported. Please upload a [supported_type].'
    //         ],
    //         'rate_limiting' => [
    //             'too_many_requests' => 'You have exceeded the maximum number of requests. Please try again later.'
    //         ],
    //         'general' => [
    //             'server_error' => 'Something went wrong on our end. Please try again later.',
    //             'unexpected_error' => 'An unexpected error occurred. Please try again later.',
    //             'not_found' => 'The requested resource could not be found.'
    //         ],
    //         'api' => [
    //             'invalid_api_key' => 'Invalid API key. Please check your credentials and try again.',
    //             'api_rate_limit' => 'API rate limit exceeded. Please try again after [time].'
    //         ]
    //     ];

    //     // Prepare the error response structure
    //     $response = [
    //         'success' => false,
    //         'status_code' => $statusCode,
    //         'message' => '',
    //         'data' => null,
    //     ];

    //     // Check for SQL error based on SQLSTATE code in error string
    //     if (preg_match('/SQLSTATE\[(\w+)\]/', $error, $matches)) {
    //         $sqlCode = $matches[1];
    //         // Handle specific SQL error codes
    //         if (isset($sqlErrors[$sqlCode])) {
    //             // If it's a 23000 error (integrity constraint violation), check for duplicate or not null
    //             if ($sqlCode == '23000') {
    //                 // Look for specific violation details (duplicate or not null)
    //                 if (strpos($error, 'Duplicate entry') !== false) {
    //                     $response['message'] = str_replace('[column_name]', 'the specified column', $sqlErrors['23000']['duplicate']);
    //                 } elseif (strpos($error, 'Cannot be null') !== false) {
    //                     $response['message'] = str_replace('[column_name]', 'the specified column', $sqlErrors['23000']['not_null']);
    //                 } else {
    //                     $response['message'] = $sqlErrors['23000']['duplicate']; // Default to duplicate error message
    //                 }
    //             } else {
    //                 $response['message'] = $sqlErrors[$sqlCode];
    //             }
    //         } else {
    //             // Send a generic SQL error message if the code isn't in the predefined list
    //             $response['message'] = 'An unknown SQL error occurred.';
    //         }
    //     } else {
    //         // For non-SQL errors, use the non-SQL error messages
    //         if (isset($nonSqlErrors[$error])) {
    //             $response['message'] = $nonSqlErrors[$error];
    //         } else {
    //             // For unknown non-SQL errors, send the generic error message
    //             $response['message'] = is_array($error) ? implode(', ', $error) : $error;
    //         }
    //     }

    //     // Optionally include the error messages if provided (for validation errors or additional details)
    //     if (!empty($errorMessages)) {
    //         $response['data'] = $errorMessages;
    //     }

    //     // Return the error response with only one message
    //     return response()->json($response, $statusCode);
    // }
}







if (!function_exists('successResponse')) {
    /**
     * successResponse
     *
     * @param string $message
     * @param mixed $result
     * @param bool $paginate
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */

    function successResponse($message, $result = [], $paginate = false, $code = 200)
    {
        $response = [
            'success' => true,
            'status_code' => $code,
            'message' => [$message],
            'data' => $result
        ];

        // Handle pagination
        if ($paginate && !empty($result)) {
            $totalRecords = DB::table('trainers')->count(); // Total records in trainers table
            $limit = request()->query('limit', 10); // Default limit
            $page = request()->query('page', 1); // Default page
            $totalPages = ceil($totalRecords / $limit); // Total pages

            $paginationData = [
                'current_page' => (int) $page,
                'total_records' => $totalRecords,
                'total_pages' => $totalPages,
                'limit' => (int) $limit
            ];

            $response['pagination'] = $paginationData;
        }

        return response()->json($response, $code);
    }

}

if (!function_exists('getAuthenticatedUser')) {

    function getAuthenticatedUser()
    {
        return auth('api')->user();
    }
}

if (!function_exists('paginate')) {
    /**
     * paginate
     *
     * @param \Illuminate\Contracts\Pagination\LengthAwarePaginator $data
     * @return array|null
     */
    function paginate($data)
    {
        if ($data != null) {
            $paginationArray = [
                'list' => $data->items(),
                'pagination' => [
                    'total' => $data->total(),
                    'current' => $data->currentPage(),
                    'first' => 1,
                    'last' => $data->lastPage(),
                    'previous' => $data->currentPage() > 1 ? $data->currentPage() - 1 : null,
                    'next' => $data->hasMorePages() ? $data->currentPage() + 1 : null,
                    'pages' => range(1, $data->lastPage()),
                    'from' => $data->firstItem(),
                    'to' => $data->lastItem()
                ]
            ];

            return $paginationArray;
        }

        return null;
    }
}

if (!function_exists('handleException')) {
    /**
     * handleException
     *
     * @param \Exception $e
     * @return \Illuminate\Http\JsonResponse
     */
    function handleException(\Exception $e)
    {
        // Log the exception
        Log::error('An exception occurred: ' . $e);

        // Check specific exceptions and return appropriate error responses
        if ($e instanceof QueryException) {
            return errorResponse('Something went wrong with the database query.', 400);
        }

        if ($e instanceof ModelNotFoundException) {
            return errorResponse('The requested data was not found.', 404);
        }

        if ($e instanceof TokenInvalidException) {
            return errorResponse('Token is Invalid', 401);
        }

        if ($e instanceof TokenExpiredException) {
            return errorResponse('Token is Expired', 401);
        }

        if ($e instanceof JWTException) {
            return errorResponse('Authorization Token not found', 401);
        }

        if ($e instanceof ApiErrorException) {
            return errorResponse($e->getMessage(), 402);
        }

        // For other exceptions, return a generic error response
        return errorResponse('An unexpected error occurred.', $e->getCode());
    }
}




if (!function_exists('getCurrentUserId')) {

    function getCurrentUserId()
    {
        return auth()->user()->id;
    }
}

