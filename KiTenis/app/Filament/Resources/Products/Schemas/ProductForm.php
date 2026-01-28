<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\DateTimePicker;
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
                    ->label('Preço')
                    ->required()
                    ->numeric()
                    ->prefix('R$'),

                TextInput::make('discount_percentage')
                    ->label('Desconto (%)')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(90)
                    ->step(0.01)
                    ->suffix('%')
                    ->helperText('O preço com desconto é calculado automaticamente no site.'),

                DateTimePicker::make('promotion_start')
                    ->label('Início da promoção')
                    ->seconds(false)
                    ->helperText('Se vazio, a promoção pode iniciar imediatamente.'),

                DateTimePicker::make('promotion_end')
                    ->label('Fim da promoção')
                    ->seconds(false)
                    ->helperText('Se vazio, a promoção não tem data de término.'),

                Toggle::make('featured_in_carousel')
                    ->label('Mostrar no carousel de promoções')
                    ->helperText('O produto aparece na Home apenas quando a promoção estiver ativa.'),

                TextInput::make('stock')
                    ->label('Estoque')
                    ->required()
                    ->numeric()
                    ->default(0),

                FileUpload::make('image')
                    ->image()
                    ->disk('public')
                    ->directory('products')
                    ->visibility('public')
                    ->imageEditor(),

                Select::make('category')
                    ->options([
                        'masculino' => 'Masculino',
                        'feminino'  => 'Feminino',
                        'infantil'  => 'Infantil',
                    ])
                    ->default('masculino')
                    ->required(),

                Toggle::make('active')
                    ->label('Ativo')
                    ->required(),
            ]);
    }
}
