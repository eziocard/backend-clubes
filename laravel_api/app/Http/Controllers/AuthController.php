<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(User::latest()->get()); 
    }

    /**
     * Show the form for creating a new resource.
     */
    public function register(Request $request)
    {
        $fields = $request->validate([
            'rut'       => 'required|string|unique:users,rut',
            'name'      => 'required|string|max:255',
            'lastname'  => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|min:1',
            'team_id'   => 'nullable|exists:teams,id',
            'role'      => 'in:superuser,teacher,team_admin'
        ]);
       
        $user = User::create([
            'rut'       =>  $fields['rut'],
            'name'      => $fields['name'],
            'lastname'  => $fields['lastname'],
            'email'     => $fields['email'],
            'password'  => bcrypt($fields['password']),
            'team_id'   => $fields['team_id'],
            'role'      => $fields['role'],
        ]);
        
        $token = $user->createToken('backend-token')->plainTextToken;
        $response = [
            'user' => $user,
            'token' => $token,
        ];

        return response($response,201);
    }

 public function login(Request $request)
{
    $fields = $request->validate([
        'email'    => 'required|email',
        'password' => 'required|min:1',
    ]);

    $user = User::where('email', $fields['email'])->first();

    if (!$user || !Hash::check($fields['password'], $user->password)) {
        return response([
            'message' => 'Error en las Credenciales'
        ], 401);
    }

    $token = $user->createToken('backendtoken')->plainTextToken;

    return response([
        'user'  => $user,
        'token' => $token,
    ], 201);
}



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
    public function destroy(string $id)
    {
        //
    }
}
