namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAbonnement extends Model
{
    protected $table = 'user_abonnement';
    public $timestamps = false;
    protected $fillable = ['id_inscri', 'id_abonnement', 'date_debut', 'date_fin'];

    public function abonnement()
    {
        return $this->belongsTo(Abonnement::class, 'id_abonnement');
    }
}
