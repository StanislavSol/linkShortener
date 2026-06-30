<?php

namespace App\Filament\Resources\LinkResource\Pages;

use App\Filament\Resources\LinkResource;
use Filament\Actions;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewLink extends ViewRecord
{
    protected static string $resource = LinkResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Информация о ссылке')
                    ->schema([
                        TextEntry::make('original_url')
                            ->label('Оригинальный URL')
                            ->url(fn ($record) => $record->original_url, true),

                        TextEntry::make('short_url')
                            ->label('Короткая ссылка')
                            ->url(fn ($record) => $record->short_url, true)
                            ->copyable(),

                        TextEntry::make('visits_count')
                            ->label('Всего переходов')
                            ->state(fn ($record) => $record->visits()->count()),

                        TextEntry::make('created_at')
                            ->label('Создана')
                            ->dateTime('d.m.Y H:i'),
                    ])
                    ->columns(2),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
