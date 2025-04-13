<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactResource\Pages;
use App\Models\Contact;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ContactResource extends Resource
{
    protected static ?string $model = Contact::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                $query->where('resaver_id', auth()->user()->id)
                    ->orWhere('sender_id', auth()->user()->id);
            })
            ->columns([
                Tables\Columns\TextColumn::make('sender_id')
                    ->label('Label')
                    ->formatStateUsing(function ($record) {
                        return $record->sender_id === auth()->id() ? 'Sender' : 'Receiver';
                    }),

                Tables\Columns\TextColumn::make('from')
                    ->searchable(),
                Tables\Columns\TextColumn::make('subject')
                    ->searchable(),
                Tables\Columns\TextColumn::make('message')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
//                Tables\Actions\ViewAction::make(),
//                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('reply')
                    ->authorize(fn(Contact $record): bool => $record->sender_id !== auth()->id())
                    ->color('primary')
                    ->form([
                        Textarea::make('message')
                            ->label('Reply')
                            ->required()
                            ->maxLength(500)
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->action(function (Contact $record, array $data) {
                        $record->create([
                            'sender_id' => auth()->id(),
                            'resaver_id' => $record->sender_id,
                            'subject' => $record->subject,
                            'message' => $data['message'],
                        ]);
                    })
            ])
//            php artisan make:filament-resource Order --generate
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ])
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
            'index' => Pages\ListContacts::route('/'),
            //            'create' => Pages\CreateContact::route('/create'),
//            'view' => Pages\ViewContact::route('/{record}'),
            //            'edit' => Pages\EditContact::route('/{record}/edit'),
        ];
    }
}
