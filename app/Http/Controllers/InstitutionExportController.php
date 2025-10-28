<?php

namespace App\Http\Controllers;

use App\Models\Institution;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class InstitutionExportController extends Controller
{
    public function export(Request $request)
    {
        $institutions = Institution::query()
            ->withCount('accounts')
            ->orderBy('name')
            ->get();

        $csvData = $this->generateCsvData($institutions);
        
        $filename = 'institutions_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        return response($csvData)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    protected function generateCsvData($institutions): string
    {
        $headers = [
            'ID',
            'Name',
            'Type',
            'Website',
            'Description',
            'Accounts Count',
            'Created At',
            'Updated At'
        ];

        $csv = $this->arrayToCsv($headers);

        foreach ($institutions as $institution) {
            $row = [
                $institution->id,
                $institution->name,
                Institution::getTypes()[$institution->type] ?? $institution->type,
                $institution->website ?? '',
                $institution->description ?? '',
                $institution->accounts_count,
                $institution->created_at?->format('Y-m-d H:i:s') ?? '',
                $institution->updated_at?->format('Y-m-d H:i:s') ?? ''
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

