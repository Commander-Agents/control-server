<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\Agent;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class OperatingSystem
 * 
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property Collection|Agent[] $agents
 *
 * @package App\Models\Base
 */
class OperatingSystem extends Model
{
	use SoftDeletes;
	protected $table = 'operating_system';

	protected $fillable = [
		'name'
	];

	public function agents(): HasMany
	{
		return $this->hasMany(Agent::class);
	}
}
