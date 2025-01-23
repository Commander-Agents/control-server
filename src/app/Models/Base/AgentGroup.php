<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\Agent;
use App\Models\Group;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class AgentGroup
 * 
 * @property int $agent_id
 * @property int $group_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Agent $agent
 * @property Group $group
 *
 * @package App\Models\Base
 */
class AgentGroup extends Model
{
	protected $table = 'agent_groups';
	public $incrementing = false;

	protected $casts = [
		'agent_id' => 'int',
		'group_id' => 'int'
	];

	public function agent(): BelongsTo
	{
		return $this->belongsTo(Agent::class);
	}

	public function group(): BelongsTo
	{
		return $this->belongsTo(Group::class);
	}
}
