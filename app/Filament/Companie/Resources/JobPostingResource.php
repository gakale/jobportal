<?php

namespace App\Filament\Companie\Resources;

use App\Filament\Companie\Resources\JobPostingResource\Pages;
use App\Filament\Companie\Resources\JobPostingResource\RelationManagers;
use App\Models\JobPosting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Hidden;


class JobPostingResource extends Resource
{
    protected static ?string $model = JobPosting::class;
    protected static ?string $guard = 'company';

    protected static ?string $navigationIcon = 'heroicon-o-document';
    protected static ?string $slug = 'job-postings';
    protected static ?string $description = 'Manage your job postings';
    protected static ?string $title = 'Job Postings';
    protected static ?string $navigationGroup = "Postings";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Information ')
                ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->label('Title'),
                Forms\Components\TextInput::make('location')
                    ->required()
                    ->maxLength(255)
                        ->label('Location'),
                ])
                ->columns('2'),
                Section::make('Keywords & Score Threshold')
                ->schema([
                    Forms\Components\TagsInput::make('keywords')
                    ->required()
                    ->separator(',')
                    ->label('Keywords'),
                    Forms\Components\TextInput::make('score_threshold')
                    ->required()
                    ->maxLength(255)
                    ->label('Score Threshold'),
                ])
                ->columns(2),
                Section::make('Deadline & Salary')
                ->schema([
                    Forms\Components\DatePicker::make('deadline')
                    ->required()
                    ->label('Deadline'),
                    Forms\Components\TextInput::make('salary')
                    ->maxLength(255)
                    ->label('Salary'),
                ])->columns(2),

                Section::make('Application Link')
                ->schema([
                    Forms\Components\TextInput::make('application_link')
                    ->required()
                    ->url()
                    ->suffixIcon('heroicon-m-globe-alt')
                    ->maxLength(255)
                    ->label('Application Link'),
                    Forms\Components\RichEditor::make('description')
                    ->required()
                    ->toolbarButtons([
                        'attachFiles',
                        'blockquote',
                        'bold',
                        'bulletList',
                        'codeBlock',
                        'h2',
                        'h3',
                        'italic',
                        'link',
                        'orderedList',
                        'redo',
                        'strike',
                        'underline',
                        'undo',
                    ])
                    ->label('Description'),



                ])

            

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
            'index' => Pages\ListJobPostings::route('/'),
            'create' => Pages\CreateJobPosting::route('/create'),
            'edit' => Pages\EditJobPosting::route('/{record}/edit'),
        ];
    }
}
