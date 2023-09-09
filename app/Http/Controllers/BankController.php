<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use App\Helper\ParamUtils;
use App\Helper\RenterType;
use App\Helper\ResponseUtils;
use App\Helper\StatusContractDefineCode;
use App\Http\Controllers\Controller;
use App\Models\MsgCode;
use App\Models\Bank;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BankController extends Controller
{
    //getBankList
    public function  getUserBankList()
    {
        $bankList = Bank::select(
            'user_id',
            'bank_code',
            'bank_account_number',
            'bank_account_holder_name',
        )->get();
        return response()->json([
            'code' => 200,
            'success' => true,
            'msg_code' => MsgCode::SUCCESS[0],
            'msg' => MsgCode::SUCCESS[1],
            'data' => $bankList,
        ], 200);
    }

    //Get bank list by user id
    public function getUserBankListbyUserId(Request $request,$user_id) {
        $userBankList = Bank::orderBy('updated_at', 'desc')
        ->where('user_id', $user_id)->paginate($limit);
        return ResponseUtils::json([
            'code' => Response::HTTP_OK,
            'success' => true,
            'msg_code' => MsgCode::SUCCESS[0],
            'msg' => MsgCode::SUCCESS[1],
            'data' => $userBankList,
        ]);
    }
  // add bank 
    public function addBank(Request $request)
    {
        DB::beginTransaction();
        try {
            $addBankInfo = Bank::create([
                "user_id" => $request->user->id,
                "bank_code" => $request->bank_code,
                "bank_account_number" => $request->bank_account_number,
                "bank_account_holder_name" => $request->bank_account_holder_name,
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
            'data' => $addBankInfo,
        ]);
    }
    /// edit bank 

    public function update(Request $request)
    {

        $bankId = request("id");

        $BankListExist = Bank::where(
            [
                ['id', $bankId],
                ['user_id', $request->user->id]
            ]
        )->first();

        if ($BankListExist == null) {
            return ResponseUtils::json([
                'code' => Response::HTTP_BAD_REQUEST,
                'success' => false,
                'msg_code' => MsgCode::NO_BANK_EXISTS[0],
                'msg' => MsgCode::NO_BANK_EXISTS[1],
            ]);
        }

        $BankListExist->update([
            "bank_code" => $request->bank_code ?? $BankListExist->bank_code,
            "bank_account_number" => $request->bank_account_number ?? $BankListExist->bank_account_number,
            "bank_account_holder_name" => $request->bank_account_holder_name ?? $BankListExist->bank_account_holder_name,

        ]);

        return ResponseUtils::json([
            'code' => Response::HTTP_OK,
            'success' => true,
            'msg_code' => MsgCode::SUCCESS[0],
            'msg' => MsgCode::SUCCESS[1],
            'data' => $BankListExist
        ]);
    }



    // public function editBankInfo($bankId, Request $request)
    // {
    //     if ($bankId == null || empty($bankId)) {
    //         return response()->json("Wallet not found");
    //     }

    //     $editBankInfo = Bank::findOrFail($bankId);
    //     if ($editBankInfo == null || empty($editBankInfo)) {
    //         return response()->json('Wallet not found');
    //     }

    //     $editBankInfo = Bank::where(['id' => $bankId])->first();
    //     if ($editBankInfo == null) {
    //         return ResponseUtils::json([
    //             'code' => Response::HTTP_NOT_FOUND,
    //             'success' => false,
    //             'msg_code' => MsgCode::NO_TRANSACTION_EXISTS[0],
    //             'msg' => MsgCode::NO_TRANSACTION_EXISTS[1],
    //         ]);
    //     }

    //     DB::beginTransaction();
    //     try {
    //         $response = $editBankInfo->update([
    //             "bank_code" => $request->bank_code ?? $editBankInfo->bank_code,
    //             "bank_account_number" => $request->bank_account_number ?? $editBankInfo->bank_account_number,
    //             "bank_account_holder_name" => $request->bank_account_holder_name ?? $editBankInfo->bank_account_holder_name,
    //         ]);
    //         DB::commit();
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         throw new Exception($e->getMessage());
    //     }

    //     return ResponseUtils::json([
    //         'code' => Response::HTTP_OK,
    //         'success' => true,
    //         'msg_code' => MsgCode::SUCCESS[0],
    //         'msg' => MsgCode::SUCCESS[1],
    //         'data' => $editBankInfo,
    //     ]);
    // }

}