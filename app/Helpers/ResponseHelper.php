<?php


namespace App\Helpers;


final class ResponseHelper
{
    const productionEnv = 'production';
    const statusKey = 'status';
    const errorCodeKey = 'error';
    const success = 'success';
    const error = 'error';
    const successCode = 200;
    const createdCode = 201;
    const errorCode = 409;

    /**
     * Return classic success response
     *
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    public static function successResponse(array $data = [])
    {
        $response = [self::statusKey => self::success];
        if (!empty($data)) {
            $response = array_merge($response, $data);
        }

        return response()->json($response, self::successCode);
    }

    /**
     * Return classic created response
     *
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    public static function createdResponse(array $data = [])
    {
        $response = [self::statusKey => self::success];
        if (!empty($data)) {
            $response = array_merge($response, $data);
        }
        return response()->json($response, self::createdCode);
    }

    /**
     * Return classic created response
     *
     * @param integer $errorCode ErrorCode declared in ErrorsCodesHelper
     * @param mixed $debugData
     * @return \Illuminate\Http\JsonResponse
     */
    public static function errorResponse($errorCode, $debugData = [])
    {
        if (env('APP_ENV', self::productionEnv) === self::productionEnv) {
            return response()->json([self::errorCodeKey => $errorCode], self::errorCode);
        }

        return response()->json([
            self::errorCodeKey => $errorCode,
            'debugData' => $debugData
        ], self::errorCode);
    }
}