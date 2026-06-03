<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('temp_stds', function (Blueprint $table) {
            $table->id();

            // Student
            $table->string('stdName')->nullable();
            $table->date('dob')->nullable();
            $table->string('gender')->nullable();
            $table->string('uid')->nullable();
            $table->string('blood')->nullable();
            $table->string('mTongue')->nullable();
            $table->string('stdReligion')->nullable();
            $table->string('category')->nullable();
            $table->string('minority')->nullable();
            $table->string('std_ph')->nullable();
            $table->string('identitymark')->nullable();

            // Admission
            $table->string('admNo')->nullable();
            $table->string('admFormNo')->nullable();
            $table->date('dateOfJoin')->nullable();
            $table->string('session')->nullable();
            $table->string('stdClass')->nullable();
            $table->string('stdSection')->nullable();
            $table->string('rollno')->nullable();
            $table->string('stdroll')->nullable();
            $table->string('medium')->nullable();
            $table->string('stdType')->nullable();

            // School
            $table->string('previousSchoolName')->nullable();
            $table->string('lastClass')->nullable();
            $table->string('passingYear')->nullable();
            $table->string('marks')->nullable();
            $table->string('tcNo')->nullable();
            $table->string('affilated')->nullable();

            // Father
            $table->string('fname')->nullable();
            $table->string('fmobile')->nullable();
            $table->string('fwhatsapp')->nullable();
            $table->string('fmail')->nullable();
            $table->string('foccupation')->nullable();
            $table->string('fqualificatin')->nullable();
            $table->string('fincome')->nullable();
            $table->text('fpresentadd')->nullable();
            $table->text('fpermanentadd')->nullable();

            // Mother
            $table->string('mname')->nullable();
            $table->string('mmobile')->nullable();
            $table->string('mwhatsapp')->nullable();
            $table->string('mmail')->nullable();
            $table->string('moccupation')->nullable();
            $table->string('mqualificatin')->nullable();
            $table->string('mincome')->nullable();
            $table->text('mpresentadd')->nullable();
            $table->text('mpermanentadd')->nullable();

            // Guardian
            $table->string('studentguardian')->nullable();
            $table->string('guardianRelation')->nullable();
            $table->string('studetParentRelation')->nullable();
            $table->string('whatRelation')->nullable();

            // Address
            $table->text('presentAdd')->nullable();
            $table->text('permanentadd')->nullable();
            $table->string('area')->nullable();
            $table->string('district')->nullable();
            $table->string('state')->nullable();
            $table->string('pin')->nullable();

            // Bank
            $table->string('bankname')->nullable();
            $table->string('bankAc')->nullable();

            // Transport
            $table->string('routeNo')->nullable();
            $table->string('tripNo')->nullable();
            $table->string('vahicalNo')->nullable();
            $table->string('stopage')->nullable();
            $table->string('busFee')->nullable();

            // Hostel
            $table->string('hostel')->nullable();

            // Other
            $table->string('bpl')->nullable();
            $table->string('ph')->nullable();
            $table->text('deo')->nullable();
            $table->date('leavedate')->nullable();

            // Files
            $table->string('stdImage')->nullable();
            $table->string('studentAadhar')->nullable();
            $table->string('parentAadhar')->nullable();

            // School
            $table->unsignedBigInteger('S_id')->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temp_stds');
    }
};
