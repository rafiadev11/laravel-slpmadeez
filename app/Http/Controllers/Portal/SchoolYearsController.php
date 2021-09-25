<?php

    namespace App\Http\Controllers\Portal;

    use App\Http\Controllers\Controller;
    use App\Models\SchoolYear;
    use Illuminate\Http\Request;

    class SchoolYearsController extends Controller
    {
        /**
         * @var SchoolYear
         */
        private $schoolYear;

        /**
         * @param  SchoolYear  $schoolYear
         */
        public function __construct(SchoolYear $schoolYear)
        {
            $this->schoolYear = $schoolYear;
        }

        public function index($schoolId)
        {
            return $this->schoolYear->where('school_id', $schoolId)->get();
        }

        public function show(SchoolYear $schoolYear){
            return $schoolYear;
        }

        public function store(Request $request)
        {
            $this->validate($request, [
                'school_id' => 'required|integer',
                'start' => 'required|date',
                'end' => 'required|date',
            ]);
            return $this->schoolYear->create($request->all());
        }

        public function update(SchoolYear $schoolYear, Request $request)
        {
            $this->validate($request, [
                'school_id' => 'required|integer',
                'start' => 'required|date',
                'end' => 'required|date',
            ]);
            $schoolYear->update($request->all());
            return $schoolYear->refresh();
        }

        public function destroy(SchoolYear $schoolYear): bool
        {
            return $schoolYear->delete();
        }
    }
