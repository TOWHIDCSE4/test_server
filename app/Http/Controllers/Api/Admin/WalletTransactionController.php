<?php

namespace App\Http\Controllers\Api\Admin;

use App\Helper\Helper;
use App\Helper\ParamUtils;
use App\Helper\RenterType;
use App\Helper\ResponseUtils;
use App\Helper\StatusContractDefineCode;
use App\Http\Controllers\Controller;
use App\Models\MsgCode;
use App\Models\WalletTransaction;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WalletTransactionController extends Controller
{
    
    public function createWalletDeposit(Request $request)
    {
        if ($request->deposit_money == null || empty($request->deposit_money)) {
            return ResponseUtils::json([
                'code' => Response::HTTP_BAD_REQUEST,
                'success' => false,
                'msg_code' => MsgCode::DEPOSIT_MONEY_IS_REQUIRED[0],
                'msg' => MsgCode::DEPOSIT_MONEY_IS_REQUIRED[1],
            ]);
        }

        DB::beginTransaction();
        try {
            $wallet_transaction_created = WalletTransaction::create([
                "user_id" => $request->user->id,
                "account_number" => $request->account_number,
                "bank_account_holder_name" => $request->bank_account_holder_name,
                "bank_name" => $request->bank_name,
                "deposit_money" => $request->deposit_money,
                "deposit_trading_code" => Helper::generateTransactionID(),
                "deposit_date_time" => Helper::getTimeNowString(),
                "deposit_content" => $request->deposit_content ?? null,
            ]);


            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }

        return ResponseUtils::json([
            'code' => Response::HTTP_OK,
            'success' => true,
            'msg_code' => MsgCode::SUCCESS[0],
            'msg' => MsgCode::SUCCESS[1],
            'data' => $wallet_transaction_created,
        ]);
    }

    public function createWalletWithdraws(Request $request)
    {
        if ($request->withdraw_money == null || empty($request->withdraw_money)) {
            return ResponseUtils::json([
                'code' => Response::HTTP_BAD_REQUEST,
                'success' => false,
                'msg_code' => MsgCode::WITHDRAW_MONEY_IS_REQUIRED[0],
                'msg' => MsgCode::WITHDRAW_MONEY_IS_REQUIRED[1],
            ]);
        }

        DB::beginTransaction();
        try {
            $wallet_transaction_created = WalletTransaction::create([
                "user_id" => $request->user->id,
                "account_number" => $request->account_number,
                "bank_account_holder_name" => $request->bank_account_holder_name,
                "bank_name" => $request->bank_name,

                "withdraw_money" => $request->withdraw_money,
                "withdraw_trading_code" => Helper::generateTransactionID(),
                "withdraw_date_time" => Helper::getTimeNowString(),
                "withdraw_content" => $request->withdraw_content ?? null,
            ]);


            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }

        return ResponseUtils::json([
            'code' => Response::HTTP_OK,
            'success' => true,
            'msg_code' => MsgCode::SUCCESS[0],
            'msg' => MsgCode::SUCCESS[1],
            'data' => $wallet_transaction_created,
        ]);
    }

}
