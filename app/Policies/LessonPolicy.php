<?php

namespace App\Policies;

use App\Tutor;
use App\User;
use App\Lesson;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class LessonPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can change the lesson.
     *
     * @param $model
     * @param  \App\Lesson  $lesson
     * @return mixed
     */
    public function update($model, Lesson $lesson)
    {
        if (Auth::guard('tutor')->check()) {
            return $lesson->tutor_id == $model->id;
        }

        return $lesson->user_id == $model->id;
    }
}
