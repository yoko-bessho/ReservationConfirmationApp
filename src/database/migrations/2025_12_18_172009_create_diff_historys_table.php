<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiffHistorysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diff_historys', function (Blueprint $table) {
            $table->id();
            $table->string('file_hash',32)->unique()->comment('インポートファイルのハッシュ値');
            $table->string('file_name')->comment('インポートファイル名');
            $table->integer('total_record_count')->comment('インポートファイルの総レコード数');
            $table->timestamp('detected_at')->comment('差分検出日時');
            $table->date('previous_import_date')->nullable()->comment('前回インポート日');
            $table->date('current_import_date')->comment('最新インポート日');
            $table->enum('diff_type', ['added', 'deleted', 'no_change'])->comment('差分タイプ: added=追加, deleted=削除, no_change=変化なし');
            
            // 差分がある場合のみ以下のフィールドに値を入れる
            $table->date('data_import_date')->nullable()->comment('この予約のインポート実行日');
            $table->date('visit_date')->nullable()->comment('来院予定日');
            $table->string('patient_id', 50)->nullable()->comment('患者ID');
            $table->string('patient_name', 100)->nullable()->comment('患者名');
            $table->string('reservation_content')->nullable()->comment('予約内容');
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
        Schema::dropIfExists('diff_historys');
    }
}
