<?php

namespace App\Http\Controllers;

use App\Jobs\WhatsappSendMessage;
use App\Models\Student;
use Illuminate\Http\Request;
use Propaganistas\LaravelPhone\PhoneNumber;

class WebhookController extends Controller
{
    public function __invoke(Request $request)
    {
        // 1. Verifikasi signature via method khusus
        if (!$this->isValidSignature($request) && !app()->environment('local')) {
            return response()->json(['message' => 'Invalid or missing signature'], 401);
        }

        // 2. Jika valid, proses data payload di sini
        $data = $request->json()->all();

        // Contoh: tangani event webhook
        // $event = $request->header('X-GitHub-Event');

        // return response()->json([
        //     'status' => 'success',
        //     'message' => 'Webhook berhasil diproses',
        //     'data' => $data
        // ], 200);

        // dd($data['payload']['body']);

        if ($data['event'] != 'message') {
            return response()->noContent();
        }

        if (!preg_match('/<([^>]+)\/>/', $data['payload']['body'], $matches)) {
            return response()->noContent();
        }

        $student = Student::whereToken($matches[1])->first();

        if (!$student) {
            return response()->noContent();
        }

        $phone = new PhoneNumber(str($data['payload']['chat_id'])->before('@'), 'ID');

        $student->update([
            'whatsapp' => (string) $phone
        ]);

        WhatsappSendMessage::dispatch($phone, $student);
    }

    private function isValidSignature(Request $request): bool
    {
        $signature = $request->header('X-Hub-Signature-256');
        $secret = 'lalisandine';

        // Pastikan header signature dan secret key ada
        if (!$signature || !$secret) {
            return false;
        }

        // Ambil raw payload
        $payload = $request->getContent();

        // Hitung hash yang diharapkan
        $expectedSignature = 'sha256=' . hash_hmac('sha256', $payload, $secret);

        // Bandingkan dengan aman
        return hash_equals($expectedSignature, $signature);
    }
}
