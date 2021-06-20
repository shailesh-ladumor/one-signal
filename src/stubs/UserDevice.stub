<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\UserDevice
 *
 * @property int $id
 * @property int $user_id
 * @property string $device_type
 * @property string $os_player_id
 * @property int $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|UserDevice newModelQuery()
 * @method static Builder|UserDevice newQuery()
 * @method static Builder|UserDevice query()
 * @method static Builder|UserDevice whereCreatedAt($value)
 * @method static Builder|UserDevice whereDeviceType($value)
 * @method static Builder|UserDevice whereId($value)
 * @method static Builder|UserDevice whereIsActive($value)
 * @method static Builder|UserDevice whereOsPlayerId($value)
 * @method static Builder|UserDevice whereUpdatedAt($value)
 * @method static Builder|UserDevice whereUserId($value)
 * @mixin \Eloquent
 */
class UserDevice extends Model
{
    public $table = 'user_devices';

    public $fillable = [
        'user_id',
        'device_type',
        'os_player_id',
        'is_active',
    ];
}
