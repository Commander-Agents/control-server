<?php

namespace App\Models;

use App\Models\Base\Agent as BaseAgent;
use Illuminate\Support\Facades\Crypt;

class Agent extends BaseAgent
{
	protected static function booted()
    {
        static::saving(function ($agent) {
            if (!empty($agent->secret_key)) {
                $agent->secret_key = Crypt::encryptString($agent->secret_key);
            }
        });

        static::retrieved(function ($agent) {
            if (!empty($agent->secret_key)) {
                $agent->secret_key = Crypt::decryptString($agent->secret_key);
            }
        });
    }

	const INACTIVE_TIME = 600; // 600 seconds (10 min) before inactivity
	const HMAC_KEY_LENGTH = 64;
	const STATUS_NEVER_CONNECTED = 0;
	const STATUS_CONNECTED = 1;
	const STATUS_DISCONNECTED = 2;

	public function getColor() {
		$color = "orange";
		if($this->status == Agent::STATUS_NEVER_CONNECTED) {
			$color = "gray";
		} elseif($this->status == Agent::STATUS_DISCONNECTED) {
			$color = "red";
		} elseif($this->status == Agent::STATUS_CONNECTED)  {
			$color = "green";
		}
		return $color;
	}
	public function getStatus() {
		$status = "Unknown (" . $this->status . ")";
		if($this->status == Agent::STATUS_NEVER_CONNECTED) {
			$status = "Never connected";
		} elseif($this->status == Agent::STATUS_DISCONNECTED) {
			$status = "Disconnected";
		} elseif($this->status == Agent::STATUS_CONNECTED)  {
			$status = "Connected";
		}
		return $status;
	}
}
