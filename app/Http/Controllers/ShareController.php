<?php

namespace App\Http\Controllers;

use App\Models\Share;
use App\Models\Platform;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ShareController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the dashboard.
     */
    public function index()
    {
        $platforms = Platform::all();
        return view('dashboard', compact('platforms'));
    }

    /**
     * Store a social media share record.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'forum_id' => 'required|exists:forums,id',
                'platform_id' => 'required|exists:platforms,id',
                'url' => 'required|url',
            ]);

            $platform = Platform::find($validated['platform_id']);

            Share::create([
                'user_id' => $request->user()?->id,
                'forum_id' => $validated['forum_id'],
                'platform_id' => $platform->id,
                'platform_name' => $platform->name,
                'url' => $validated['url'],
                'ip_address' => $request->ip(),
            ]);

            return response()->json([
                'message' => 'Share recorded successfully',
                'success' => true,
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error recording share:', [
                'error' => $e->getMessage(),
                'request_data' => $request->all(),
            ]);

            return response()->json([
                'message' => 'Failed to record share',
                'success' => false,
            ], 500);
        }
    }

    /**
     * Get shares grouped by platform.
     */
    public function byPlatform(Request $request)
    {
        $startDate = $request->start_date ? Carbon::createFromFormat('Y-m-d', $request->start_date, 'Asia/Kuala_Lumpur')->startOfDay()->utc() : Carbon::now()->subDays(30)->utc();
        $endDate = $request->end_date ? Carbon::createFromFormat('Y-m-d', $request->end_date, 'Asia/Kuala_Lumpur')->endOfDay()->utc() : Carbon::now()->endOfDay()->utc();

        $query = Share::selectRaw('platform_name, count(*) as total')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('platform_name')
            ->orderByRaw('total DESC');

        return response()->json($query->get());
    }

    /**
     * Get shares grouped by date.
     */
    public function byDate(Request $request)
    {
        $startDate = $request->start_date ? Carbon::createFromFormat('Y-m-d', $request->start_date, 'Asia/Kuala_Lumpur')->startOfDay()->utc() : Carbon::now()->subDays(30)->utc();
        $endDate = $request->end_date ? Carbon::createFromFormat('Y-m-d', $request->end_date, 'Asia/Kuala_Lumpur')->endOfDay()->utc() : Carbon::now()->endOfDay()->utc();
        $platformId = $request->platform_id;

        $query = Share::selectRaw('DATE(created_at) as date, platform_name, count(*) as total')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date', 'platform_name')
            ->orderBy('date');

        if ($platformId) {
            $query->where('platform_id', $platformId);
        }

        return response()->json($query->get());
    }

    /**
     * Get overall statistics.
     */
    public function stats(Request $request)
    {
        $startDate = $request->start_date ? Carbon::createFromFormat('Y-m-d', $request->start_date, 'Asia/Kuala_Lumpur')->startOfDay()->utc() : Carbon::now()->subDays(30)->utc();
        $endDate = $request->end_date ? Carbon::createFromFormat('Y-m-d', $request->end_date, 'Asia/Kuala_Lumpur')->endOfDay()->utc() : Carbon::now()->endOfDay()->utc();
        $platformId = $request->platform_id;

        $query = Share::whereBetween('created_at', [$startDate, $endDate]);

        if ($platformId) {
            $query->where('platform_id', $platformId);
        }

        $totalShares = $query->count();
        $uniqueUsers = $query->distinct('user_id')->count('user_id');
        $uniqueIps = $query->distinct('ip_address')->count('ip_address');

        return response()->json([
            'total_shares' => $totalShares,
            'unique_users' => $uniqueUsers,
            'unique_ips' => $uniqueIps,
        ]);
    }
}
