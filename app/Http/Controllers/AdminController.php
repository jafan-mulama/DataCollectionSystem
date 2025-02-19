<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Questionnaire;
use App\Models\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'can:admin']);
    }

    public function dashboard()
    {
        $totalUsers = User::count();
        $totalQuestionnaires = Questionnaire::count();
        $totalResponses = Response::count();
        
        $usersByRole = [
            'admin' => User::where('role', 'admin')->count(),
            'lecturer' => User::where('role', 'lecturer')->count(),
            'student' => User::where('role', 'student')->count(),
        ];

        return view('admin.dashboard', compact('totalUsers', 'totalQuestionnaires', 'totalResponses', 'usersByRole'));
    }

    public function users()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        return view('admin.users.index', compact('users'));
    }

    public function createUser()
    {
        return view('admin.users.create');
    }

    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,lecturer,student',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        return redirect()->route('admin.users')
            ->with('success', 'User created successfully.');
    }

    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,lecturer,student',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];
        
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('admin.users')
            ->with('success', 'User updated successfully.');
    }

    public function deleteUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users')
                ->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('admin.users')
            ->with('success', 'User deleted successfully.');
    }

    public function statistics()
    {
        $stats = [
            'total_users' => User::count(),
            'total_lecturers' => User::where('role', 'lecturer')->count(),
            'total_students' => User::where('role', 'student')->count(),
            'total_questionnaires' => Questionnaire::count(),
            'total_responses' => Response::count(),
            'recent_questionnaires' => Questionnaire::with('user')
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get(),
            'recent_responses' => Response::with(['user', 'questionnaire'])
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get(),
        ];

        return view('admin.statistics', compact('stats'));
    }
}
