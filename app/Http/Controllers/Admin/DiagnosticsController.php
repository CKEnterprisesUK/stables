<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class DiagnosticsController extends Controller
{
    public function index()
    {
        $data = [];

        // Queue connection
        $data['queue_connection'] = config('queue.default');

        // Mail config
        $data['mail'] = [
            'driver' => config('mail.default'),
            'host' => config('mail.mailers.smtp.host'),
            'port' => config('mail.mailers.smtp.port'),
            'encryption' => config('mail.mailers.smtp.encryption') ?? 'none',
            'from_address' => config('mail.from.address'),
            'from_name' => config('mail.from.name'),
        ];

        // Jobs table
        $data['pending_jobs'] = 0;
        $data['failed_jobs'] = 0;
        $data['recent_failures'] = [];

        try {
            $data['pending_jobs'] = DB::table('jobs')->count();
            $data['failed_jobs'] = DB::table('failed_jobs')->count();

            if ($data['failed_jobs'] > 0) {
                $data['recent_failures'] = DB::table('failed_jobs')
                    ->orderByDesc('failed_at')
                    ->limit(5)
                    ->get(['failed_at', 'payload', 'exception'])
                    ->map(function ($job) {
                        return [
                            'failed_at' => $job->failed_at,
                            'exception' => substr($job->exception, 0, 500),
                        ];
                    })
                    ->toArray();
            }
        } catch (\Exception $e) {
            $data['db_error'] = $e->getMessage();
        }

        return view('admin.diagnostics', compact('data'));
    }

    public function sendTest(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        try {
            Mail::raw(
                'This is a test email from the diagnostics page. If you see this, SMTP is working correctly. Sent at: ' . now()->toDateTimeString(),
                function ($message) use ($request) {
                    $message->to($request->email)
                        ->subject('SMTP Diagnostic Test - ' . now()->format('H:i:s'));
                }
            );

            return back()->with('status', "Test email sent to {$request->email} — check your inbox (and spam folder).");
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send: ' . $e->getMessage());
        }
    }
}
