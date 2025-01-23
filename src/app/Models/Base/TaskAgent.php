<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\Agent;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class TaskAgent
 * 
 * @property string $uid
 * @property int $task_id
 * @property int $agent_id
 * @property Carbon $scheduled_at
 * @property string $status
 * @property string|null $output
 * @property string|null $error
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Agent $agent
 * @property Task $task
 *
 * @package App\Models\Base
 */
class TaskAgent extends Model
{
	protected $table = 'task_agent';
	protected $primaryKey = 'uid';
	protected $keyType = 'string';
	public $incrementing = false;

	protected $casts = [
		'task_id' => 'int',
		'agent_id' => 'int',
		'scheduled_at' => 'datetime'
	];

	protected $fillable = [
		'uid',
		'task_id',
		'agent_id',
		'scheduled_at',
		'status',
		'output',
		'error'
	];

	public function agent(): BelongsTo
	{
		return $this->belongsTo(Agent::class);
	}

	public function task(): BelongsTo
	{
		return $this->belongsTo(Task::class);
	}
}
