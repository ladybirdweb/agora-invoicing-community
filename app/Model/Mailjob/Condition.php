<?php

namespace App\Model\Mailjob;

use Illuminate\Database\Eloquent\Model;

class Condition extends Model
{
    protected $table = 'conditions';
    protected $fillable = ['job', 'value'];

    public function checkActiveJob()
    {
        $result = ['expiryMail' => '', 'deleteLogs' => ''];
        $allStatus = new \App\Model\Common\StatusSetting();
        $status = $allStatus->find(1);
        if ($status) {
            if ($status->expiry_mail == 1) {
                $result['expiryMail'] = true;
            }
            if ($status->activity_log_delete == 1) {
                $result['deleteLogs'] = true;
            }
        }

        return $result;
    }

    public function getConditionValue($job)
    {
        $value = ['condition' => '', 'at' => ''];
        $condition = $this->where('job', $job)->first();
        if ($condition) {
            $condition_value = explode(',', $condition->value);
            $value = ['condition' => $condition_value, 'at' => ''];
            if (is_array($condition_value)) {
                $value = ['condition' => $this->checkArray(0, $condition_value), 'at' => $this->checkArray(1, $condition_value)];
            }
        }

        return $value;
    }

    public function checkArray($key, $array)
    {
        $value = '';
        if (is_array($array)) {
            if (array_key_exists($key, $array)) {
                $value = $array[$key];
            }
        }

        return $value;
    }
}
