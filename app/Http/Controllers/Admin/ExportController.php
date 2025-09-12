<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Automobile;
use App\Models\User;
use App\Models\Booking;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;

class ExportController extends Controller
{
    /**
     * Export automobiles data
     */
    public function automobiles(Request $request)
    {
        $query = Automobile::with('dealer');

        // Apply filters if provided
        if ($request->has('filters')) {
            $filters = $request->get('filters');

            if (!empty($filters['make'])) {
                $query->where('make', $filters['make']);
            }
            if (!empty($filters['status'])) {
                $query->where('status', $filters['status']);
            }
            if (!empty($filters['min_price'])) {
                $query->where('price', '>=', $filters['min_price']);
            }
            if (!empty($filters['max_price'])) {
                $query->where('price', '<=', $filters['max_price']);
            }
            if (!empty($filters['date_from'])) {
                $query->whereDate('created_at', '>=', $filters['date_from']);
            }
            if (!empty($filters['date_to'])) {
                $query->whereDate('created_at', '<=', $filters['date_to']);
            }
        }

        $automobiles = $query->orderBy('created_at', 'desc')->get();

        $csvData = $this->generateAutomobilesCsv($automobiles);
        $filename = 'automobiles_export_' . Carbon::now()->format('Y-m-d_H-i-s') . '.csv';

        return Response::make($csvData, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Export users data
     */
    public function users(Request $request)
    {
        $query = User::query();

        // Apply filters if provided
        if ($request->has('filters')) {
            $filters = $request->get('filters');

            if (!empty($filters['role'])) {
                $query->where('role', $filters['role']);
            }
            if (!empty($filters['date_from'])) {
                $query->whereDate('created_at', '>=', $filters['date_from']);
            }
            if (!empty($filters['date_to'])) {
                $query->whereDate('created_at', '<=', $filters['date_to']);
            }
        }

        $users = $query->orderBy('created_at', 'desc')->get();

        $csvData = $this->generateUsersCsv($users);
        $filename = 'users_export_' . Carbon::now()->format('Y-m-d_H-i-s') . '.csv';

        return Response::make($csvData, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Export bookings data
     */
    public function bookings(Request $request)
    {
        $query = Booking::with(['user', 'automobile']);

        // Apply filters if provided
        if ($request->has('filters')) {
            $filters = $request->get('filters');

            if (!empty($filters['status'])) {
                $query->where('status', $filters['status']);
            }
            if (!empty($filters['date_from'])) {
                $query->whereDate('created_at', '>=', $filters['date_from']);
            }
            if (!empty($filters['date_to'])) {
                $query->whereDate('created_at', '<=', $filters['date_to']);
            }
        }

        $bookings = $query->orderBy('created_at', 'desc')->get();

        $csvData = $this->generateBookingsCsv($bookings);
        $filename = 'bookings_export_' . Carbon::now()->format('Y-m-d_H-i-s') . '.csv';

        return Response::make($csvData, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Export transactions data
     */
    public function transactions(Request $request)
    {
        $query = Transaction::with(['user', 'automobile']);

        // Apply filters if provided
        if ($request->has('filters')) {
            $filters = $request->get('filters');

            if (!empty($filters['status'])) {
                $query->where('status', $filters['status']);
            }
            if (!empty($filters['min_amount'])) {
                $query->where('amount', '>=', $filters['min_amount']);
            }
            if (!empty($filters['max_amount'])) {
                $query->where('amount', '<=', $filters['max_amount']);
            }
            if (!empty($filters['date_from'])) {
                $query->whereDate('created_at', '>=', $filters['date_from']);
            }
            if (!empty($filters['date_to'])) {
                $query->whereDate('created_at', '<=', $filters['date_to']);
            }
        }

        $transactions = $query->orderBy('created_at', 'desc')->get();

        $csvData = $this->generateTransactionsCsv($transactions);
        $filename = 'transactions_export_' . Carbon::now()->format('Y-m-d_H-i-s') . '.csv';

        return Response::make($csvData, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Export comprehensive sales report
     */
    public function salesReport(Request $request)
    {
        $dateFrom = $request->get('date_from', Carbon::now()->startOfMonth());
        $dateTo = $request->get('date_to', Carbon::now()->endOfMonth());

        // Get sales data
        $transactions = Transaction::with(['user', 'automobile'])
            ->where('status', 'completed')
            ->whereDate('created_at', '>=', $dateFrom)
            ->whereDate('created_at', '<=', $dateTo)
            ->orderBy('created_at', 'desc')
            ->get();

        $csvData = $this->generateSalesReportCsv($transactions, $dateFrom, $dateTo);
        $filename = 'sales_report_' . Carbon::parse($dateFrom)->format('Y-m-d') . '_to_' . Carbon::parse($dateTo)->format('Y-m-d') . '.csv';

        return Response::make($csvData, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Generate CSV data for automobiles
     */
    private function generateAutomobilesCsv($automobiles)
    {
        $headers = [
            'ID', 'Make', 'Model', 'Year', 'VIN', 'Price', 'Mileage', 'Color',
            'Body Type', 'Fuel Type', 'Transmission', 'Engine Size', 'Status',
            'Dealer Name', 'Dealer Email', 'Created At', 'Updated At'
        ];

        $csvData = $this->arrayToCsv($headers);

        foreach ($automobiles as $automobile) {
            $row = [
                $automobile->id,
                $automobile->make,
                $automobile->model,
                $automobile->year,
                $automobile->vin,
                $automobile->price,
                $automobile->mileage,
                $automobile->color,
                $automobile->body_type,
                $automobile->fuel_type,
                $automobile->transmission,
                $automobile->engine_size,
                $automobile->status,
                $automobile->dealer->name ?? 'N/A',
                $automobile->dealer->email ?? 'N/A',
                $automobile->created_at->format('Y-m-d H:i:s'),
                $automobile->updated_at->format('Y-m-d H:i:s')
            ];

            $csvData .= $this->arrayToCsv($row);
        }

        return $csvData;
    }

    /**
     * Generate CSV data for users
     */
    private function generateUsersCsv($users)
    {
        $headers = [
            'ID', 'Name', 'Email', 'Role', 'Email Verified At', 'Created At', 'Updated At'
        ];

        $csvData = $this->arrayToCsv($headers);

        foreach ($users as $user) {
            $row = [
                $user->id,
                $user->name,
                $user->email,
                $user->role,
                $user->email_verified_at ? $user->email_verified_at->format('Y-m-d H:i:s') : 'Not Verified',
                $user->created_at->format('Y-m-d H:i:s'),
                $user->updated_at->format('Y-m-d H:i:s')
            ];

            $csvData .= $this->arrayToCsv($row);
        }

        return $csvData;
    }

    /**
     * Generate CSV data for bookings
     */
    private function generateBookingsCsv($bookings)
    {
        $headers = [
            'ID', 'User Name', 'User Email', 'Automobile', 'Booking Date', 'Status',
            'Notes', 'Created At', 'Updated At'
        ];

        $csvData = $this->arrayToCsv($headers);

        foreach ($bookings as $booking) {
            $automobile = $booking->automobile;
            $automobileInfo = $automobile ?
                $automobile->year . ' ' . $automobile->make . ' ' . $automobile->model : 'N/A';

            $row = [
                $booking->id,
                $booking->user->name ?? 'N/A',
                $booking->user->email ?? 'N/A',
                $automobileInfo,
                $booking->booking_date->format('Y-m-d H:i:s'),
                $booking->status,
                $booking->notes ?? '',
                $booking->created_at->format('Y-m-d H:i:s'),
                $booking->updated_at->format('Y-m-d H:i:s')
            ];

            $csvData .= $this->arrayToCsv($row);
        }

        return $csvData;
    }

    /**
     * Generate CSV data for transactions
     */
    private function generateTransactionsCsv($transactions)
    {
        $headers = [
            'ID', 'User Name', 'User Email', 'Automobile', 'Amount', 'Type', 'Status',
            'Payment Method', 'Transaction Date', 'Created At', 'Updated At'
        ];

        $csvData = $this->arrayToCsv($headers);

        foreach ($transactions as $transaction) {
            $automobile = $transaction->automobile;
            $automobileInfo = $automobile ?
                $automobile->year . ' ' . $automobile->make . ' ' . $automobile->model : 'N/A';

            $row = [
                $transaction->id,
                $transaction->user->name ?? 'N/A',
                $transaction->user->email ?? 'N/A',
                $automobileInfo,
                $transaction->amount,
                $transaction->type,
                $transaction->status,
                $transaction->payment_method ?? 'N/A',
                $transaction->transaction_date->format('Y-m-d H:i:s'),
                $transaction->created_at->format('Y-m-d H:i:s'),
                $transaction->updated_at->format('Y-m-d H:i:s')
            ];

            $csvData .= $this->arrayToCsv($row);
        }

        return $csvData;
    }

    /**
     * Generate CSV data for sales report
     */
    private function generateSalesReportCsv($transactions, $dateFrom, $dateTo)
    {
        $csvData = "Sales Report\n";
        $csvData .= "Period: " . Carbon::parse($dateFrom)->format('F j, Y') . " to " . Carbon::parse($dateTo)->format('F j, Y') . "\n";
        $csvData .= "Generated: " . Carbon::now()->format('F j, Y \a\t g:i A') . "\n";
        $csvData .= "Total Transactions: " . $transactions->count() . "\n";
        $csvData .= "Total Revenue: $" . number_format($transactions->sum('amount'), 2) . "\n\n";

        $headers = [
            'Transaction ID', 'Date', 'Customer Name', 'Customer Email', 'Vehicle',
            'Amount', 'Payment Method', 'Status'
        ];

        $csvData .= $this->arrayToCsv($headers);

        foreach ($transactions as $transaction) {
            $automobile = $transaction->automobile;
            $automobileInfo = $automobile ?
                $automobile->year . ' ' . $automobile->make . ' ' . $automobile->model : 'N/A';

            $row = [
                $transaction->id,
                $transaction->transaction_date->format('Y-m-d'),
                $transaction->user->name ?? 'N/A',
                $transaction->user->email ?? 'N/A',
                $automobileInfo,
                '$' . number_format($transaction->amount, 2),
                $transaction->payment_method ?? 'N/A',
                $transaction->status
            ];

            $csvData .= $this->arrayToCsv($row);
        }

        return $csvData;
    }

    /**
     * Convert array to CSV row
     */
    private function arrayToCsv($array)
    {
        $output = fopen('php://temp', 'w');
        fputcsv($output, $array);
        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);
        return $csv;
    }
}
