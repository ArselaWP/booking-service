<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use App\Services\RabbitMQPublisher;

class BookingController extends Controller
{
    /**
     * CREATE BOOKING
     */
    public function store(Request $request)
    {
        $booking = Booking::create([
            'user_id' => $request->user_id,
            'facility_id' => $request->facility_id,
            'schedule_id' => $request->schedule_id,
            'booking_date' => $request->booking_date,
            'status' => 'PENDING'
        ]);

        RabbitMQPublisher::publish(
            'booking_created',
            [
                'booking_id' => $booking->id,
                'user_id' => $booking->user_id,
                'facility_id' => $booking->facility_id,
                'schedule_id' => $booking->schedule_id,
                'status' => 'booked'
            ]
        );

        return response()->json([
            'message' => 'Booking created successfully',
            'data' => $booking
        ], 201);
    }

    /**
     * GET BOOKING BY ID
     */
    public function show(string $id)
    {
        $booking = Booking::findOrFail($id);

        return response()->json([
            'message' => 'Booking found',
            'data' => $booking
        ]);
    }

    /**
     * GET BOOKING BY USER
     */
    public function getByUser($userId)
    {
        $bookings = Booking::where('user_id', $userId)->get();

        return response()->json([
            'message' => 'Bookings retrieved',
            'data' => $bookings
        ]);
    }

    /**
     * APPROVE BOOKING
     */
    public function approve(string $id)
    {
        $booking = Booking::findOrFail($id);

        $booking->status = 'APPROVED';
        $booking->save();

        return response()->json([
            'message' => 'Booking approved',
            'data' => $booking
        ]);
    }

    /**
     * CANCEL BOOKING
     */
  public function cancel(string $id)
{
    $booking = Booking::findOrFail($id);

    $booking->status = 'CANCELLED';
    $booking->save();

    RabbitMQPublisher::publish(
        'booking_created',
        [
            'event' => 'BookingCancelled',
            'booking_id' => $booking->id,
            'schedule_id' => $booking->schedule_id,
            'status' => 'available'
        ]
    );

    return response()->json([
        'message' => 'Booking cancelled',
        'data' => $booking
    ]);
}
}