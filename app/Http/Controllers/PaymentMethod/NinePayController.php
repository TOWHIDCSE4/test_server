<?php

namespace App\Http\Controllers\PaymentMethod;

use App\Helper\Helper;
use App\Helper\TypeFCM;
use App\Http\Controllers\Controller;
use App\Jobs\PushNotificationUserJob;
use App\Models\Order;
use App\Models\OrderRecord;
use App\Models\StatusPaymentHistory;
use Exception;
use Illuminate\Http\Request;


/**
 * @group  Customer/thanh toán onpay
 */
class OnePayController extends Controller
{


    public function index(Request $request)
    {
        return $this->pay($request);
    }

    public function pay()
    {
    }
    public function success()
    {
    }
    public function cancel()
    {
    }
    public function fail()
    {
    }
    public function ipn()
    {
    }
}
