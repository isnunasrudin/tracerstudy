<?php

namespace App\Filament\Resources\RespondResource\Pages;

use App\Filament\Resources\RespondResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewRespond extends ViewRecord
{
    protected static string $resource = RespondResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\EditAction::make(),
        ];
    }
}
