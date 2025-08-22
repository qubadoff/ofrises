<?php

namespace App\Filament\Resources;

use App\Enum\Worker\WorkerStatusEnum;
use App\Filament\Resources\WorkerResource\Pages;
use App\Models\Customer;
use App\Models\Language;
use App\Models\LanguageLevel;
use App\Models\WorkArea;
use App\Models\Worker;
use Filament\Actions\ForceDeleteAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class WorkerResource extends Resource
{
    protected static ?string $model = Worker::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Customer')
                    ->schema([
                        Select::make('customer_id')
                            ->label('Customer')
                            ->options(fn () =>
                            Customer::query()
                                ->orderBy('name')
                                ->get()
                                ->mapWithKeys(fn ($c) => [
                                    $c->id => "{$c->name} {$c->surname}"
                                ])
                            )
                            ->searchable()
                            ->preload()
                            ->required()
                            ->reactive()
                            ->dehydrated(),

                    ]),


                Section::make('Work Areas')
                    ->schema([
                        Select::make('work_area_ids')
                            ->label('Select Work Areas')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->dehydrated(false)
                            ->options(function () {
                                $areas = WorkArea::query()
                                    ->select(['id', 'name', 'parent_id'])
                                    ->get();

                                $byId  = $areas->keyBy('id');
                                $cache = [];

                                $path = function ($id) use (&$path, $byId, &$cache) {
                                    if (isset($cache[$id])) return $cache[$id];
                                    $n = $byId[$id] ?? null;
                                    if (!$n) return '';
                                    if (!$n->parent_id) return $cache[$id] = $n->name;
                                    return $cache[$id] = $path($n->parent_id) . ' › ' . $n->name;
                                };

                                return $areas
                                    ->mapWithKeys(fn ($a) => [$a->id => $path($a->id)])
                                    ->sort()
                                    ->all();
                            })
                            ->afterStateHydrated(function (Select $component, ?Worker $record, Get $get, Set $set) {
                                if (!$record) return;
                                $customerId = $get('customer_id');
                                if (!$customerId) return;

                                $ids = $record->workAreas()
                                    ->wherePivot('customer_id', $customerId)
                                    ->pluck('work_areas.id')
                                    ->all();

                                $component->state($ids);
                                $set('work_area_ids_hidden', $ids);
                            })
                            ->afterStateUpdated(function ($state, Set $set) {
                                $set('work_area_ids_hidden', $state ?: []);
                            }),

                        Hidden::make('work_area_ids_hidden')
                            ->dehydrated(true)
                            ->default([]),
                    ]),

                Section::make('Education')
                    ->schema([
                        Repeater::make('educations')
                            ->relationship('educations')
                            ->label('Educations')
                            ->defaultItems(0)
                            ->addActionLabel('Add education')
                            ->schema([
                                Hidden::make('customer_id')
                                    ->dehydrated(true)
                                    ->default(fn (Get $get) => (int) $get('customer_id')),

                                Select::make('education_type')
                                    ->label('Education Type')
                                    ->options([
                                        0 => 'High School',
                                        1 => 'Bachelor',
                                        2 => 'Master',
                                        3 => 'PhD',
                                        4 => 'Other',
                                    ])
                                    ->required()
                                    ->native(false),

                                TextInput::make('university_name')
                                    ->label('University / Institution')
                                    ->required()
                                    ->maxLength(255),

                                DatePicker::make('start_date')
                                    ->label('Start Date')
                                    ->native(false)
                                ->required(),

                                DatePicker::make('end_date')
                                    ->label('End Date')
                                    ->native(false)
                                    ->disabled(fn (Get $get) => (bool) $get('is_present'))
                                    ->rule(function (Get $get) {
                                        if (! $get('is_present')) {
                                            return 'after_or_equal:start_date';
                                        }
                                        return null;
                                    }),

                                Toggle::make('is_present')
                                    ->label('Still Studying / Present')
                                    ->inline(false)
                                    ->default(false)
                                    ->reactive(),

                                Textarea::make('description')
                                    ->label('Description')
                                    ->rows(3)
                                    ->maxLength(2000),
                            ])
                            ->columns(2)
                            ->afterStateHydrated(function (Repeater $component, Get $get) {
                                $cid = (int) $get('customer_id');
                                $state = collect($component->getState() ?? [])
                                    ->map(function ($row) use ($cid) {
                                        $row['customer_id'] = $cid;
                                        return $row;
                                    })->all();
                                $component->state($state);
                            })
                            ->mutateRelationshipDataBeforeCreateUsing(function (array $data, Get $get) {
                                $data['customer_id'] = (int) $get('customer_id');
                                return $data;
                            })
                            ->mutateRelationshipDataBeforeSaveUsing(function (array $data, Get $get) {
                                $data['customer_id'] = (int) $get('customer_id');
                                return $data;
                            }),
                    ])
                    ->columns(1),

                Section::make('Languages')
                    ->schema([
                        Repeater::make('languages')
                            ->relationship('languages')
                            ->label('Language Levels')
                            ->defaultItems(0)
                            ->addActionLabel('Add language')
                            ->schema([
                                Hidden::make('customer_id')
                                    ->dehydrated(true)
                                    ->default(fn (Get $get) => (int) $get('customer_id')),

                                Select::make('language_id')
                                    ->label('Language')
                                    ->options(fn () => Language::query()
                                        ->orderBy('name')
                                        ->pluck('name', 'id'))
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->disableOptionsWhenSelectedInSiblingRepeaterItems(),

                                Select::make('language_level_id')
                                    ->label('Level')
                                    ->options(fn () => LanguageLevel::query()
                                        ->pluck('name', 'id'))
                                    ->required()
                                    ->searchable()
                                    ->preload(),
                            ])
                            ->columns()
                            ->afterStateHydrated(function (Repeater $component, Get $get) {
                                $cid = (int) $get('customer_id');
                                $state = collect($component->getState() ?? [])
                                    ->map(function ($row) use ($cid) {
                                        $row['customer_id'] = $cid;
                                        return $row;
                                    })->all();
                                $component->state($state);
                            })
                            ->mutateRelationshipDataBeforeCreateUsing(function (array $data, Get $get) {
                                $data['customer_id'] = (int) $get('customer_id');
                                return $data;
                            })
                            ->mutateRelationshipDataBeforeSaveUsing(function (array $data, Get $get) {
                                $data['customer_id'] = (int) $get('customer_id');
                                return $data;
                            }),
                    ])
                    ->columns(1),

                Section::make('Work Experiences')
                    ->schema([
                        Repeater::make('workExperience')
                            ->relationship('workExperience')
                        ->label('Work Experience')
                        ->defaultItems(0)
                        ->addActionLabel('Add work experience')
                        ->schema([
                            Hidden::make('customer_id')
                                ->dehydrated(true)
                                ->default(fn (Get $get) => (int) $get('customer_id')),
                            TextInput::make('company_name')->required(),
                            TextInput::make('position_name')->required(),

                            DatePicker::make('start_date')
                                ->label('Start Date')
                                ->native(false)
                                ->required(),

                            DatePicker::make('end_date')
                                ->label('End Date')
                                ->native(false)
                                ->disabled(fn (Get $get) => (bool) $get('is_present'))
                                ->rule(function (Get $get) {
                                    if (! $get('is_present')) {
                                        return 'after_or_equal:start_date';
                                    }
                                    return null;
                                }),

                            Toggle::make('is_present')
                                ->label('Still Studying / Present')
                                ->inline(false)
                                ->default(false)
                                ->reactive(),

                            Textarea::make('workload'),
                            Textarea::make('description'),
                        ])
                        ->columns()
                            ->afterStateHydrated(function (Repeater $component, Get $get) {
                                $cid = (int) $get('customer_id');
                                $state = collect($component->getState() ?? [])
                                    ->map(function ($row) use ($cid) {
                                        $row['customer_id'] = $cid;
                                        return $row;
                                    })->all();
                                $component->state($state);
                            })
                            ->mutateRelationshipDataBeforeCreateUsing(function (array $data, Get $get) {
                                $data['customer_id'] = (int) $get('customer_id');
                                return $data;
                            })
                            ->mutateRelationshipDataBeforeSaveUsing(function (array $data, Get $get) {
                                $data['customer_id'] = (int) $get('customer_id');
                                return $data;
                            }),
                    ]),

                Section::make([
                    Select::make('work_type_id')
                        ->relationship('workType', 'name')
                        ->multiple()
                        ->required()
                    ->label('Work Type')
                ]),

                Section::make([
                    TextInput::make('location')->required(),
                    TextInput::make('latitude')->required(),
                    TextInput::make('longitude')->required(),
                ])->columns(3),

                Section::make([
                    TextInput::make('salary_min')->required(),
                    TextInput::make('salary_max')->required(),

                    Select::make('currency_id')
                        ->relationship('currency', 'name')
                        ->required()
                        ->label('Currency'),

                    Select::make('salary_type_id')
                        ->relationship('salaryType', 'name')
                        ->required()
                        ->label('Salary Type'),
                ])->columns(4),

                Section::make([
                    Select::make('work_expectation_id')
                    ->relationship('workExpectation', 'name')
                    ->multiple()
                ]),

                Section::make([
                    Select::make('citizenship_id')
                    ->relationship('citizenship', 'name')
                    ->multiple()
                ]),

                Section::make([
                    TextInput::make('birth_place'),
                ]),

                Section::make([
                    Select::make('marital_status_id')
                    ->relationship('maritalStatus', 'name')
                ]),

                Section::make([
                    TextInput::make('height')->numeric()->suffix(' SM'),
                    TextInput::make('weight')->numeric()->suffix(' KQ'),
                ])->columns(),

                Section::make([
                    Select::make('military_status_id')
                    ->relationship('militaryStatus', 'name')
                ]),

                Section::make([
                    Select::make('have_a_child')
                        ->label('Have a Child?')
                        ->options([
                            true  => 'Have a child',
                            false => 'No child',
                        ]),
                ]),

                Section::make([
                    Select::make('driver_license_id')
                    ->relationship('driverLicense', 'name')
                    ->multiple(),

                    Select::make('car_model_id')
                    ->relationship('carModel', 'name')
                    ->multiple(),
                ])->columns(),

                Section::make([
                    Select::make('hobby_id')
                    ->relationship('hobby', 'name')
                    ->multiple(),

                    Select::make('hard_skill_id')
                        ->relationship('hardSkill', 'name')
                        ->multiple(),

                    Select::make('soft_skill_id')
                        ->relationship('softSkill', 'name')
                        ->multiple(),
                ])->columns(3),

                Section::make([
                    TextArea::make('description')->nullable(),

                    Select::make('status')
                    ->options([
                        WorkerStatusEnum::PENDING->value => WorkerStatusEnum::PENDING->getLabel(),
                        WorkerStatusEnum::ACTIVE->value => WorkerStatusEnum::ACTIVE->getLabel(),
                        WorkerStatusEnum::INACTIVE->value => WorkerStatusEnum::INACTIVE->getLabel(),
                        WorkerStatusEnum::DEACTIVATED->value => WorkerStatusEnum::DEACTIVATED->getLabel(),
                        WorkerStatusEnum::DELETED->value => WorkerStatusEnum::DELETED->getLabel(),
                    ])->default(WorkerStatusEnum::PENDING->value)->required(),
                ])->columns(),

                Section::make('Media')
                    ->schema([
                        Hidden::make('customer_id')
                            ->dehydrated(true)
                            ->default(fn (Get $get) => (int) $get('customer_id')),

                        FileUpload::make('photos')
                            ->label('Photos')
                            ->image()
                            ->multiple()
                            ->reorderable()
                            ->appendFiles()
                            ->downloadable()
                            ->openable()
                            ->directory('workers/photos')
                            ->preserveFilenames()
                            ->maxFiles(20)
                            ->maxSize(15000)
                            ->helperText('Photos are multiple'),

                        FileUpload::make('video')
                            ->label('Video')
                            ->acceptedFileTypes(['video/mp4', 'video/quicktime', 'video/x-matroska'])
                            ->directory('workers/videos')
                            ->preserveFilenames()
                            ->maxSize(204800)
                            ->helperText('Only one video file allowed'),
                    ])
                    ->columns()
                    ->relationship('photoAndVideo')
                    ->afterStateHydrated(function ($component, Get $get) {
                        $cid = (int) $get('customer_id');
                        $state = $component->getState() ?? [];
                        $state['customer_id'] = $cid;
                        $component->state($state);
                    })
                    ->mutateRelationshipDataBeforeCreateUsing(function (array $data, Get $get) {
                        $data['customer_id'] = (int) $get('customer_id');
                        return $data;
                    })
                    ->mutateRelationshipDataBeforeSaveUsing(function (array $data, Get $get) {
                        $data['customer_id'] = (int) $get('customer_id');
                        return $data;
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('customer.name')->searchable(),
                TextColumn::make('customer.surname')->searchable(),
                TextColumn::make('status')->badge(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    ForceDeleteAction::make(),
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
            'index' => Pages\ListWorkers::route('/'),
            'create' => Pages\CreateWorker::route('/create'),
            'edit' => Pages\EditWorker::route('/{record}/edit'),
        ];
    }
}
