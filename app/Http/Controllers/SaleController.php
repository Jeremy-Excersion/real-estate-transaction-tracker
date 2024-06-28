<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\User;
use Inertia\Inertia;
use App\Models\SaleSource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\CommissionCalculator;

class SaleController extends Controller
{
    protected $commissionCalculator;

    public function __construct(CommissionCalculator $commissionCalculator)
    {
        $this->commissionCalculator = $commissionCalculator;
    }

    public function index()
    {
        $userRoles = auth()->user()->roles;
        // if user is not admin, only show their sales
        $sales = $userRoles->contains('name', 'admin')
            ? Sale::with(['fees', 'user', 'sources'])->get()
            : Sale::with(['fees', 'user', 'sources'])->where('user_id', auth()->id())->get();

        // Calculate agent commission for each sale
        foreach ($sales as $sale) {
            $sale['agent_commission'] = $this->commissionCalculator->calculateAgentCommission($sale);
        }

        return Inertia::render('Sales/Index', [
            'sales' => $sales,
        ]);
    }

    public function edit($id)
    {
        $sale = Sale::with(['fees', 'user', 'sources', 'checks'])->findOrFail($id);
        $sources = SaleSource::select('name')->distinct()->get();
        $users = User::orderBy('name')->get();

        return Inertia::render('Sales/Edit', [
            'sale' => $sale,
            'users' => $users,
            'sources' => $sources,
        ]);
    }

    public function report($id)
    {
        $sale = Sale::with(['fees', 'user', 'sources'])->findOrFail($id);

        return Inertia::render('Sales/Report', [
            'sale' => $sale,
        ]);
    }

    public function export(Request $request)
    {
        // Get request parameters to filter the sales query
        $status = $request->status;
        $user_id = $request->user_id;
        $pending_start_date = $request->pending_start_date;
        $pending_end_date = $request->pending_end_date;
        $closing_start_date = $request->closing_start_date;
        $closing_end_date = $request->closing_end_date;

        Log::info($request->all());

        $userRoles = auth()->user()->roles;
        // if user is not Admin, only show their sales
        $salesQuery = $userRoles->contains('name', 'admin')
            ? Sale::with(['fees', 'user', 'sources'])
            : Sale::with(['fees', 'user', 'sources'])->where('user_id', auth()->id());


        // Get sales based on the request parameters
        $sales = $salesQuery
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($user_id, function ($query, $user_id) {
                return $query->where('user_id', $user_id);
            })
            ->when($pending_start_date, function ($query, $pending_start_date) {
                return $query->where('pending_date', '>=', $pending_start_date);
            })
            ->when($pending_end_date, function ($query, $pending_end_date) {
                return $query->where('pending_date', '<=', $pending_end_date);
            })
            ->when($closing_start_date, function ($query, $closing_start_date) {
                return $query->where('closing_date', '>=', $closing_start_date);
            })
            ->when($closing_end_date, function ($query, $closing_end_date) {
                return $query->where('closing_date', '<=', $closing_end_date);
            })
            ->get();

        Log::info('all sales' . $sales);

        // Create a CSV file with the sales data
        $filename = 'sales.csv';
        $filePath = storage_path('app/' . $filename);

        $handle = fopen($filePath, 'w+');
        fputcsv($handle, [
            'ID',
            'User',
            'Client Name',
            'Client Email',
            'Address',
            'City',
            'State',
            'Zip',
            'Asking Price',
            'Sold Price',
            'Agent Split Percentage',
            'Pending Date',
            'Closing Date',
            'Status',
            'Buyer',
            'Notes',
            'Agent Commission',
            'Created At',
            'Updated At',
        ]);

        foreach ($sales as $sale) {
            fputcsv($handle, [
                $sale->id,
                $sale->user->name,
                $sale->client_name,
                $sale->client_email,
                $sale->address,
                $sale->city,
                $sale->state,
                $sale->zip,
                $sale->asking_price,
                $sale->sold_price,
                $sale->agent_split_percentage,
                $sale->pending_date,
                $sale->closing_date,
                $sale->status,
                $sale->buyer,
                $sale->notes,
                $this->commissionCalculator->calculateAgentCommission($sale),
                $sale->created_at,
                $sale->updated_at,
            ]);
        }

        fclose($handle);

        // Check if file exists before downloading
        if (file_exists($filePath)) {
            Log::info('downloading file ' . $filePath);

            return response()->download($filePath, $filename, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Content-Length' => filesize($filePath),
            ])->deleteFileAfterSend(true);
        } else {
            Log::error('File not found: ' . $filePath);
            return response()->json(['error' => 'File not found.'], 404);
        }
    }

    public function update($id, Request $request)
    {
        Log::info($request->all());
        $sale = Sale::findOrFail($id);
        $sale->update($request->all());
        // handle adding/updating sale fees here
        $sale->fees()->delete();
        if ($request->fees) {
            $sale->fees()->createMany($request->fees);
        }
        // handle adding/updating sale sources here
        $sale->sources()->delete();
        if ($request->sources) {
            $sale->sources()->createMany($request->sources);
        }
        // handle adding/updating sale checks here
        $sale->checks()->delete();
        if ($request->checks) {
            $sale->checks()->createMany($request->checks);
        }
        return response()->json(['sale' => $sale], 200);
    }

    public function create(Request $request)
    {
        $sale = new Sale();
        $sale->address = $request->address;
        $sale->agent_split_percentage = $request->agent_split_percentage;
        $sale->asking_price = $request->asking_price;
        $sale->buyer = $request->buyer;
        $sale->client_email = $request->client_email;
        $sale->client_name = $request->client_name;
        $sale->closing_date = $request->closing_date;
        $sale->city = $request->city;
        $sale->notes = $request->notes;
        $sale->pending_date = $request->pending_date;
        $sale->percentage = $request->percentage;
        $sale->sold_price = $request->sold_price;
        $sale->state = $request->state;
        $sale->status = $request->status;
        $sale->user_id = $request->user_id;
        $sale->zip = $request->zip;

        $sale->save();

        if ($request->sources) {
            $sale->sources()->createMany($request->sources);
        }

        if ($request->fees) {
            $sale->fees()->createMany($request->fees);
        }

        if ($request->checks) {
            $sale->checks()->createMany($request->checks);
        }
        return response()->json(['sale' => $sale], 200);
    }
}
