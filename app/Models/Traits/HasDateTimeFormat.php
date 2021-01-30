<?php

namespace App\Models\Traits;

use Carbon\Carbon;

trait HasDateTimeFormat {

    public function getCreatedAtAttribute($value)
    {
        return Carbon::createFromFormat("Y-m-d H:i:s", $value)->format("Y-m-d H:i:s");
    }
    public function getUpdatedAtAttribute($value)
    {
        return Carbon::createFromFormat("Y-m-d H:i:s", $value)->format("Y-m-d H:i:s");
    }
}
