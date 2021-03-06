<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Course;
use App\Models\Category;
use App\Models\Type;
use App\Models\Level;
use App\Models\Price;
use App\Models\Modality;

use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('instructor.courses.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::pluck('name', 'id');
        $types = Type::pluck('name', 'id');
        $levels = Level::pluck('name', 'id');
        $prices = Price::pluck('name', 'id');
        $modalities = Modality::pluck('name', 'id');

        return view('instructor.courses.create', compact('categories', 'types', 'levels', 'prices', 'modalities' ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'slug' => 'required|unique:courses',
            'duration_in_minutes' => 'required',
            'summary' => 'required',
            'category_id' => 'required',
            'type_id' => 'required',
            'level_id' => 'required',
            'price_id' => 'required',
            'file' => 'image',
        ]);

        $course = Course::create( $request->all() );

        if($request->file('file') ){
            $url = Storage::put('courses', $request->file('file'));

            $course->image()->create([
                'url' => $url
            ]);
        }

        return redirect()->route('instructor.courses.edit', $course);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function show(Course $course)
    {
        return view('instructor.courses.show', compact('course'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function edit(Course $course)
    {
        $categories = Category::pluck('name', 'id');
        $types = Type::pluck('name', 'id');
        $levels = Level::pluck('name', 'id');
        $prices = Price::pluck('name', 'id');
        $modalities = Modality::pluck('name', 'id');

        return view('instructor.courses.edit', compact('course', 'categories', 'types', 'levels', 'prices', 'modalities'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Course $course)
    {
        $request->validate([
            'title' => 'required',
            'slug' => 'required|unique:courses,slug,' . $course->id,
            'duration_in_minutes' => 'required',
            'summary' => 'required',
            'category_id' => 'required',
            'type_id' => 'required',
            'level_id' => 'required',
            'price_id' => 'required',
            'file' => 'image',
        ]);

        $course->update($request->all());

        if( $request->file('file') ){
            $url = Storage::put('courses', $request->file('file') );

            if( $course->image ){
                Storage::delete($course->image->url);

                $course->image->update([
                    'url' => $url
                ]);
            } else {
                $course->image->create([
                    'url' => $url
                ]);
            }
        }

        return redirect()->route('instructor.courses.edit', $course);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function destroy(Course $course)
    {
        //
    }
}
