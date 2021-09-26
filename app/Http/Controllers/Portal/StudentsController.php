<?php

    namespace App\Http\Controllers\Portal;

    use App\Http\Controllers\Controller;
    use App\Models\Goal;
    use App\Models\Schedule;
    use App\Models\Student;
    use Illuminate\Http\Request;

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

//        public function index($schoolId)
//        {
//            return $this->schoolYear->where('school_id', $schoolId)->get();
//        }
//
//        public function show(SchoolYear $schoolYear){
//            return $schoolYear;
//        }
        public function store(Request $request)
        {
            $this->validate($request, [
                'school_year_id' => 'required|integer',
                'disorders' => 'required',
                'first_name' => 'required',
                'last_name' => 'required',
            ]);
            $student = $this->student->create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'grade' => $request->grade,
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
                // create objectives per disorder
            }

        }

//        public function update(SchoolYear $schoolYear, Request $request)
//        {
//            $this->validate($request, [
//                'school_id' => 'required|integer',
//                'start' => 'required|date',
//                'end' => 'required|date',
//            ]);
//            $schoolYear->update($request->all());
//            return $schoolYear->refresh();
//        }
//
//        public function destroy(SchoolYear $schoolYear): bool
//        {
//            return $schoolYear->delete();
//        }
    }
