<?php

namespace App\Filament\Companie\Resources\CandidatureResource\Pages;

use App\Filament\Companie\Resources\CandidatureResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCandidature extends EditRecord
{
    protected static string $resource = CandidatureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
