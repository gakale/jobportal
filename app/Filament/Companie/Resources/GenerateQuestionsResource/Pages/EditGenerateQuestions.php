<?php

namespace App\Filament\Companie\Resources\GenerateQuestionsResource\Pages;

use App\Filament\Companie\Resources\GenerateQuestionsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGenerateQuestions extends EditRecord
{
    protected static string $resource = GenerateQuestionsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
