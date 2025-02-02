<?php

namespace App\Models;

use App\Models\_Traits\HasCompositeKey;
use App\Models\Base\TaskAgent as BaseTaskAgent;

class TaskAgent extends BaseTaskAgent
{
    const STATUS_PENDING = "pending";
    const STATUS_INPROGRESS = "in_progress";
    const STATUS_COMPLETED = "completed";
    const STATUS_FAILED = "failed";
	const STATUS_ACKNOWLEDGE = "acknowledged";

    public function getColor() {
		$color = "gray";
		if($this->status == TaskAgent::STATUS_INPROGRESS) {
			$color = "orange";
		} elseif($this->status == TaskAgent::STATUS_COMPLETED) {
			$color = "green";
		} elseif($this->status == TaskAgent::STATUS_FAILED) {
			$color = "red";
		} elseif($this->status == TaskAgent::STATUS_ACKNOWLEDGE) {
			$color = "purple";
		}
		return $color;
	}
	public function getStatus() {
		$status = "Unknown (" . $this->status . ")";
		if($this->status == TaskAgent::STATUS_INPROGRESS) {
			$status = "In progress";
		} elseif($this->status == TaskAgent::STATUS_COMPLETED) {
			$status = "Completed";
		} elseif($this->status == TaskAgent::STATUS_FAILED)  {
			$status = "Failed";
		} elseif($this->status == TaskAgent::STATUS_PENDING)  {
			$status = "Pending";
		} elseif($this->status == TaskAgent::STATUS_ACKNOWLEDGE) {
			$status = "Acknowledged";
		}
		return $status;
	}
}
