<?php

namespace App\Filament\Companie\Resources;

use App\Filament\Companie\Resources\TestResource\Pages;
use App\Filament\Companie\Resources\TestResource\RelationManagers;
use App\Models\Test;
use App\Models\JobPosting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth; 
class TestResource extends Resource
{
    protected static ?string $model = Test::class;

    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';
    protected static ?string $navigationLabel = "Generate Question";
    protected static ?string $navigationGroup = "List Questions";


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Information')
                ->schema([
                    Forms\Components\Hidden::make('company_id')
                    ->default(fn () => Auth::guard('company')->id()) 
                    ->required(),
                    Forms\Components\Select::make('job_posting_id')
                        ->label('Offre d\'emploi')
                        ->options(JobPosting::all()->pluck('title', 'id'))
                        ->required(),
                    Forms\Components\TextInput::make('number_of_questions')
                        ->label('Nombre de questions')
                        ->numeric()
                        ->required(),
                    Forms\Components\TextInput::make('duration')
                        ->label('Durée du test (en minutes)')
                        ->numeric()
                        ->required(),
                    Forms\Components\Select::make('language')
                        ->label('Langue')
                        ->options([
                            'en' => 'Anglais',
                            'fr' => 'Français'
                        ])
                        ->default('en')
                        ->required(),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('jobPosting.title')
                    ->label('Offre d\'emploi'),
                Tables\Columns\TextColumn::make('number_of_questions')
                    ->label('Nombre de questions'),
                Tables\Columns\TextColumn::make('duration')
                    ->label('Durée (minutes)'),
                Tables\Columns\TextColumn::make('language')
                    ->label('Langue'),

            ])
            ->filters([
                //
            ])
            ->actions([
              //  Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListTests::route('/'),
            'create' => Pages\CreateTest::route('/create'),
          //  'edit' => Pages\EditTest::route('/{record}/edit'),
        ];
    }
}
