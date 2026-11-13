<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AvailabilitySlotResource\Pages;
use App\Models\AvailabilitySlot;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AvailabilitySlotResource extends Resource
{
    protected static ?string $model = AvailabilitySlot::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    protected static ?string $navigationLabel = 'Slot Jam Bimbingan';

    protected static ?string $modelLabel = 'Slot Ketersediaan Guru';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('teacher_id')
                    ->label('Tentor / Guru')
                    ->relationship('teacher', 'name', fn (Builder $query) => $query->where('role', 'teacher'))
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('day_of_week')
                    ->label('Hari Rutin (Mingguan)')
                    ->options([
                        'Monday' => 'Senin',
                        'Tuesday' => 'Selasa',
                        'Wednesday' => 'Rabu',
                        'Thursday' => 'Kamis',
                        'Friday' => 'Jumat',
                        'Saturday' => 'Sabtu',
                        'Sunday' => 'Minggu',
                    ]),
                Forms\Components\DatePicker::make('specific_date')
                    ->label('Atau Tanggal Khusus (Opsional)'),
                Forms\Components\TimePicker::make('start_time')
                    ->label('Jam Mulai')
                    ->seconds(false)
                    ->required(),
                Forms\Components\TimePicker::make('end_time')
                    ->label('Jam Selesai')
                    ->seconds(false)
                    ->required(),
                Forms\Components\TextInput::make('room_or_location')
                    ->label('Ruangan / Lokasi (Contoh: R. Diskusi Lt. 2)')
                    ->maxLength(255),
                Forms\Components\Toggle::make('is_active')
                    ->label('Aktifkan Slot Ini')
                    ->default(true)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('teacher.name')
                    ->label('Tentor')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('day_of_week')
                    ->label('Hari')
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'Monday' => 'Senin',
                        'Tuesday' => 'Selasa',
                        'Wednesday' => 'Rabu',
                        'Thursday' => 'Kamis',
                        'Friday' => 'Jumat',
                        'Saturday' => 'Sabtu',
                        'Sunday' => 'Minggu',
                        default => $state ?? '-',
                    })
                    ->badge()
                    ->color('primary')
                    ->sortable(),
                Tables\Columns\TextColumn::make('specific_date')
                    ->label('Tanggal Khusus')
                    ->date()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('start_time')
                    ->label('Jam Mulai')
                    ->time('H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_time')
                    ->label('Jam Selesai')
                    ->time('H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('room_or_location')
                    ->label('Lokasi / Ruang')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('teacher_id')
                    ->label('Filter Tentor')
                    ->relationship('teacher', 'name', fn (Builder $query) => $query->where('role', 'teacher')),
                Tables\Filters\SelectFilter::make('day_of_week')
                    ->label('Filter Hari')
                    ->options([
                        'Monday' => 'Senin',
                        'Tuesday' => 'Selasa',
                        'Wednesday' => 'Rabu',
                        'Thursday' => 'Kamis',
                        'Friday' => 'Jumat',
                        'Saturday' => 'Sabtu',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAvailabilitySlots::route('/'),
            'create' => Pages\CreateAvailabilitySlot::route('/create'),
            'edit' => Pages\EditAvailabilitySlot::route('/{record}/edit'),
        ];
    }
}
