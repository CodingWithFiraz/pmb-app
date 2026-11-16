<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Services\MidtransService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(private MidtransService $midtrans) {}

    // ─── Halaman Pembayaran UKT ──────────────────────────────────────────────

    public function show(Application $application)
    {
        $this->authorize('view', $application);

        if ($application->status !== 'passed') {
            return redirect()
                ->route('applicant.dashboard')
                ->with('error', 'Pembayaran UKT hanya tersedia setelah dokumen dinyatakan lulus.');
        }

        $application->load(['studyProgram', 'admissionPeriod', 'latestPayment']);

        $golonganList         = config('ukt.golongan');
        $golonganLabels       = config('ukt.labels');
        $golonganDescriptions = config('ukt.descriptions');
        $amount               = $application->ukt_golongan
            ? ($golonganList[$application->ukt_golongan] ?? null)
            : null;

        // Snap token: hanya generate jika UKT sudah ditetapkan & belum lunas
        $snapToken  = null;
        $clientKey  = config('midtrans.client_key');
        $isPaid     = $application->latestPayment?->isPaid()
                      || $application->payment_status === 'verified';

        if ($application->ukt_golongan && $amount && ! $isPaid) {
            try {
                $payment   = $this->midtrans->getOrCreateSnapToken($application);
                $snapToken = $payment->snap_token;
            } catch (\Exception $e) {
                // Jika gagal generate token, lanjutkan halaman tanpa token
                report($e);
            }
        }

        return view('applicant.payment.show', compact(
            'application',
            'golonganList',
            'golonganLabels',
            'golonganDescriptions',
            'amount',
            'snapToken',
            'clientKey',
            'isPaid'
        ));
    }

    // ─── Webhook Notifikasi Midtrans (CSRF exempt via route) ────────────────

    public function notification(Request $request)
    {
        $payload = $request->all();

        // Verifikasi signature key Midtrans
        $serverKey  = config('midtrans.server_key');
        $orderId    = $payload['order_id'] ?? '';
        $statusCode = $payload['status_code'] ?? '';
        $grossAmount = $payload['gross_amount'] ?? '';
        $expected   = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        if (($payload['signature_key'] ?? '') !== $expected) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $payment = $this->midtrans->handleNotification($payload);

        return response()->json(['message' => $payment ? 'OK' : 'Payment not found']);
    }
}
