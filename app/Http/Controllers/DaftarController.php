<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Unit;
use Hash;
use App\Services\WhatsAppService;
use Session;

class DaftarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $units = Unit::all();
        return view('daftar.index', compact('units'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }


    protected $whatsappService;

    public function __construct(WhatsAppService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $email = User::where('email',$request->email)->first();
        if($email){
            return back()->with('error','Email yang anda masukan telah didaftarkan');
        }else{
            $id_user = User::insertGetId([
                'email'=>$request->email,
                'password'=>Hash::make($request->password),
                'penanggung_jawab'=>$request->penanggung_jawab,
                'unit_id'=>$request->unit_id,
                'no_hp'=>$request->no_hp,
                'name'=>$request->name,
                'nohp_user'=>$request->nohp_user,
            ]);
           
            $whatsappService = new WhatsappService();
            $message = "*{$request->name},anda berhasil mendaftarkan akun TPS Limbah anda dengan detail akun sebagai berikut:*\n"
                        . "*Email:* {$request->email}\n"
                        . "*Password:* {$request->password}\n";
            
            $number = $request->nohp_user;
    
            $result = $this->whatsappService->sendMessage($number, $message);
            $results[] = [
                'number' => $number,
                'result' => $result,
            ];
            return redirect('login')->with('success','Berhasil membuat akun Website Limbah B3 PT Semen Padang. Silahkan login ke akun anda!');
        }
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::find(Session::get('id'));
        $user->update([
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
            'penanggung_jawab'=>$request->penanggung_jawab,
            'unit_id'=>$request->unit_id,
            'nohp_user'=>$request->no_hp,
            'name'=>$request->name,
        ]);
        // Update session with new values
        session()->put('email', $user->email);
        session()->put('unit_id', $user->unit_id);
        session()->put('unit_name', $user->unit->nama_unit ?? '');
        session()->put('name', $user->name);
        $whatsappService = new WhatsappService();
        $message = "*{$request->name},anda berhasil mengubah informasi akun TPS Limbah anda dengan detail akun sebagai berikut:*\n"
                    . "*Email:* {$request->email}\n"
                    . "*Password:* {$request->password}\n";
        
        $number = $user->nohp_user;

        $result = $this->whatsappService->sendMessage($number, $message);
        $results[] = [
            'number' => $number,
            'result' => $result,
        ];

        return back()->with('success','Berhasil mengubah informasi akun Website Limbah B3 PT Semen Padang. Silahkan login ke akun anda!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
