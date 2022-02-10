<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobStatuses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_statuses', function (Blueprint $table) {
            $table->id();
            $table->string("queue");
            $table->string('uuid')->unique();
            $table->unsignedBigInteger("mail_task_id");
            $table->integer("group_id");
            $table->integer("group_email_id");
            $table->boolean('status')->default(true);
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('CASCADE');
            $table->foreign('mail_task_id')->references('id')->on('mail_tasks')->onDelete('CASCADE');
            $table->foreign('group_email_id')->references('id')->on('group_emails')->onDelete('CASCADE');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_statuses');
    }
}
