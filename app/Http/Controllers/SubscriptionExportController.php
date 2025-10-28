<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SubscriptionExportController extends Controller
{
    public function export(Request $request)
    {
        $subscriptions = Subscription::query()
            ->where('user_id', auth()->id())
            ->orderBy('name')
            ->get();

        $csvData = $this->generateCsvData($subscriptions);
        
        $filename = 'subscriptions_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        return response($csvData)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    protected function generateCsvData($subscriptions): string
    {
        $headers = [
            'ID',
            'Subscription Name',
            'Price',
            'Billing Frequency',
            'Category',
            'Status',
            'Next Billing Date',
            'Description',
            'Created At',
            'Updated At'
        ];

        $csv = $this->arrayToCsv($headers);

        foreach ($subscriptions as $subscription) {
            $row = [
                $subscription->id,
                $subscription->name,
                '$' . number_format($subscription->price, 2),
                Subscription::getBillingFrequencies()[$subscription->billing_frequency] ?? $subscription->billing_frequency,
                Subscription::getCategories()[$subscription->category] ?? $subscription->category,
                Subscription::getStatuses()[$subscription->status] ?? $subscription->status,
                $subscription->next_billing_date?->format('Y-m-d') ?? 'Not Set',
                $subscription->description ?? '',
                $subscription->created_at?->format('Y-m-d H:i:s') ?? '',
                $subscription->updated_at?->format('Y-m-d H:i:s') ?? ''
            ];

            $csv .= $this->arrayToCsv($row);
        }

        return $csv;
    }

    protected function arrayToCsv(array $data): string
    {
        $output = fopen('php://temp', 'r+');
        fputcsv($output, $data);
        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);
        return $csv;
    }
}
