<?php

namespace App\Filament\Exports;

use App\Models\Donation;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class DonationExporter extends Exporter
{
    protected static ?string $model = Donation::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('receipt_number'),
            ExportColumn::make('donor_name'),
            ExportColumn::make('donor_email'),
            ExportColumn::make('is_anonymous'),
            ExportColumn::make('amount'),
            ExportColumn::make('frequency'),
            ExportColumn::make('status'),
            ExportColumn::make('paymentMethod.name')->label('Payment Method'),
            ExportColumn::make('campaign.title')->label('Campaign'),
            ExportColumn::make('project.title')->label('Project'),
            ExportColumn::make('gateway_reference'),
            ExportColumn::make('approved_at'),
            ExportColumn::make('created_at'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your donation export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
