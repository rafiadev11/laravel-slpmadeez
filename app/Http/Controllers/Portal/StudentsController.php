<?php

    namespace App\Http\Controllers\Portal;

    use App\Http\Controllers\Controller;
    use App\Models\Goal;
    use App\Models\Objective;
    use App\Models\Schedule;
    use App\Models\Student;
    use Illuminate\Http\Request;
    use Illuminate\Support\Arr;
    use Illuminate\Support\Facades\Log;

    class StudentsController extends Controller
    {
        /**
         * @var Student
         */
        private $student;

        /**
         * @param  Student  $student
         */
        public function __construct(Student $student)
        {
            $this->student = $student;
        }

        public function index($schoolYearId, $disorderId = null)
        {
            return Goal::where('school_year_id', $schoolYearId)
                ->when(!is_null($disorderId) && $disorderId != '0', function ($q) use ($disorderId) {
                    $q->where('disorder_id', $disorderId);
                })->with('student', 'disorder')
                ->get();
        }

        public function store(Request $request)
        {
            $this->validate($request, [
                'school_year_id' => 'required|integer',
                'first_name' => 'required',
                'last_name' => 'required',
            ]);
            $student = $this->student->create([
                'first_name' => $request->get('first_name'),
                'last_name' => $request->get('last_name'),
                'grade' => $request->get('grade'),
            ]);
            foreach ($request->get('disorders') as $disorder) {
                $goal = Goal::create([
                    'school_year_id' => $request->get('school_year_id'),
                    'student_id' => $student->id,
                    'disorder_id' => $disorder['id'],
                    'annual_minutes' => 0,
                    'active' => true,
                ]);
                foreach ($request->get('sessions') as $session) {
                    if ($session['disorder_id'] == $goal->disorder_id) {
                        $annualMinutes = $session['duration'] * $session['perMonth'] * 8;
                        $goal->update(['annual_minutes' => $annualMinutes]);
                        foreach ($session['schedule'] as $schedule) {
                            if ($schedule['checked']) {
                                Schedule::create([
                                    'goal_id' => $goal->id,
                                    'day' => $schedule['day'],
                                    'start_time' => $schedule['time']['startTime'],
                                    'end_time' => $schedule['time']['endTime'],
                                ]);
                            }
                        }
                    }


                }
                foreach ($request->get('objectives') as $objective) {
                    if ($objective['disorder_id'] == $goal->disorder_id) {
                        foreach ($objective['values'] as $value) {
                            Objective::create([
                                'goal_id' => $goal->id,
                                'goal' => $value['name'],
                            ]);
                        }
                    }

                }
            }
            return $student;
        }

        public function update(Student $student, Request $request)
        {
            $this->validate($request, [
                'first_name' => 'required',
                'last_name' => 'required',
                'grade' => 'required',
            ]);
            $student->update($request->all());
            return $student->refresh();
        }

        public function updateGoal($goalId, Request $request)
        {
            $goal = Goal::with('schedule', 'objectives', 'student', 'schoolYear', 'disorder')
                ->where('student_id', $request->get('student_id'))->findOrFail($goalId);
            $goal->update([
                'school_year_id' => $request->get('school_year_id'),
                'disorder_id' => $request->get('disorder_id'),
                'annual_minutes' => $request->get('annual_minutes'),
            ]);
            if (!is_null($request->get('schedule')) && count($request->get('schedule')) > 0) {
                foreach ($goal->schedule as $schedule) {
                    $schedule->delete();
                    // Make sure to run a cron job to remove soft deleted schedules at the end of the week
                }
                foreach ($request->get('schedule') as $schedule) {
                    Schedule::create([
                        'goal_id' => $goal->id,
                        'day' => $schedule['day'],
                        'start_time' => $schedule['start_time'],
                        'end_time' => $schedule['end_time'],
                    ]);
                }
            }
            if (!is_null($request->get('objectives')) && count($request->get('objectives')) > 0) {
                foreach ($goal->objectives as $objective) {
                    $objective->delete();
                    // Make sure to run a cron job to remove soft deleted schedules at the end of the week
                }
                foreach ($request->get('objectives') as $objective) {
                    Objective::create([
                        'goal_id' => $goal->id,
                        'goal' => $objective,
                    ]);
                }
            }
            return $goal->refresh();
        }

        public function deactivateGoal($goalId, Request $request)
        {
            $goal = Goal::where('student_id', $request->get('student_id'))
                ->where('disorder_id', $request->get('disorder_id'))
                ->where('school_year_id', $request->get('school_year_id'))
                ->findOrFail($request->get('goal_id'));
            $goal->update([
                'active' => false,
            ]);
            return $goal->refresh();
        }

        public function addDisorder(Request $request)
        {
            $goal = Goal::create([
                'school_year_id' => $request->get('school_year_id'),
                'student_id' => $request->get('student_id'),
                'disorder_id' => $request->get('id'),
                'annual_minutes' => $request->get('duration') * $request->get('perMonth') * 8,
                'active' => true,
            ]);
            foreach ($request->get('schedule') as $schedule) {
                if ($schedule['checked']) {
                    Schedule::create([
                        'goal_id' => $goal->id,
                        'day' => $schedule['day'],
                        'start_time' => $schedule['time']['startTime'],
                        'end_time' => $schedule['time']['endTime'],
                    ]);
                }
            }
            foreach ($request->get('objectives') as $objective) {
                Objective::create([
                    'goal_id' => $goal->id,
                    'goal' => $objective['name'],
                ]);
            }
            return Goal::with('student', 'disorder')->findOrFail($goal->id);
        }

        public function getSchedule($goalId)
        {
            return Schedule::where('goal_id', $goalId)->get();
        }

        public function updateSchedule(Request $request)
        {
            $goalId = $request->get('goal_id');
            foreach ($request->get('schedule') as $schedule) {
                if ($schedule['checked']) {
                    if (Arr::has($schedule, 'id') && !is_null($schedule['id'])) {
                        Schedule::findOrFail($schedule['id'])
                            ->update([
                                'day' => $schedule['day'],
                                'start_time' => $schedule['time']['startTime'],
                                'end_time' => $schedule['time']['endTime'],
                            ]);
                    } else {
                        Schedule::create([
                            'goal_id' => $goalId,
                            'day' => $schedule['day'],
                            'start_time' => $schedule['time']['startTime'],
                            'end_time' => $schedule['time']['endTime'],
                        ]);
                    }
                } else {
                    if (Arr::has($schedule, 'id') && !is_null($schedule['id'])) {
                        Schedule::findOrFail($schedule['id'])->delete();
                    }
                }
            }
        }

        public function getObjectives($goalId)
        {
            return Objective::where('goal_id', $goalId)->get();
        }

        public function updateObjectives(Request $request)
        {
            $goalId = $request->get('goal_id');
            $ids = [];
            foreach ($request->get('objectives') as $objective) {
                if (Arr::has($objective, 'id') && !is_null($objective['id'])) {
                    Objective::findOrFail($objective['id'])
                        ->update([
                            'goal' => $objective['name'],
                        ]);
                    $ids[] = $objective['id'];
                } else {
                    $obj = Objective::create([
                        'goal_id' => $goalId,
                        'goal' => $objective['name'],
                    ]);
                    $ids[] = $obj->id;
                }
            }
            Objective::where('goal_id', $goalId)
                ->whereNotIn('id', $ids)
                ->delete();
        }

        public function deactivate(Request $request)
        {
            Goal::findOrFail($request->get('id'))
                ->update(['active' => false]);
        }
    }
