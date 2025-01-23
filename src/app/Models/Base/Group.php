<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\Agent;
use App\Models\Permission;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Group
 * 
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property Collection|Agent[] $agents
 * @property Collection|Permission[] $permissions
 *
 * @package App\Models\Base
 */
class Group extends Model
{
	use SoftDeletes;
	protected $table = 'groups';

	protected $fillable = [
		'name'
	];

	public function agents(): BelongsToMany
	{
		return $this->belongsToMany(Agent::class, 'agent_groups')
					->withTimestamps();
	}

	public function permissions(): BelongsToMany
	{
		return $this->belongsToMany(Permission::class, 'group_permissions')
					->withPivot('deleted_at')
					->withTimestamps();
	}
}
