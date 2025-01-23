<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\Group;
use App\Models\Permission;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class GroupPermission
 * 
 * @property int $group_id
 * @property int $permission_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property Group $group
 * @property Permission $permission
 *
 * @package App\Models\Base
 */
class GroupPermission extends Model
{
	use SoftDeletes;
	protected $table = 'group_permissions';
	public $incrementing = false;

	protected $casts = [
		'group_id' => 'int',
		'permission_id' => 'int'
	];

	public function group(): BelongsTo
	{
		return $this->belongsTo(Group::class);
	}

	public function permission(): BelongsTo
	{
		return $this->belongsTo(Permission::class);
	}
}
