<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use Alert;
use Session;

class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('login.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = [
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ];
        $user = User::where('email',$request->email)->first();
        if (Auth::Attempt($data)) {
            Session::put('id',$user->id);
            // store unit id and readable unit name in session
            Session::put('unit_id',$user->unit_id);
            Session::put('unit_name', $user->unit->nama_unit ?? null);
            Session::put('name',$user->name);
            Session::put('level',$user->level);
            Session::put('email',$user->email);
            Session::put('login',TRUE);
            return redirect('/');
        }else{
            Session::flush();
            return back()->with('error','Informasi akun yang anda masukan salah!');
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        Auth::logout();
        Session::flush();
        $request->session()->invalidate(); // Hapus sesi
        $request->session()->regenerateToken(); // Regenerasi CSRF token
        return redirect('/login')->with('succes','Berhasil Logout dari akun');
    }
}
