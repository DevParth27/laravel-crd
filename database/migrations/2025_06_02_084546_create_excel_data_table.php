<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExcelDataTable extends Migration
{
    public function up()
    {
     Schema::create('excel_data', function (Blueprint $table) {
    $table->id();
    $table->string('file_name');
    $table->integer('row_number')->nullable();
    $table->json('row_data'); // store entire row as JSON
    $table->timestamps();
});
    }

    public function down()
    {
        Schema::dropIfExists('excel_data');
    }
}