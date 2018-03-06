<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Cookie;

class LoginController extends Controller
{
    public function showLogin() {

      // check if there are cookies set.
      $userId = Cookie::get('userId');
      $role = Cookie::get('role');

      // if there are cookies, use them to log in
      if (isset($userId) && isset($role)) {

        // set session data
        session()->push('userId', $userId);
        session()->push('role', $role);

        // send to route according to role
        switch ($role) {
          case 'worker':
            return redirect('/worker');
          case 'supervisor':
            return redirect('/supervisor');
          case 'coordinator':
            return redirect('/coordinator');
        }
      }

      // if there are no cookies, go to login screen.
      return view('login');

    }
    public function showLoginCoordinator() {
      return view('loginCoordinator');
    }
    public function loginCoordinator(Request $request) {
      // validate form data
      $request->validate([
        'email' => 'string|required',
        'password' => 'string|required'
      ]);

      $username = $request['email'] . "@maxwellsda.org";
      $password = sha1($request['password']);

      // attempt to find user in DB
      $users = DB::table('coordinators')->where('email', $username)->get();

      // go back if user not found
      if ($users->isEmpty()) {
        return back()->with('error', 'Invalid credentials');
      }

      $user = $users->first();

      // check password correct
      if ($password !== $user->password) {
        return back()->with('error', 'Invalid credentials');
      }

      // set session variables
      session(['userId' => $user->id]);
      session(['role' => 'coordinator']);

      // redirect to coordinator page
      return redirect('/coordinator');

    }
    public function showLoginSupervisor() {
      return view('loginSupervisor');
    }
    public function loginsupervisor(Request $request) {}
}
