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
use App\Models\WalletTransactionBankList;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WalletTransactionController extends Controller
{
    //getAllWalletDeposit
    public function  getAllWalletDeposit()
    {
        $deposits = WalletTransaction::select(
            'user_id',
            'deposit_money',
            'account_number',
            'bank_account_holder_name',
            'bank_name',
            'deposit_trading_code',
            'deposit_date_time',
            'deposit_content'
        )->paginate(10);
        return response()->json([
            'code' => 200,
            'success' => true,
            'msg_code' => MsgCode::SUCCESS[0],
            'msg' => MsgCode::SUCCESS[1],
            'data' => $deposits,
        ], 200);
    }

    ///createWalletDeposit
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

    /// editWalletDeposit
    public function  editWalletDeposit(Request $request)
    {

        if ($request->walletId == null || empty($request->deposit_money)) {
            return ResponseUtils::json([
                'code' => Response::HTTP_BAD_REQUEST,
                'success' => false,
                'msg_code' => MsgCode::WITHDRAW_MONEY_IS_REQUIRED[0],
                'msg' => MsgCode::WITHDRAW_MONEY_IS_REQUIRED[1],
            ]);
        }

        $this->validate($request, [
            'deposit_money' => 'required',
            'bank_name' => 'required',
        ]);

        $wallet = WalletTransaction::findOrFail($request->id);
        if ($wallet == null || empty($wallet)) {
            return response()->json('Wallet not found');
        }

        DB::beginTransaction();
        try {
            $response = $wallet->update([
                "account_number" => $request->account_number,
                "bank_account_holder_name" => $request->bank_account_holder_name,
                "bank_name" => $request->bank_name,
                "deposit_money" => $request->deposit_money,
                "deposit_trading_code" => Helper::generateTransactionID(),
                "deposit_date_time" => Helper::getTimeNowString(),
                "deposit_content" => $request->deposit_content ?? null,
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }


        return ResponseUtils::json([
            'code' => Response::HTTP_OK,
            'success' => true,
            'msg_code' => MsgCode::SUCCESS[0],
            'msg' => MsgCode::SUCCESS[1],
            'data' => $wallet,
        ]);
    }

    ///getAllWalletWithdraws
    public function  getAllWalletWithdraws()
    {
        $deposits = WalletTransaction::select(
            'user_id',
            'withdraw_money',
            'account_number',
            'bank_account_holder_name',
            'bank_name',

            'withdraw_trading_code',
            'withdraw_date_time',
            'withdraw_content'
        )->paginate(10);
        return response()->json([
            'code' => 200,
            'success' => true,
            'msg_code' => MsgCode::SUCCESS[0],
            'msg' => MsgCode::SUCCESS[1],
            'data' => $deposits,
        ], 200);
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

    public function editWalletWithdrows(Request $request, $walletTransactionId)
    {
        if ($walletTransactionId == null || empty($walletTransactionId)) {
            return response()->json("Wallet not found");
        }

        $wallet = WalletTransaction::findOrFail($walletTransactionId);
        if ($wallet == null || empty($wallet)) {
            return response()->json('Wallet not found');
        }

        DB::beginTransaction();
        try {
            $wallet_transaction = $wallet->update([
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
            'data' => $wallet_transaction,
        ]);
    }
    public function addBank(Request $request)
    {


        DB::beginTransaction();
        try {
            $wallet_transaction = WalletTransactionBankList::create([
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
            'data' => $wallet_transaction,
        ]);
    }
    public function editBank(Request $request, $bankId)
    {
        if ($bankId == null || empty($bankId)) {
            return response()->json("Wallet not found");
        }

        $wallet = WalletTransactionBankList::findOrFail($bankId);
        if ($wallet == null || empty($wallet)) {
            return response()->json('Wallet not found');
        }

        DB::beginTransaction();
        try {
            $wallet_transaction = $wallet->update([
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
            'data' => $wallet_transaction,
        ]);
    }
}
