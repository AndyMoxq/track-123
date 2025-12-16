<?php
namespace ThankSong\Track123\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use ThankSong\Track123\Models\Tracking;

class WebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        $request->validate([
            'data' => ['required', 'array'],
            'verify' => ['nullable', 'array'],
            'verify.signature' => ['nullable', 'string'],
            'verify.timestamp' => ['nullable', 'string'],
        ]);
        try {
            Tracking::init($request->data);
            return response()->json([
                'code' => 200,
                'message' => 'Success',
            ], 200);
        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'code' => 500,
                'message' => 'Failed to persist tracking payload.',
            ], 500);
        }
    }
}