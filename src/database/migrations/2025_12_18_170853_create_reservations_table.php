<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->date('import_date')->index()->comment('インポート実行日');
            $table->date('visit_date')->comment('来院予定日');
            $table->string('patient_id', 50);
            $table->string('patient_name', 100);
            $table->string('reservation_content',255)->comment('薬剤予約内容'); // 表記ゆれ発生するのでstringとする
            $table->timestamps();

            // 完全に同一データの重複登録不可
            $table->unique([
                'import_date',
                'visit_date',
                'patient_id',
                'patient_name'
            ], 'unique_reservation');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reservations');
    }
}
