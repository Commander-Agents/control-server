<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\Group;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Permission
 * 
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property Collection|Group[] $groups
 *
 * @package App\Models\Base
 */
class Permission extends Model
{
	use SoftDeletes;
	protected $table = 'permissions';

	protected $fillable = [
		'name',
		'description'
	];

	public function groups(): BelongsToMany
	{
		return $this->belongsToMany(Group::class, 'group_permissions')
					->withPivot('deleted_at')
					->withTimestamps();
	}
}
