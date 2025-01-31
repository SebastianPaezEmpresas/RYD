<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class WorkerController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'worker');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('active', $request->status === 'active');
        }

        $workers = $query->get();
        return view('admin.trabajadores.index', compact('workers'));
    }

    public function create()
    {
        return view('admin.trabajadores.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6'
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'worker'
        ]);

        return redirect()->route('admin.trabajadores.index')->with('success', 'Trabajador creado exitosamente');
    }

    public function edit(User $worker)
    {
        return view('admin.trabajadores.edit', compact('worker'));
    }

    public function update(Request $request, User $worker)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$worker->id,
            'password' => 'nullable|min:6',
            'active' => 'required|boolean'
        ]);

        $worker->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'active' => $validated['active']
        ]);

        if($request->filled('password')) {
            $worker->update(['password' => Hash::make($validated['password'])]);
        }

        return redirect()->route('admin.trabajadores.index')->with('success', 'Trabajador actualizado exitosamente');
    }

    public function destroy(User $worker)
    {
        $worker->delete();
        return redirect()->route('admin.trabajadores.index')->with('success', 'Trabajador eliminado exitosamente');
    }
}