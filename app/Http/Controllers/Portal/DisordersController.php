<?php

    namespace App\Http\Controllers\Portal;

    use App\Http\Controllers\Controller;
    use App\Models\Disorder;
    use App\Models\Goal;
    use Illuminate\Http\Request;

    class DisordersController extends Controller
    {
        /**
         * @var Disorder
         */
        private $disorder;

        /**
         * @param  Disorder  $disorder
         */
        public function __construct(Disorder $disorder)
        {
            $this->disorder = $disorder;
        }

        public function index()
        {
            return $this->disorder->get();
        }

        public function show(Disorder $disorder): Disorder
        {
            return $disorder;
        }

        public function store(Request $request)
        {
            $this->validate($request, [
                'name' => 'required',
            ]);
            return $this->disorder->create($request->all());
        }

        public function update(Disorder $disorder, Request $request)
        {
            $this->validate($request, [
                'name' => 'required',
            ]);
            $disorder->update($request->all());
            return $disorder->refresh();
        }

        public function destroy(Disorder $disorder): bool
        {
            return $disorder->delete();
        }

        public function unused($studentId){
            $goals = Goal::where('student_id', $studentId)->get();
            $disorderIds = [];
            foreach ($goals as $goal){
                $disorderIds[] = $goal->disorder_id;
            }
            return $this->disorder
                ->whereNotIn('id',$disorderIds)
                ->get();
        }
    }
