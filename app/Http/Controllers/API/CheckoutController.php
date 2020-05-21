<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\TransactionDetail;
use App\Models\Product;
use App\Models\Transaction;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\CheckoutRequest;

class CheckoutController extends Controller
{
    public function checkout(CheckoutRequest $request)
    {
        $data = $request->except('transaction_details');
        $data['uuid'] = 'TRX'.mt_rand(10000, 99999).mt_rand(100, 999);

        $transaction = Transaction::create($data);

        foreach($request->transaction_details as $product)
        {
            // membuat array transaksi detail
            $details[] = new TransactionDetail([
                'transactions_id' => $transaction->id,
                'products_id' => $product,
            ]);

            // mengurangi quantity
            Product::find($product)->decrement('quantity');
        }

        $transaction->details()->saveMany($details);

        return ResponseFormatter::success($transaction);
    }
}
