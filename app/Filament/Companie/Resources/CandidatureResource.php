<?php

namespace App\Filament\Companie\Resources;

use App\Filament\Companie\Resources\CandidatureResource\Pages;
use App\Filament\Companie\Resources\CandidatureResource\RelationManagers;
use App\Models\Candidature;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CandidatureResource extends Resource
{
    protected static ?string $model = Candidature::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'Managements of Candidatures';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('candidats.name')
                    ->numeric()
                    ->label('Candidat'),
                Tables\Columns\TextColumn::make('job_posting_id')
                    ->numeric()
                    ->label('Offre d\'emploi'),
                Tables\Columns\TextColumn::make('score_threshold')
                    ->label('scorethreshold'),
                
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
            'index' => Pages\ListCandidatures::route('/'),
            'create' => Pages\CreateCandidature::route('/create'),
            'edit' => Pages\EditCandidature::route('/{record}/edit'),
        ];
    }
}
