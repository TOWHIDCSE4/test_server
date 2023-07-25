<?php

namespace App\Http\Controllers\Api\Admin;

use App\Helper\ResponseUtils;
use App\Http\Controllers\Controller;
use App\Models\MsgCode;
use App\Models\WalletTransaction;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class WallentTransactionAdminReviewController extends Controller
{
    
    public function  confirmPaymentStatusAdmin(Request $request)
    {
        if ($request->wallet_transaction_id == null || empty($request->wallet_transaction_id)) {
            return ResponseUtils::json([
                'code' => Response::HTTP_BAD_REQUEST,
                'success' => false,
                'msg_code' => MsgCode::WALLET_TRANSACTION_ID_IS_REQUIRED[0],
                'msg' => MsgCode::WALLET_TRANSACTION_ID_IS_REQUIRED[1],
            ]);
        }

        $wallet_transaction = WalletTransaction::where('id', $request->wallet_transaction_id)->first();

        if ($wallet_transaction == null) {
            return ResponseUtils::json([
                'code' => Response::HTTP_NOT_FOUND,
                'success' => false,
                'msg_code' => MsgCode::NO_TRANSACTION_EXISTS[0],
                'msg' => MsgCode::NO_TRANSACTION_EXISTS[1],
            ]);
        }

        DB::beginTransaction();

        try {
            $result = $wallet_transaction->update([
                "status" => WalletTransaction::COMPLETED,
            ]);
            DB::commit();

            return ResponseUtils::json([
                'code' => Response::HTTP_OK,
                'success' => true,
                'msg_code' => MsgCode::SUCCESS[0],
                'msg' => MsgCode::SUCCESS[1],
                'data' => $wallet_transaction,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }

    }

}
