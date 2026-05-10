<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class EmailExportController extends Controller
{
    /**
     * Export all emails from the database to CSV file.
     */
    public function export(): Response
    {
        // Get all unique emails from users table
        $emails = User::query()
            ->whereNotNull('email')
            ->where('email', '!=', '')
            ->pluck('email')
            ->unique()
            ->sort()
            ->values();

        // Create CSV content
        $csvContent = "Email\n";
        foreach ($emails as $email) {
            $csvContent .= '"' . str_replace('"', '""', $email) . '"' . "\n";
        }

        // Generate filename with timestamp
        $filename = 'emails_export_' . now()->format('Y-m-d_H-i-s') . '.csv';

        // Return response with CSV download
        return response($csvContent, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
        ]);
    }

    /**
     * Export emails with user details to CSV file.
     */
    public function exportWithDetails(): Response
    {
        // Get all users with their details
        $users = User::query()
            ->whereNotNull('email')
            ->where('email', '!=', '')
            ->orderBy('email')
            ->get(['name', 'email', 'phone', 'role', 'location', 'created_at']);

        // Create CSV content with headers
        $csvContent = "Name,Email,Phone,Role,Location,Registered At\n";

        foreach ($users as $user) {
            $name = $this->escapeCsv($user->name);
            $email = $this->escapeCsv($user->email);
            $phone = $this->escapeCsv($user->phone ?? '');
            $role = $this->escapeCsv($user->role ?? '');
            $location = $this->escapeCsv($user->location ?? '');
            $registeredAt = $user->created_at ? $user->created_at->format('Y-m-d H:i:s') : '';

            $csvContent .= "{$name},{$email},{$phone},{$role},{$location},{$registeredAt}\n";
        }

        // Generate filename with timestamp
        $filename = 'emails_with_details_' . now()->format('Y-m-d_H-i-s') . '.csv';

        return response($csvContent, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
        ]);
    }

    /**
     * Escape CSV value properly.
     */
    private function escapeCsv(?string $value): string
    {
        if ($value === null) {
            return '';
        }

        // If value contains comma, newline, or double quote, wrap in quotes
        if (str_contains($value, ',') || str_contains($value, '"') || str_contains($value, "\n") || str_contains($value, "\r")) {
            return '"' . str_replace('"', '""', $value) . '"';
        }

        return $value;
    }
}
