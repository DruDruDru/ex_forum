<?php

namespace App\Services;

use App\Http\Requests\GradeRequest;
use App\Models\Grade;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class GradeService
{
    protected $grade;

    public function __construct(Grade $grade)
    {
        $this->grade = $grade;
    }

    public function create(GradeRequest $request, $postId)
    {
        if (Post::posses($postId)) {
            $gradeBuilder = Grade::where('user_id', Auth::id())
                                 ->where('post_id', $postId);

            if ($gradeBuilder->exists()) {

                if ($gradeBuilder->first()->rating !== $request->input('rating')) {
                    $gradeBuilder->update([
                        'rating' => $request->input('rating')
                    ]);
                    return 'Оценка изменена';
                }

                $gradeBuilder->delete();
                return 'Оценка убрана';
            }

            $this->grade::create([
                ...$request->toArray(),
                'user_id' => Auth::id(),
                'post_id' => (int) $postId
            ]);

            return 'Оценка поставлена';
        }

        return false;
    }
}
