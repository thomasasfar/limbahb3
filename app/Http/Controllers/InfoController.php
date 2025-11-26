<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Info;
use App\Models\Role;
use App\Models\User;
use App\Models\Worker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\InfoNotification;
use App\Services\WhatsAppService;

class InfoController extends Controller
{
    public function create()
    {
        // $role = Role::all();
        $info = Info::all();
        return view('info.index',compact('info'));
    }


    // protected $whatsappService;

    // public function __construct(WhatsAppService $whatsappService)
    // {
    //     $this->whatsappService = $whatsappService;
    // }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpg,png,jpeg,gif|max:2048',
            'content' => 'required|string',
        ]);

        // Simpan gambar
        $input = $request->all();
        $file_name=$request->image->getClientOriginalName();
        $gambar = $request->file('image')->move('img/info/',$file_name);
        // Simpan info ke database
        $info = Info::create([
            'title' => $request->title,
            'content' => $request->content,
            'image' => $file_name,
            'date'=>\Carbon\Carbon::now(),
        ]);

        // $message = "*Info Terbaru:*\n\n"
        //         . "*Judul:* {$request->title}\n\n"    
        //         . "*Informasi:* {$request->content}\n\n";
             
       
        // $result = $this->whatsappService->sendMessage($number, $message);
        // $results[] = [
        //     'number' => $number,
        //     'result' => $result,
        // ];
        

        return back()->with('success', 'Info berhasil disimpan!');
    }

    protected function sendEmailNotification($roles, $info)
    {
        // Dapatkan email dari role yang diberikan
        foreach ($roles as $role) {
            // Misalkan kita menggunakan model User untuk mendapatkan email berdasarkan role
            $users = Worker::where('id_role', $role)->get();
            foreach ($users as $user) {
                Mail::to($user->email)->send(new InfoNotification($info));
            }
        }
    }
    public function show(string $id)
    {
        $info = Info::find($id);
        return view('info.show',compact('info'));
    }
}
?>