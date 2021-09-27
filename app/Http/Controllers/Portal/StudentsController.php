<?php

    namespace App\Http\Controllers\Portal;

    use App\Http\Controllers\Controller;
    use App\Models\Goal;
    use App\Models\Objective;
    use App\Models\Schedule;
    use App\Models\Student;
    use Illuminate\Http\Request;
    use Illuminate\Support\Arr;

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
                ->when(!is_null($disorderId), function ($q) use ($disorderId) {
                    $q->where('disorder_id', $disorderId);
                })->with('student')
                ->get();

        }

        public function store(Request $request)
        {
            $this->validate($request, [
                'school_year_id' => 'required|integer',
                'disorders' => 'required',
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
                    'annual_minutes' => $disorder['annual_minutes'],
                    'active' => true,
                ]);
                foreach ($disorder['schedule'] as $schedule) {
                    Schedule::create([
                        'goal_id' => $goal->id,
                        'day' => $schedule['day'],
                        'start_time' => $schedule['start_time'],
                        'end_time' => $schedule['end_time'],
                    ]);
                }
                foreach ($disorder['objectives'] as $objective) {
                    Objective::create([
                        'goal_id' => $goal->id,
                        'goal' => $objective,
                    ]);
                }
            }
            return $student;
        }

        public function update(Student $student, Request $request)
        {
            $this->validate($request, [
                'first_name' => 'required',
                'last_name' => 'required',
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

        public function deactivateGoal($goalId, Request $request){
            $goal = Goal::where('student_id', $request->get('student_id'))
                ->where('disorder_id', $request->get('disorder_id'))
                ->where('school_year_id', $request->get('school_year_id'))
                ->findOrFail($request->get('goal_id'));
            $goal->update([
                'active' => false
            ]);
            return $goal->refresh();
        }
//
//        public function destroy(SchoolYear $schoolYear): bool
//        {
//            return $schoolYear->delete();
//        }
    }
