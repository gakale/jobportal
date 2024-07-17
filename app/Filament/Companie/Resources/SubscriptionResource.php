<?php

namespace App\Filament\Companie\Resources;

use App\Filament\Companie\Resources\SubscriptionResource\Pages;
use App\Filament\Companie\Resources\SubscriptionResource\RelationManagers;
use App\Models\Subscription;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use App\Models\SubscriptionPlan;
use Filament\Forms\Components\Select;
class SubscriptionResource extends Resource
{
    protected static ?string $model = Subscription::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $navigationGroup = "Subscription";

    protected static ?string $title = 'Souscription';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Information')
                ->schema([
                    Select::make('subscription_plan_id')
                        ->options(SubscriptionPlan::all()->pluck('name', 'id'))
                        ->label('Plan de souscription')
                        ->required()
                        ->disabled(),
                    Forms\Components\DatePicker::make('trial_ends_at')
                            ->label('Date de fin de période d\'essai')
                            ->required()
                            ->disabled(),
                    Forms\Components\DatePicker::make('subscription_ends_at')
                                ->label('Date de fin de souscription')
                                ->required()
                                ->disabled(),

                ])

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('subscription_plan.name')
                    ->label('Plan de souscription'),
                Tables\Columns\TextColumn::make('trial_ends_at')
                    ->label('Fin de la période d\'essai')
                    ->date(),
                Tables\Columns\TextColumn::make('ends_at')
                    ->label('Fin de l\'abonnement')
                    ->date(),
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
            'index' => Pages\ListSubscriptions::route('/'),
            'edit' => Pages\EditSubscription::route('/{record}/edit'),
        ];
    }
}
