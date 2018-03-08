<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Cookie;

class LoginController extends Controller
{
    public function showLogin() {


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
      session(['fullname' => $user->firstname . ' ' . $user->lastname]);

      // redirect to coordinator page
      return redirect('/coordinator');

    }
    public function showLoginSupervisor() {
      return view('loginSupervisor');
    }
    public function loginSupervisor(Request $request) {
      // validate form data
      $request->validate([
        'email' => 'string|required',
        'password' => 'string|required'
      ]);

      $username = $request['email'] . "@maxwellsda.org";
      $password = sha1($request['password']);

      // attempt to find user in DB
      $users = DB::table('supervisors')->where('email', $username)->get();

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
      session(['role' => 'supervisor']);
      session(['fullname' => $user->firstname . ' ' . $user->lastname]);

      // redirect to coordinator page
      return redirect('/supervisor');

    }

    public function logout() {
      // delete session variables
      session()->forget('userId');
      session()->forget('role');
      session()->forget('fullname');

      // redirect to login page
      return redirect('/');
    }
}
