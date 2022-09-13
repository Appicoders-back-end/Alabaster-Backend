<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    public function getImageUrl()
    {
        if ($this['image'] == null) {
            return null;
        }

        return url('/storage/uploads/').'/'. $this['image'];
    }
}
