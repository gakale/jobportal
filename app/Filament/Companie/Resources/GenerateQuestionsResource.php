<?php

namespace App\Filament\Companie\Resources;

use App\Filament\Companie\Resources\GenerateQuestionsResource\Pages;
use App\Filament\Companie\Resources\GenerateQuestionsResource\RelationManagers;
use App\Models\Question;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GenerateQuestionsResource extends Resource
{
    protected static ?string $model = Question::class;

    protected static ?string $navigationIcon = 'heroicon-o-square-3-stack-3d';
    protected static ?string $navigationGroup = "Question";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('question_text')
                    ->label('Questions'),
                    Tables\Columns\TextColumn::make('correct_answer')
                    ->label('Correct answer'),
                    Tables\Columns\TextColumn::make('choices')
                    ->label('Choice')
                    ->formatStateUsing(function ($state) {
                        $choices = json_decode($state, true);
                        return implode(', ', $choices);
                    })
                    ->wrap(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGenerateQuestions::route('/'),
            'create' => Pages\CreateGenerateQuestions::route('/create'),
            'edit' => Pages\EditGenerateQuestions::route('/{record}/edit'),
        ];
    }
}