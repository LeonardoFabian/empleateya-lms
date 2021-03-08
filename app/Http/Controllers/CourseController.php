<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;

class CourseController extends Controller
{
    /**
     * Controller to manage the courses home page
     */
    public function index(){
        return view( 'courses.index' );
    }

    /**
     * Controller to manage the courses single page
     */
    public function show( Course $course ){

        // Show only published courses to autenticated users
        $this->authorize('published', $course );

        /**
         * Retur related courses
         */
        $related_courses = 
        Course::where( 'category_id', $course->category_id )
            ->where( 'id', '!=', $course->id )
            ->where( 'status', 3 )
            ->latest()
            ->take(5)
        ->get();

        return view( 'courses.show', compact( 'course', 'related_courses' ) );
    }

    /**
     * Controller to enrolled users
     */
    public function enrolled( Course $course ){

        // insert user auth id in course_user table
        $course->students()->attach( auth()->user()->id );

        // redirect user to enrolled course;
        return redirect()->route('courses.status', $course);

    }
    
}
