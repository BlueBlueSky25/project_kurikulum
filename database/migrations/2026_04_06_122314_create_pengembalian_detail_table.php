use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Schema;
use Illuminate\Support\Facades\Schema;

class CreatePengembalianDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pengembalian_detail', function (Blueprint $table) {
            $table->id();  // Primary key
            $table->unsignedBigInteger('peminjaman_id'); // Foreign key reference
            $table->string('item_name'); // Name of the item
            $table->timestamp('returned_at'); // Timestamp for when returned
            $table->timestamps(); // Created at and Updated at
            
            $table->foreign('peminjaman_id')->references('id')->on('peminjaman')->onDelete('cascade'); // Foreign key constraint
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pengembalian_detail');
    }
}