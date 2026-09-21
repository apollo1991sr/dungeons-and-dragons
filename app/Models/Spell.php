<?php

namespace App\Models;

use App\Enums\SpellClass;
use App\Enums\SpellSchool;
use Illuminate\Database\Eloquent\Casts\AsEnumCollection;
use Illuminate\Database\Eloquent\Model;

class Spell extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'image',
        'level',
        'school',
        'ritual',
        'classes',
        'casting_time',
        'range',
        'component_verbal',
        'component_somatic',
        'component_material',
        'material',
        'duration',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'level' => 'integer',
            'school' => SpellSchool::class,
            'ritual' => 'boolean',

            'classes' => AsEnumCollection::of(
                SpellClass::class
            ),

            'component_verbal' => 'boolean',
            'component_somatic' => 'boolean',
            'component_material' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Spell $spell): void {
            $fields = [
                'name',
                'slug',
                'image',
                'casting_time',
                'range',
                'material',
                'duration',
                'description',
            ];

            foreach ($fields as $field) {
                if (is_string($spell->{$field})) {
                    $spell->{$field} = trim($spell->{$field});
                }
            }

            if (!$spell->component_material) {
                $spell->material = null;
            }
        });
    }

    public function levelLabel(): string
    {
        return $this->level === 0
            ? 'Замовляння'
            : $this->level . ' рівень';
    }

    public function componentsLabel(): string
    {
        $components = [];

        if ($this->component_verbal) {
            $components[] = 'С';
        }

        if ($this->component_somatic) {
            $components[] = 'Т';
        }

        if ($this->component_material) {
            $components[] = 'М';
        }

        return implode(', ', $components);
    }
}
