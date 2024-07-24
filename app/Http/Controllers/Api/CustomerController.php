<?php
/**
 * Created by PhpStorm.
 * User: Sara
 * Date: 6/28/2023
 * Time: 12:05 AM
 */

namespace App\Http\Controllers\Api;


use App\Helpers\ApiResponseHandler;
use App\Helpers\AppException;
use App\Helpers\Helper;
use App\Models\Closet;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerController
{
    /**
     * @OA\Get(
     *
     *     path="/v1/customer",
     *     tags={"Customer"},
     *     summary="Get meta data content",
     *     operationId="getCustomer",
     *
     *      @OA\Response(response=200, description="messages.general.success"),
     *      @OA\Response(response=422, description="Unprocessable Entity"),
     *      @OA\Response(response=400, description="messages.general.failed"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     *
     *     security={
     *          {"user_access_token": {}}
     *     }
     * )
     */

    public function getCustomer(Request $request)
    {
        try {
            $requestData = $request->all();

            $customerId = $requestData['customer_id'];
            $customer = Customer::findById($customerId);
            $response = [
                'customer' => [
                    'first_name' => optional($customer)->first_name,
                    'last_name' => optional($customer)->last_name,
                    'email' => optional($customer)->email,
                    'country_code' => optional($customer)->country_code,
                    'phone_number' => optional($customer)->phone_number,
                    'identifier' => optional($customer)->identifier,
                ],
            ];
            return ApiResponseHandler::success($response, __('messages.general.success'));
        } catch (\Exception $e) {
            AppException::log($e);
            return ApiResponseHandler::failure(__('messages.general.failed'), $e->getMessage());
        }
    }

}