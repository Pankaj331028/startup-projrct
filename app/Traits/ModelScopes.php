<?php

namespace App\Traits;

/**
 * Model scopes trait to filter models based in is_active column
 */
trait ModelScopes {
    // that trait is used for filtering Models eloquent data
    // scopes
    public function scopeIsActive($query) {
        return $query->where('is_active', '=', 1);
    }

    public function scopeIsInactive($query) {
        return $query->whereIn('is_active', [2, 4]);
    }

    public function scopeIsDeleted($query) {
        return $query->whereIn('is_active', [3, 5]);
    }

    public function scopeIsActiveIn($query, $statuses) {
        return $query->whereIn('is_active', $statuses);
    }

    public function scopeOnlyAssigned($query, $columnName = "id", $ids = []) {
        return $query->whereIn($columnName, $ids);
    }
}
