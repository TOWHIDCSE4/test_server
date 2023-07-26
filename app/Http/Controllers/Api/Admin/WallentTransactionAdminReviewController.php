<?php

namespace App\Http\Controllers\Api\Admin;

use App\Helper\Helper;
use App\Helper\ParamUtils;
use App\Helper\RenterType;
use App\Helper\ResponseUtils;
use App\Helper\StatusContractDefineCode;
use App\Http\Controllers\Controller;
use App\Models\MsgCode;
use App\Models\WallentTransactionAdminReview;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WalletTransactionController extends Controller
{

     public function getAllBankList()  {
        
    }

    //ComfirmStatusPaymentAdmin
    public function comfirmStatusPaymentAdmin()
    {
        $deposits = WallentTransactionAdminReview::select(
            'user_id',
            'deposit_money',
            'account_number',
            'bank_account_holder_name',
            'bank_name',
            'deposit_trading_code',
            'deposit_date_time',
            'deposit_content'
        )->get();
        return response()->json([
            'code' => 200,
            'success' => true,
            'msg_code' => MsgCode::SUCCESS[0],
            'msg' => MsgCode::SUCCESS[1],
            'data' => $deposits,
        ], 200);
    }

    public function getAdminReview()
    {
        $deposits = WallentTransactionAdminReview::select(
            'user_id',
            'deposit_money',
            'account_number',
            'bank_account_holder_name',
            'bank_name',
            'deposit_trading_code',
            'deposit_date_time',
            'deposit_content'
        )->get();
        return response()->json([
            'code' => 200,
            'success' => true,
            'msg_code' => MsgCode::SUCCESS[0],
            'msg' => MsgCode::SUCCESS[1],
            'data' => $deposits,
        ], 200);
    }
}
