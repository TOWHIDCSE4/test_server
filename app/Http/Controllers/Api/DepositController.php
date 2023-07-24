<?php

namespace App\Http\Controllers\Api;

use App\Helper\ResponseUtils;
use App\Models\Deposit;
use App\Models\MsgCode;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DepositController extends Controller
{

    public function index(Request $request)
    {

        $this->validate($request, [
            'amount' => 'required',
            'user_id' => 'required',
            'account_number' => 'required'
        ]);

        try {
            DB::beginTransaction();
            $response = Deposit::create([
                "user_id" => $request->user_id,
                "amount"  => $request->amount,
                "account_number" => $request->account_number,
                "note" => $request->note,
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


    public function store(Request $request)
    {
    }


    public function show(Deposit $deposit)
    {
    }


    public function update(Request $request, Deposit $deposit)
    {
    }


    public function destroy(Deposit $deposit)
    {
    }
}
