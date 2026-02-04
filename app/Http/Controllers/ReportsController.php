namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function index()
    {
        // Retourne une vue que tu vas créer ensuite
        return view('chef.reports');
    }
}
