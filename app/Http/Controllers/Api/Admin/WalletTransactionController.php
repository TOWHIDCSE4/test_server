<?php

namespace App\Http\Controllers\Api\Admin;

use App\Helper\ResponseUtils;
use App\Models\WalletTransaction;
use App\Models\MsgCode;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DepositController extends Controller
{

    // Wallet Deposit

    public function  getAllWalletDeposit()
    {
        $deposits = WalletTransaction::select('deposit_money', 'deposit_trading_code', 'deposit_date_time', 'deposit_content')->get();
        return response()->json([
            'code' => 200,
            'success' => true,
            'msg_code' => MsgCode::SUCCESS[0],
            'msg' => MsgCode::SUCCESS[1],
            'data' => $deposits,
        ], 200);
    }

    public function  createWalletDeposit(Request $request)
    {

        $this->validate($request, [
            'deposit_money' => 'required',
            'deposit_trading_code' => 'required',
        ]);

        try {
            DB::beginTransaction();
            $response = WalletTransaction::create([
                "user_id" => $request->user_id,
                "deposit_money"  => $request->deposit_money,
                "deposit_trading_code" => $request->deposit_trading_code,
                "deposit_date_time" => $request->deposit_date_time,
                "deposit_content" => $request->deposit_content,
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }


        return ResponseUtils::json([
            'code' => Response::HTTP_OK,
            'success' => true,
            'msg_code' => MsgCode::SUCCESS[0],
            'msg' => MsgCode::SUCCESS[1],
            'data' => $response,
        ]);
    }

    public function  editWalletDeposit(Request $request)
    {

        $this->validate($request, [
            'deposit_money' => 'required',
            'deposit_trading_code' => 'required',
        ]);

        try {
            $wallet = WalletTransaction::findOrFail($request->id);
            DB::beginTransaction();
            $response = $wallet->update([
                "user_id" => $request->user_id,
                "deposit_money"  => $request->deposit_money,
                "deposit_trading_code" => $request->deposit_trading_code,
                "deposit_date_time" => $request->deposit_date_time,
                "deposit_content" => $request->deposit_content,
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }


        return ResponseUtils::json([
            'code' => Response::HTTP_OK,
            'success' => true,
            'msg_code' => MsgCode::SUCCESS[0],
            'msg' => MsgCode::SUCCESS[1],
            'data' => $response,
        ]);
    }

    // Wallet withdrow 
    public function  getAllWalletWithdrow()
    {
        $deposits = WalletTransaction::select('withdraw_money', 'withdraw_trading_code', 'withdraw_date_time', 'withdraw_content')->get();
        return response()->json([
            'code' => 200,
            'success' => true,
            'msg_code' => MsgCode::SUCCESS[0],
            'msg' => MsgCode::SUCCESS[1],
            'data' => $deposits,
        ], 200);
    }

    public function  createWalletWithdrow(Request $request)
    {

        $this->validate($request, [
            'withdraw_money' => 'required',
            'user_id' => 'required',
            'withdraw_trading_code' => 'required'
        ]);

        try {
            DB::beginTransaction();
            $response = WalletTransaction::create([
                "user_id" => $request->user_id,
                "withdraw_money"  => $request->withdraw_money,
                "withdraw_trading_code" => $request->withdraw_trading_code,
                "withdraw_date_time" => $request->withdraw_date_time,
                "withdraw_content" => $request->withdraw_content,
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }


        return ResponseUtils::json([
            'code' => Response::HTTP_OK,
            'success' => true,
            'msg_code' => MsgCode::SUCCESS[0],
            'msg' => MsgCode::SUCCESS[1],
            'data' => $response,
        ]);
    }

    public function  editWalletWithdrow(Request $request, $id)
    {

        $this->validate($request, [
            'amount' => 'required',
            'user_id' => 'required',
            'account_number' => 'required'
        ]);

        try {
            $wallet = WalletTransaction::findOrFail($id);
            DB::beginTransaction();
            $response = $wallet->update([
                "user_id" => $request->user_id,
                "withdraw_money"  => $request->withdraw_money,
                "withdraw_trading_code" => $request->withdraw_trading_code,
                "withdraw_date_time" => $request->withdraw_date_time,
                "withdraw_content" => $request->withdraw_content,
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }


        return ResponseUtils::json([
            'code' => Response::HTTP_OK,
            'success' => true,
            'msg_code' => MsgCode::SUCCESS[0],
            'msg' => MsgCode::SUCCESS[1],
            'data' => $response,
        ]);
    }

    // Bank information
    public function  getAllWalletBankList()
    {
        $deposits = WalletTransaction::select('account_number', 'bank_account_holder_name', 'bank_name', 'rest_money', 'otp_code')->get();
        return response()->json([
            'code' => 200,
            'success' => true,
            'msg_code' => MsgCode::SUCCESS[0],
            'msg' => MsgCode::SUCCESS[1],
            'data' => $deposits,
        ], 200);
    }

    public function  createWalletBank(Request $request)
    {

        $this->validate($request, [
            'account_number' => 'required',
            'user_id' => 'required',
            'bank_account_holder_name' => 'required'
        ]);

        try {
            DB::beginTransaction();
            $response = WalletTransaction::create([
                "user_id" => $request->user_id,
                "bank_name"  => $request->bank_name,
                "bank_account_holder_name" => $request->bank_account_holder_name,
                "rest_money" => $request->rest_money,
                "account_number" => $request->account_number,
                "otp_code" => $request->otp_code,
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }


        return ResponseUtils::json([
            'code' => Response::HTTP_OK,
            'success' => true,
            'msg_code' => MsgCode::SUCCESS[0],
            'msg' => MsgCode::SUCCESS[1],
            'data' => $response,
        ]);
    }

    public function  editWalletBank(Request $request, $id)
    {

        $this->validate($request, [
            'account_number' => 'required',
            'user_id' => 'required',
            'bank_account_holder_name' => 'required'
        ]);

        try {
            $bankInfo =  WalletTransaction::findOrFail($id);
            DB::beginTransaction();
            $response =  $bankInfo->update([
                "user_id" => $request->user_id,
                "bank_name"  => $request->bank_name,
                "bank_account_holder_name" => $request->bank_account_holder_name,
                "rest_money" => $request->rest_money,
                "account_number" => $request->account_number,
                "otp_code" => $request->otp_code,
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }


        return ResponseUtils::json([
            'code' => Response::HTTP_OK,
            'success' => true,
            'msg_code' => MsgCode::SUCCESS[0],
            'msg' => MsgCode::SUCCESS[1],
            'data' => $response,
        ]);
    }
}
