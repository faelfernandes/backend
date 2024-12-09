<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class QueueController extends Controller
{
    public function listEmailJobs(): JsonResponse
    {
        $jobs = DB::table('jobs')
            ->where('queue', 'back_emails')
            ->select(['id', 'queue', 'payload', 'attempts', 'created_at'])
            ->get()
            ->map(function ($job) {
                $payload = json_decode($job->payload);
                return [
                    'id' => $job->id,
                    'queue' => $job->queue,
                    'attempts' => $job->attempts,
                    'created_at' => $job->created_at,
                    'email_data' => [
                        'to' => env('NOTIFICATION_MAIL'),
                        'subject' => 'Novo Contato Criado',
                        'contact_id' => $payload->displayName ?? null
                    ]
                ];
            });

        return response()->json([
            'total' => $jobs->count(),
            'jobs' => $jobs
        ]);
    }
}
