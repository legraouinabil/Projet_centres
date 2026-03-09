<?php

namespace App\Helpers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;

class ActivityLogger
{
    /**
     * Log an action to activity_logs table.
     *
     * @param string $action Short action key (e.g. 'association.created')
     * @param mixed $subject Eloquent model or string describing the subject
     * @param array $properties Additional metadata
     * @return ActivityLog
     */
    public static function log(string $action, $subject = null, array $properties = [])
    {
        $subjectType = null;
        $subjectId = null;

        if (is_object($subject) && method_exists($subject, 'getKey')) {
            $subjectType = get_class($subject);
            $subjectId = $subject->getKey();
        } elseif (is_string($subject)) {
            $subjectType = null;
            $subjectId = null;
            $properties['subject_text'] = $subject;
        }

        return ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'subject_type' => $subjectType,
            'subject_id' => $subjectId,
            'properties' => $properties,
            'ip_address' => Request::ip(),
            'user_agent' => Request::header('User-Agent'),
        ]);
    }
}
