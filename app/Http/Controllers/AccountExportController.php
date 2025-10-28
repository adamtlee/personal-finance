<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AccountExportController extends Controller
{
    public function export(Request $request)
    {
        $accounts = Account::query()
            ->with('institution')
            ->orderBy('name')
            ->get();

        $csvData = $this->generateCsvData($accounts);
        
        $filename = 'accounts_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        return response($csvData)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    protected function generateCsvData($accounts): string
    {
        $headers = [
            'ID',
            'Account Name',
            'Account Type',
            'Institution',
            'Balance',
            'Created At',
            'Updated At'
        ];

        $csv = $this->arrayToCsv($headers);

        foreach ($accounts as $account) {
            $row = [
                $account->id,
                $account->name,
                Account::getTypes()[$account->type] ?? $account->type,
                $account->institution?->name ?? 'No Institution',
                '$' . number_format($account->amount, 2),
                $account->created_at?->format('Y-m-d H:i:s') ?? '',
                $account->updated_at?->format('Y-m-d H:i:s') ?? ''
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
