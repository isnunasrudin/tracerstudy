<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RespondResource\Pages;
use App\Models\Hasil;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RespondResource extends Resource
{
    protected static ?string $model = Hasil::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationGroup = "Surveys";
    protected static ?string $label = "Hasil";

    protected static ?string $modelLabel = "Hasil";
    protected static ?string $pluralLabel = "Hasil";

    protected static ?string $pluralModelLabel = "Hasil";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make('Data Siswa')
                    ->relationship('student')
                    ->schema([
                        TextInput::make('name'),
                        FileUpload::make('avatar')
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('student.avatar')->label('Foto')->circular(),
                TextColumn::make('student.name')->label('Nama')->searchable(),
                TextColumn::make('created_at')->label('Mengisi Pada')->since()->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                // Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            RespondResource\RelationManagers\AnswersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListResponds::route('/'),
            'view' => Pages\ViewRespond::route('/{record}'),
        ];
    }
}
