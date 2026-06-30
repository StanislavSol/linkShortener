<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LinkVisitResource\Pages;
use App\Models\LinkVisit;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LinkVisitResource extends Resource
{
    protected static ?string $model = LinkVisit::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationLabel = 'Статистика переходов';

    protected static ?string $modelLabel = 'Переход';

    protected static ?string $pluralModelLabel = 'Переходы';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('link.original_url')
                    ->label('Ссылка')
                    ->searchable()
                    ->limit(50),

                Tables\Columns\TextColumn::make('ip_address')
                    ->label('IP-адрес')
                    ->searchable(),

                Tables\Columns\TextColumn::make('visited_at')
                    ->label('Дата перехода')
                    ->dateTime('d.m.Y H:i:s')
                    ->sortable(),
            ])
            ->filters([])
            ->actions([])
            ->bulkActions([])
            ->defaultSort('visited_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLinkVisits::route('/'),
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('link', function ($query) {
                $query->where('user_id', auth()->id());
            });
    }
}
