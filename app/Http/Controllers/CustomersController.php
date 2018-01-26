<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Order;
use App\Entities\Tercero;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DB;
use MP;
use App\Commision;
use App\Logorder;
use App\Product;
use App\Entities\Network;
use Validator;

class CustomersController extends Controller
{

    public function verify_webhook($data, $hmac_header)
    {
        $calculated_hmac = base64_encode(hash_hmac('sha256', $data, 'afc86df7e11dcbe0ab414fa158ac1767', true));
        return hash_equals($hmac_header, $calculated_hmac);
    }

    public function create()
    {
        $input = file_get_contents('php://input');
        $customer = json_decode($input, true);
        $hmac_header = $_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256'];
        $verified = $this->verify_webhook(collect($customer), $hmac_header);
        $resultapi = error_log('Webhook verified: ' . var_export($verified, true));

        if ($resultapi == 'true') {

            $result = Customer::where('customer_id', $customer['id'])
                ->where('email', strtolower($customer['email']))
                ->where('network_id', 1)
                ->get();

            if (count($result) == 0) {

                Customer::createCustomer($customer);

                return response()->json(['status' => 'The resource has been created'], 200);

            } else {

                return response()->json(['status' => 'The resource exist'], 200);
            }
        }
    }
    
}