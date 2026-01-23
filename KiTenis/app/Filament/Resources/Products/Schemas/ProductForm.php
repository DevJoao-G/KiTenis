<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),

                Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),

                TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('$'),

                TextInput::make('stock')
                    ->required()
                    ->numeric()
                    ->default(0),

                FileUpload::make('image')
                    ->image()
                    ->disk('public')
                    ->directory('products')
                    ->visibility('public')
                    ->imageEditor(), // opcional (remove se não quiser editor)

                Select::make('category')
                    ->options([
                        'masculino' => 'Masculino',
                        'feminino'  => 'Feminino',
                        'infantil'  => 'Infantil',
                    ])
                    ->default('masculino')
                    ->required(),

                Toggle::make('active')
                    ->required(),
            ]);
    }
}
