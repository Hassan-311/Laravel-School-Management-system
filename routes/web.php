<?php

use App\Http\Controllers\ClassSubjectController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SchoolClassController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\ClassTeacherController;
use App\Http\Controllers\TeacherSubjectController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\FeeController;

Route::resource('classes', SchoolClassController::class);
Route::get('classes-data',[SchoolClassController::class, 'getData'])->name('classes.data');

Route::resource('subjects', SubjectController::class);
Route::get('subjects-data', [SubjectController::class, 'getData'])->name('subjects.data');

Route::resource('teachers', TeacherController::class);
Route::get('teachers-data', [TeacherController::class, 'getData'])->name('teachers.data');

Route::resource('students', StudentController::class);
Route::get('students-data',[StudentController::class, 'getData'])->name('students.data');

Route::get('teacher-subject', [TeacherSubjectController::class, 'index'])->name('teacher.subject.index');
Route::get('teacher-subject-data', [TeacherSubjectController::class, 'getData'])->name('teacher.subject.data');
Route::post('teacher-subject', [TeacherSubjectController::class, 'store'])->name('teacher.subject.store');
Route::delete('teacher-subject', [TeacherSubjectController::class, 'destroy'])->name('teacher.subject.destroy');

Route::get('class-teacher', [ClassTeacherController::class, 'index'])->name('class.teacher.index');
Route::get('class-teacher-data', [ClassTeacherController::class, 'getData'])->name('class.teacher.data');
Route::post('class-teacher', [ClassTeacherController::class, 'store'])->name('class.teacher.store');
Route::delete('class-teacher', [ClassTeacherController::class, 'destroy'])->name('class.teacher.destroy');

Route::get('class-subject', [ClassSubjectController::class, 'index'])->name('class.subject.index');
Route::get('class-subject-data',[ClassSubjectController::class, 'getData'])->name('class.subject.data');
Route::post('class-subject', [ClassSubjectController::class, 'store'])->name('class.subject.store');
Route::delete('class-subject', [ClassSubjectController::class, 'destroy'])->name('class.subject.destroy');

Route::get('attendance', [AttendanceController::class, 'index'])->name('attendance.index');
Route::post('attendance', [AttendanceController::class, 'store'])->name('attendance.store');
Route::get('attendance/students', [AttendanceController::class, 'getStudents'])->name('attendance.students');
Route::get('attendance/report', [AttendanceController::class, 'getReport'])->name('attendance.report');


Route::get('fees-data', [FeeController::class, 'getData'])->name('fees.data');
Route::resource('fees', FeeController::class);


Route::get('/', function () {
    return redirect()->route('students.index');
});
