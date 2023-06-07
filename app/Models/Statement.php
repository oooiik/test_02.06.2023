<?php

namespace App\Models;

use App\Filters\StatementFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Oooiik\LaravelQueryFilter\Traits\Model\Filterable;

/**
 * @property int $id
 * @property string $title
 * @property string $description
 * @property int $number
 * @property string $date
 * @property int $user_id
 *
 * @property-read User $user
 */
class Statement extends Model
{
    use HasFactory;
    use Filterable;

    protected $fillable = [
        'title',
        'description',
        'number',
        'date',
        'user_id'
    ];

    public $defaultFilter = StatementFilter::class;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function attachments()
    {
        return $this->morphMany(
            Attachment::class,
            'attachment',
            'attachment_type',
            'attachment_id'
        );
    }
}
