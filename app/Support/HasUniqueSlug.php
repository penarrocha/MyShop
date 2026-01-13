<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use RuntimeException;

trait HasUniqueSlug
{
    /**
     * Genera un slug único para el modelo dado.
     *
     * @param  class-string<Model>  $modelClass
     */

    public const MAX_ATTEMPTS = 10;

    protected function uniqueSlugForModel(
        string $modelClass,
        string $baseSlug,
        ?int $ignoreId = null,
        string $fallback = 'item',
        string $slugColumn = 'slug'
    ): string {
        $ignoreId = ($ignoreId && $ignoreId > 0) ? $ignoreId : null;

        $slug = Str::slug($baseSlug);
        if ($slug === '') {
            $slug = Str::slug($fallback) ?: 'item';
        }

        // Builder base (incluye trashed si el modelo tiene SoftDeletes)
        /** @var Builder $baseQuery */
        $baseQuery = method_exists($modelClass, 'withTrashed')
            ? $modelClass::withTrashed()
            : $modelClass::query();

        $exists = function (string $candidate) use ($baseQuery, $slugColumn, $ignoreId): bool {
            $q = (clone $baseQuery)->where($slugColumn, $candidate);

            if ($ignoreId !== null) {
                $q->where('id', '!=', $ignoreId);
            }

            return $q->exists();
        };

        if (!$exists($slug)) {
            return $slug;
        }

        $i = 2;
        while ($i < static::MAX_ATTEMPTS + 1) {
            $candidate = "{$slug}-{$i}";

            if (!$exists($candidate)) {
                return $candidate;
            }

            ++$i;
        }
        throw new RuntimeException(
            "No se pudo generar un slug único para '{$slug}' en {$modelClass} tras " . static::MAX_ATTEMPTS . " intentos."
        );
    }
}
