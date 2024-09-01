<?php

namespace App\Models;

use App\Enums\Region;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Support\Enums\IconPosition;

class Conference extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
        'status',
        'region',
        'venue_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'region' => Region::class,
        'venue_id' => 'integer',
    ];

    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }

    public function speakers(): BelongsToMany
    {
        return $this->belongsToMany(Speaker::class);
    }

    public function talks(): BelongsToMany
    {
        return $this->belongsToMany(Talk::class);
    }

    public static function getForm(): array
    {
        return [
            Tabs::make('Conference details')
                ->columnSpanFull()
                ->tabs([
                    Tabs\Tab::make('Conference')
                        ->schema([
                            Section::make('Conference details')
                                ->description('Provide some basic information about the conference.')
                                ->icon('heroicon-o-information-circle')
                                ->columnSpanFull()
                                ->columns(2)
                                ->schema([
                                    TextInput::make('name')
                                        ->columnSpanFull()
                                        ->required()
                                        ->maxLength(60),
                                    MarkdownEditor::make('description')
                                        ->columnSpanFull()
                                        ->required(),
                                    DateTimePicker::make('start_date')
                                        ->native(false)
                                        ->required(),
                                    DateTimePicker::make('end_date')
                                        ->native(false)
                                        ->required(),
                                    Fieldset::make('status')
                                        ->columns(1)
                                        ->schema([
                                            Select::make('status')
                                                ->options([
                                                    'draft' => 'Draft',
                                                    'published' => 'Published',
                                                    'archived' => 'Archived',
                                                ])
                                                ->required(),
                                            Toggle::make('is_published')
                                                ->default(true),
                                        ]),


                                ]),
                        ]),
                    Tabs\Tab::make('Locations')
                        ->schema([
                            Section::make('Locations')
                                ->schema([
                                    Select::make('region')
                                        ->live()
                                        ->enum(Region::class)
                                        ->options(Region::class),
                                    Select::make('venue_id')
                                        ->searchable()
                                        ->preload()
                                        ->createOptionForm(Venue::getForm())
                                        ->editOptionForm(Venue::getForm())
                                        ->relationship('venue', 'name', modifyQueryUsing: function (Builder $query, Forms\Get $get) {
                                            return $query->where('region', $get('region'));
                                        })
                                        ->default(null),
                                ])
                        ]),
                ]),



        ];
    }
}
