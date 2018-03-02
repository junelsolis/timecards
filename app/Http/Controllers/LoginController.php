<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Cookie;

class LoginController extends Controller
{
    public function main() {

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

    public function login(Request $request) {
      // validate form data
      $request->validate([
        'email' => 'string|required',
        'password' => 'string|required'
      ]);

      $role = $request['role'];
      $username = $request['email'] . "@maxwellsda.org";
      $password = sha1($request['password']);

      $users = NULL;

      // attempt to find user in DB
      switch ($role) {
        case 'worker':
          $users = DB::table('workers')->where('email', $username)->get();
        case 'supervisor':
          $users = DB::table('supervisors')->where('email', $username)->get();
        case 'coordinator':
          $users = DB::table('coordinators')->where('email', $username)->get();
      }

      // go back if user not found
      if ($users->isEmpty()) {
        return back()->with('error', 'Invalid credentials');
      }

      $user = $users[0];

      // check password correct
      if ($password !== $user->password) {
        return back()->with('error', 'Invalid credentials');
      }

      // redirect to appropriate route
      switch ($role) {
        case 'worker':
          session(['userId' => $user->id]);
          session(['role' => $role]);
          return redirect()->route('worker');

        case 'supervisor':
          session(['userId' => $user->id]);
          session(['role' => $role]);
          return redirect()->route('supervisor');

        case 'coordinator':
          session(['userId' => $user->id]);
          session(['role' => $role]);
          return redirect('coordinator');

      }


    }
}
