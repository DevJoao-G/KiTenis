<?php

namespace App\Filament\Resources\Brands\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\ToggleColumn;


class BrandsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
    ImageColumn::make('logo_path')
        ->label('Logo')
        ->disk('public')
        ->circular()
        ->toggleable(),

    TextColumn::make('name')
        ->label('Nome')
        ->searchable(),

    ToggleColumn::make('is_active')
        ->label('Ativa')
        ->sortable(),
])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
