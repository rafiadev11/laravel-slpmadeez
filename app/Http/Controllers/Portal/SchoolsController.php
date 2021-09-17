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
    public function __construct(School $school) {
        $this->school = $school;
    }

    public function index(){
        return $this->school->get();
    }

    public function show(School $school){
        return $school;
    }
}
