<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\Agent;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Task
 * 
 * @property int $id
 * @property string $name
 * @property string $command
 * @property string $type
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property Collection|Agent[] $agents
 *
 * @package App\Models\Base
 */
class Task extends Model
{
	use SoftDeletes;
	protected $table = 'tasks';

	protected $fillable = [
		'name',
		'command',
		'type'
	];

	public function agents(): BelongsToMany
	{
		return $this->belongsToMany(Agent::class, 'task_agent')
					->withPivot('uid', 'scheduled_at', 'status', 'output', 'error')
					->withTimestamps();
	}
}
