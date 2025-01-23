<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\Group;
use App\Models\OperatingSystem;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Agent
 * 
 * @property int $id
 * @property string $name
 * @property string $status
 * @property Carbon|null $last_contact
 * @property int|null $operating_system_id
 * @property string|null $hostname
 * @property int|null $port
 * @property string|null $protocol
 * @property string|null $secret_key
 * @property string|null $secret_key_hash
 * @property string|null $uid
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property OperatingSystem|null $operating_system
 * @property Collection|Group[] $groups
 * @property Collection|Task[] $tasks
 *
 * @package App\Models\Base
 */
class Agent extends Model
{
	use SoftDeletes;
	protected $table = 'agents';

	protected $casts = [
		'last_contact' => 'datetime',
		'operating_system_id' => 'int',
		'port' => 'int',
		'status' => 'int',
	];

	protected $hidden = [
		'secret_key',
		'secret_key_hash'
	];

	protected $fillable = [
		'name',
		'status',
		'last_contact',
		'operating_system_id',
		'hostname',
		'port',
		'protocol',
		'secret_key',
		'secret_key_hash',
		'uid'
	];

	public function operating_system(): BelongsTo
	{
		return $this->belongsTo(OperatingSystem::class);
	}

	public function groups(): BelongsToMany
	{
		return $this->belongsToMany(Group::class, 'agent_groups')
					->withTimestamps();
	}

	public function tasks(): BelongsToMany
	{
		return $this->belongsToMany(Task::class, 'task_agent')
					->withPivot('uid', 'scheduled_at', 'status', 'output', 'error')
					->withTimestamps();
	}
}
