<?php

    namespace App\Http\Controllers\Portal;

    use App\Http\Controllers\Controller;
    use App\Models\School;
    use Illuminate\Http\Request;

    class SchoolsController extends Controller
    {
        /**
         * @var School
         */
        private $school;

        /**
         * @param  School  $school
         */
        public function __construct(School $school)
        {
            $this->school = $school;
        }

        public function index()
        {
            return $this->school->get();
        }

        public function store(Request $request)
        {
            $this->validate($request, [
                'name' => 'required',
            ]);
            return $this->school->create($request->all());
        }

        public function show(School $school): School
        {
            return $school;
        }

        public function update(School $school, Request $request)
        {
            $this->validate($request, [
                'name' => 'required',
            ]);
            $school->update($request->all());
            return $school->refresh();
        }

        public function destroy(School $school): bool
        {
            return $school->delete();
        }
    }
