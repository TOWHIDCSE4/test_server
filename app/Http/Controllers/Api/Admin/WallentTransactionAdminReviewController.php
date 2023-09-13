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
use App\Models\WalletTransaction;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WallentTransactionAdminReviewController extends Controller
{

    public function getAllBankList()
    {
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

    // get Wallet Data For Graph
    public function getWalletDataForGraph(Request $request)
    {
        $year = $request->year ?? date('Y');
        $type = $request->type ?? 'deposit';

        if ($year != null) {
            $dateFrom  = $year . '-01-01 00:00:00';
            $dateTo    = $year . '-12-31 23:59:59';
        }
        $monthWiseTotal = [];
        $wallet_transaction_data = [];
        if ($type == 'deposit') {
            $wallet_transaction_data = WalletTransaction::when($dateFrom != null, function ($query) use ($dateFrom) {
                $query->where('wallet_transactions.deposit_date_time', '>=', $dateFrom);
            })
                ->when($dateTo != null, function ($query) use ($dateTo) {
                    $query->where('wallet_transactions.deposit_date_time', '<=', $dateTo);
                })->where('type', WalletTransaction::DEPOSIT)
                ->select('wallet_transactions.deposit_money', 'wallet_transactions.deposit_date_time', 'wallet_transactions.type', 'wallet_transactions.status')
                ->get();

            $monthWiseTotal = $this->monthWiseToTal($wallet_transaction_data, ['deposit_date_time', 'deposit_money']);
        }

        if ($type == 'withdraw') {
            $wallet_transaction_data = WalletTransaction::when($dateFrom != null, function ($query) use ($dateFrom) {
                $query->where('wallet_transactions.withdraw_date_time', '>=', $dateFrom);
            })
                ->when($dateTo != null, function ($query) use ($dateTo) {
                    $query->where('wallet_transactions.withdraw_date_time', '<=', $dateTo);
                })->where('type', WalletTransaction::WITHDRAW)
                ->select('wallet_transactions.withdraw_money', 'wallet_transactions.withdraw_date_time', 'wallet_transactions.type', 'wallet_transactions.status')
                ->get();

            $monthWiseTotal = $this->monthWiseToTal($wallet_transaction_data, ['withdraw_date_time', 'withdraw_money']);
        }

        return response()->json([
            'code' => Response::HTTP_OK,
            'success' => true,
            'msg_code' => MsgCode::SUCCESS[0],
            'msg' => MsgCode::SUCCESS[1],
            'data' => [$monthWiseTotal],
        ], 200);

        // return ResponseUtils::json([
        //     'code' => Response::HTTP_OK,
        //     'success' => true,
        //     'msg_code' => MsgCode::SUCCESS[0],
        //     'msg' => MsgCode::SUCCESS[1],
        //     'data' => $monthWiseTotal
        // ]);
    }

    protected function monthWiseToTal($wallet_transaction_data, $property = [])
    {
        $monthWiseTotal = [];
        foreach ($wallet_transaction_data as $entry) {
            $month = date('m', strtotime($entry[$property[0]]));
            $monthNumber = intval($month);
            $withdrawMoney = $entry[$property[1]];
            if (array_key_exists($monthNumber, $monthWiseTotal)) {
                $monthWiseTotal[$monthNumber] += $withdrawMoney;
            } else {
                $monthWiseTotal[$monthNumber] = $withdrawMoney;
            }
        }

        return $monthWiseTotal;
    }
}
