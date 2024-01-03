<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('create-role', function(){
    // $role = Role::create(['name' => 'publisher']);

    // $permission = Permission::create(['name' => 'edit articles']);

    // return $permission;

    $user = auth()->user();

    // $user->assignRole('writer');
    // $user->givePermissionTo('edit articles');

    // $permissionNames = $user->getPermissionNames();

    // return $user->getPermissionNames();
    // return $user->permissions;
    // return $user->roles;

    // return $user->can('edit articles');

    // $checkPermission =  $user->can('edit articles');

    if ( $user->can('delete articles')) {
        return 'user have permission';
    }else {
        return 'user dont have permission';
    }
});


Route::get('posts', function(){
    $posts = Post::all();

    return view('post.post', compact('posts'));
});



 
Route::get('/auth/redirect', function () {
    return Socialite::driver('github')->redirect();
})->name('github.login');
 
Route::get('/auth/callback', function () {
    $user = Socialite::driver('github')->user();

    $user = User::firstOrCreate([
        'email' => $user->email
    ],[
        'name' => $user->name,
        'password' => bcrypt(Str::random(24))
    ]);

    Auth::login($user, true);

    return redirect('/dashboard');
});


require __DIR__.'/auth.php';
