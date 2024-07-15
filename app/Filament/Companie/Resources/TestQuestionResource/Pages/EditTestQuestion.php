<?php

namespace App\Filament\Companie\Resources\TestQuestionResource\Pages;

use App\Filament\Companie\Resources\TestQuestionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTestQuestion extends EditRecord
{
    protected static string $resource = TestQuestionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}