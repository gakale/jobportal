<?php

namespace App\Filament\Companie\Resources\GenerateQuestionsResource\Pages;

use App\Filament\Companie\Resources\GenerateQuestionsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGenerateQuestions extends ListRecords
{
    protected static string $resource = GenerateQuestionsResource::class;
    protected static ?string $title = 'Questions';

    protected static ?string $navigationGroup = "Questions";

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
