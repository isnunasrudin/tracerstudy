<?php

namespace App\Filament\Resources\RespondResource\Pages;

use App\Filament\Resources\RespondResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRespond extends EditRecord
{
    protected static string $resource = RespondResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
