<?php

namespace App\Services;

use App\Models\Customer;
use Illuminate\Support\Facades\Response;

class CsvExportService
{
    /**
     * Export customers to CSV format compatible with Mailchimp.
     *
     * @param \Illuminate\Database\Eloquent\Collection $customers
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function exportCustomers($customers)
    {
        $filename = 'clienti_' . date('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function() use ($customers) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // CSV Headers (Mailchimp-compatible)
            fputcsv($file, [
                'Email Address',
                'First Name',
                'Last Name',
                'Phone',
                'Total Inquiries',
                'Last Contact Date',
                'GDPR Consent',
                'GDPR Consent Date',
            ]);

            // Export customer data
            foreach ($customers as $customer) {
                // Split name into first and last name (simple approach)
                $nameParts = $this->splitName($customer->name);
                
                $row = [
                    $this->escapeField($customer->primary_email),
                    $this->escapeField($nameParts['first']),
                    $this->escapeField($nameParts['last']),
                    $this->escapeField($customer->phone ?? ''),
                    $customer->total_inquiries,
                    $customer->last_contact_at 
                        ? $customer->last_contact_at->format('Y-m-d H:i:s') 
                        : '',
                    $customer->gdpr_consent ? 'Yes' : 'No',
                    $customer->gdpr_consent_date 
                        ? $customer->gdpr_consent_date->format('Y-m-d H:i:s') 
                        : '',
                ];

                fputcsv($file, $row);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    /**
     * Escape CSV field value to prevent broken CSV files.
     *
     * @param string|null $value
     * @return string
     */
    protected function escapeField(?string $value): string
    {
        if ($value === null) {
            return '';
        }

        // Trim whitespace
        $value = trim($value);

        // Remove or replace newlines and carriage returns
        $value = str_replace(["\r\n", "\r", "\n"], ' ', $value);

        // fputcsv automatically handles escaping, but we ensure clean data
        // Remove control characters except tab, newline, carriage return
        $value = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $value);

        return $value;
    }

    /**
     * Split a full name into first and last name.
     *
     * @param string $fullName
     * @return array{first: string, last: string}
     */
    protected function splitName(string $fullName): array
    {
        $name = trim($fullName);
        $parts = preg_split('/\s+/', $name, 2);

        if (count($parts) === 1) {
            return [
                'first' => $parts[0],
                'last' => '',
            ];
        }

        return [
            'first' => $parts[0],
            'last' => $parts[1],
        ];
    }
}



