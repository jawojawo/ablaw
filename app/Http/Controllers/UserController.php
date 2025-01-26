<?php

namespace App\Http\Controllers;

use App\Models\LawCase;
use App\Models\Permission;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Activitylog\Models\Activity;

class UserController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', User::class);
        // $firstName = $request->input('first_name');
        // $lastName = $request->input('last_name');
        // $associates = Associate::when($firstName, function ($query, $firstName) {
        //     $query->where('first_name', 'like', "%{$firstName}%");
        // })->when($lastName, function ($query, $lastName) {
        //     $query->where('last_name', 'like', "%{$lastName}%");
        // })->withCount(['lawCases',  'contacts', 'notes'])
        //     ->paginate(15);
        $users = User::withCount(['notes', 'contacts'])->paginate(15);
        return view('user.index', ['users' => $users]);
    }

    public function show(User $user, Request $request)
    {

        // $activities = Activity::where('causer_id', $user->id)
        //     ->with('causer') // Include user who made the change
        //     ->orderBy('created_at', 'desc')
        //     ->paginate(100);

        // return view('components.activity.case-activity-template', ['activities' => $activities])->render();

        $this->authorize('view', $user);
        if ($request->ajax()) {
            switch ($request->table) {
                case  "notes":
                    return view('components.note.notes-table', ['notes' => $user->notes()])->render();
                    break;
                case  "activities":

                    $activities = Activity::where('causer_id', $user->id)
                        ->with('causer') // Include user who made the change
                        ->orderBy('created_at', 'desc')
                        ->paginate(10);

                    return view('components.activity.case-activity-template', ['activities' => $activities])->render();
                    break;

                default:
                    return '';
                    break;
            }
        }
        $user->loadCount(['notes']);
        return view('user.show', ['user' => $user]);
    }
    public function create()
    {
        $this->authorize('create', User::class);
        return view('user.create');
    }
    public function store(Request $request)
    {

        $this->authorize('create', User::class);
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|min:5|unique:users|max:100',
            'role' => 'required|string|max:100',
            'address' => 'nullable|string|max:500',
        ]);
        $password = Str::random(12);
        $validatedData['initial_password'] = $password;
        $validatedData['password'] = bcrypt($password);
        // dd($validatedData);
        $user = User::create($validatedData);


        // $permissions = $request->input('permissions', []);

        // foreach ($permissions as $entity => $actions) {
        //     foreach ($actions as $action => $value) {
        //         Permission::create([
        //             'user_id' => $user->id,
        //             'model' => $entity,
        //             'permission' => $action,
        //         ]);
        //     }
        // }
        return  redirect()->route('user')->with('success', 'User ' . $user->username . ' created successfully');
    }
    public function resetPassword(User $user)
    {
        $this->authorize('update', $user);
        if ($user->id == 1) {
            return  redirect()->route('user')->with('error', 'User ' . $user->username . ' password cannot be reset');
        }
        DB::table('sessions')->where('user_id', $user->id)->delete();
        $password = Str::random(12);
        $user->password = bcrypt($password);
        $user->initial_password = $password;
        $user->save();
        return  redirect()->route('user')->with('success', 'User ' . $user->username . ' password reset successfully');
    }
    public function update(Request $request, User $user)
    {

        $this->authorize('update', $user);
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
        ]);
        // Verify the current password if a new password is provided
        if ($request->filled('password') || $request->filled('current_password')) {
            $validatedPassword = $request->validate([
                'current_password' => 'required|string|min:8',
                'password' => 'required|string|min:8|confirmed',
            ]);
            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'The current password is incorrect.',
                ], 422);
            }
            $validatedPassword['password'] = bcrypt($validatedPassword['password']);
            $validatedPassword['initial_password'] = null;

            $user->update($validatedPassword);
        }

        $user->update($validatedData);

        if (auth()->user()->id == 1) {
            if ($request->filled('role')) {
                $validatedRole = $request->validate([
                    'role' => 'nullable|max:100',
                ]);

                $user->update($validatedRole);
            }
            if ($request->filled('username')) {
                if ($user->id === 1) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Username of admin cannot be changed.',
                    ], 422);
                }
                $validatedUsername = $request->validate([
                    'username' => 'required|min:5|max:100|unique:users,username,' . $user->id,
                ]);
                $user->update($validatedUsername);
            }
            $permissions = $request->input('permissions', []);

            $user->permissions()->delete();
            foreach ($permissions as $entity => $actions) {
                foreach ($actions as $action => $value) {
                    Permission::create([
                        'user_id' => $user->id,
                        'model' => $entity,
                        'permission' => $action,
                    ]);
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully!',
        ]);
    }
    public function destroy(User $user, Request $request)
    {
        $this->authorize('delete', User::class);

        if (!Hash::check($request->password, auth()->user()->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Password is incorrect.',
            ], 422);
        } else {
            if ($user->lawCases()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => "Cannot delete User because it has existing cases."
                ], 400);
            }
            if ($user->adminDeposits()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => "Cannot delete User because it has existing Deposits."
                ], 400);
            }
            if ($user->adminFees()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => "Cannot delete User because it has existing Deductable Expenses."
                ], 400);
            }
            if ($user->hearings()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => "Cannot delete User because it has existing Hearings."
                ], 400);
            }
            if ($user->billings()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => "Cannot delete User because it has existing Billings."
                ], 400);
            }
            if ($user->customEvents()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => "Cannot delete User because it has existing Custom Events."
                ], 400);
            }
            if ($user->officeExpenses()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => "Cannot delete User because it has existing Office Expenses."
                ], 400);
            }
            if ($user->deposits()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => "Cannot delete User because it has existing Payments."
                ], 400);
            }
            if ($user->ownedNotes()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => "Cannot delete User because it has Notes."
                ], 400);
            }
            $user->delete();
            return response()->json([
                'success' => true,
                'message' => "User has been deleted successfully. redirecting in 5 secs",
            ]);
        }
    }
}
