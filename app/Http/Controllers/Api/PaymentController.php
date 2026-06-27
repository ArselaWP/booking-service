<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * GET ALL PAYMENTS
     */
    public function index()
    {
        return response()->json([
            'message' => 'Payments retrieved',
            'data' => Payment::all()
        ]);
    }

    /**
     * CREATE PAYMENT
     */
    public function store(Request $request)
    {
        $payment = Payment::create([
            'booking_id' => $request->booking_id,
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'payment_status' => 'PAID'
        ]);

        return response()->json([
            'message' => 'Payment created',
            'data' => $payment
        ], 201);
    }

    /**
     * GET PAYMENT BY ID
     */
    public function show(string $id)
    {
        return response()->json([
            'message' => 'Payment found',
            'data' => Payment::findOrFail($id)
        ]);
    }
}