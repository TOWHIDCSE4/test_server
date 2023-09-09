<?php

namespace App\Http\Controllers;

use App\Helper\ResponseUtils;
use App\Models\Bank;
use App\Models\MsgCode;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class BankController extends Controller
{

    public function index()
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


    public function store(Request $request)
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


    public function show(Bank $bank, $user_id,)
    {

        $userBankList = Bank::orderBy('updated_at', 'desc')
            ->where('user_id', $user_id)->paginate(request()->limit);
        return ResponseUtils::json([
            'code' => Response::HTTP_OK,
            'success' => true,
            'msg_code' => MsgCode::SUCCESS[0],
            'msg' => MsgCode::SUCCESS[1],
            'data' => $userBankList,
        ]);
    }


    public function update(Request $request, Bank $bank)
    {

        if ($bank == null) {
            return ResponseUtils::json([
                'code' => Response::HTTP_BAD_REQUEST,
                'success' => false,
                'msg_code' => "NO_BANK",
                'msg' => "No bank found",
            ]);
        }

        $bank->update([
            "bank_code" => $request->bank_code ?? $bank->bank_code,
            "bank_account_number" => $request->bank_account_number ?? $bank->bank_account_number,
            "bank_account_holder_name" => $request->bank_account_holder_name ?? $bank->bank_account_holder_name,

        ]);

        return ResponseUtils::json([
            'code' => Response::HTTP_OK,
            'success' => true,
            'msg_code' => MsgCode::SUCCESS[0],
            'msg' => MsgCode::SUCCESS[1],
            'data' => $bank
        ]);
    }


    public function destroy(Bank $bank)
    {
    }
}
