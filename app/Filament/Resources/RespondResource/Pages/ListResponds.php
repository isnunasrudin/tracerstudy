<?php

namespace App\Filament\Resources\RespondResource\Pages;

use App\Filament\Resources\RespondResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListResponds extends ListRecords
{
    protected static string $resource = RespondResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
