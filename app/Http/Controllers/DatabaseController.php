<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
//use Redirect;
//use Session;
//use DB;

class DatabaseController extends Controller
{
    public function InsertStaffData(Request $request){

      $session_type = Session::get('Session_Type');
      $session_value = Session::get('Session_Value');

      if($session_type == "Admin"){

        $this->validate($request, [
          'staff_id' => 'required',
          'first_name' => 'required',
          'last_name' => 'required',
          'date_of_birth' => 'required',
          'email' => 'required',
          'phone_number' => 'required',
          'position' => 'required',
        ]);

        $staff_id       = $request->staff_id;
        $first_name     = $request->first_name;
        $last_name      = $request->last_name;
        $date_of_birth  = $request->date_of_birth;
        $email          = $request->email;
        $phone_number   = $request->phone_number;
        $position       = $request->position;


        if (DB::table('staff_data')->where('staff_id', $staff_id)->doesntExist()) {

          if(DB::insert('INSERT INTO staff_data (staff_id, firstname, lastname, dob, email, phone_number, position) values (?, ?, ?, ?, ?, ?, ?)', [$staff_id, $first_name, $last_name, $date_of_birth, $email, $phone_number, $position])){

              return redirect()->back()->with('message', 'Registeration is Successful.');

          }

        }else{
          return redirect()->back()->withErrors("<strong>Unable to register:</strong> The given staff ID already exists in the database");
        }

      }

    }

    public function DeleteStaffData($auto_id){

       $session_type = Session::get('Session_Type');

       if($session_type == "Admin"){

           if(DB::table('staff_data')->where('auto_id', '=', $auto_id)->delete()){

               return redirect()->back()->with('message', 'Deletion is Successful.');
           }

       }else{

           return Redirect::to("/");

       }

   }

   public function UpdateStaffData(Request $request){

      $session_type = Session::get('Session_Type');

      if($session_type == "Admin"){

        $this->validate($request, [
          'auto_id' => 'required',
          'first_name' => 'required',
          'last_name' => 'required',
          'date_of_birth' => 'required',
          'email' => 'required',
          'phone_number' => 'required',
          'position' => 'required',
        ]);

        $auto_id        = $request->auto_id;
        $first_name     = $request->first_name;
        $last_name      = $request->last_name;
        $date_of_birth  = $request->date_of_birth;
        $email          = $request->email;
        $phone_number   = $request->phone_number;
        $position       = $request->position;


        if(DB::table('staff_data')->where('auto_id', $auto_id)->update(['firstname' => $first_name, 'lastname' => $last_name, 'dob' => $date_of_birth, 'email' => $email, 'phone_number' => $phone_number, 'position' => $position])){

          return Redirect::to("/view-staff-management-index")->with('message', 'Updation is Successful.');

        }else{

          return Redirect::to("/view-staff-management-index")->with('message', 'No changes made.');
        }

      }else{

          return Redirect::to("/");

      }

  }


  public function ChangeUsername(Request $request){

     $session_type = Session::get('Session_Type');

     if($session_type == "Admin"){

        $admin_data = DB::table('user_account')->where("account_type", "admin")->get(); // Get staff data.

        if($request->password == $admin_data[0]->password){

          if(DB::table('user_account')->where('account_type', 'admin')->update(['username'=>$request->username])){

              return redirect()->back()->with('message', 'Username has been updated successfully.');

          }else{

            return redirect()->back()->with('message', 'No changes made.');

          }


        }else{

          return redirect()->back()->withErrors('The password is wrong.');
        }

     }else{

         return Redirect::to("/");

     }

 }

 public function ChangePassword(Request $request){

    $session_type = Session::get('Session_Type');

    if($session_type == "Admin"){

       $admin_data = DB::table('user_account')->where("account_type", "admin")->get(); // Get staff data.

       if($request->current_password == $admin_data[0]->password){

         if($request->new_password == $request->confirm_password){

           if(DB::table('user_account')->where('account_type', 'admin')->update(['password'=>$request->new_password])){

               return redirect()->back()->with('message', 'Password has been updated successfully.');

           }else{

             return redirect()->back()->with('message', 'No changes made.');

           }

         }else{

           return redirect()->back()->withErrors('The confirm password does not match');

         }

       }else{

         return redirect()->back()->withErrors('The current password is wrong.');
       }

    }else{

        return Redirect::to("/");

    }

  }

  public function EditUserAccount(Request $request){

     $session_type = Session::get('Session_Type');

     if($session_type == "Admin"){

       $this->validate($request, [
         'username' => 'required',
         'password' => 'required',
       ]);


       $username  =  $request->username;
       $password  =  $request->password;
       $auto_id   =  $request->auto_id;

       if(DB::table('user_account')->where('auto_id', $auto_id)->update(['username' => $username, 'password' => $password])){

         return Redirect::to("/view-user-accounts-index")->with('message', 'Updation is Successful.');

       }else{

         return Redirect::to("/view-user-accounts-index")->with('message', 'No changes made.');
       }


     }else{

         return Redirect::to("/");

     }
  }

  public function DeleteUserAccount($auto_id){

     $session_type = Session::get('Session_Type');

     if($session_type == "Admin"){

         if(DB::table('user_account')->where('auto_id', '=', $auto_id)->delete()){

             return redirect()->back()->with('message', 'Deletion is Successful.');
         }

     }else{

         return Redirect::to("/");

     }

 }

 public function InsertUserAccount(Request $request){

   $session_type = Session::get('Session_Type');
   $session_value = Session::get('Session_Value');

   if($session_type == "Admin"){

     $this->validate($request, [
       'staff_id' => 'required',
       'username' => 'required',
       'password' => 'required',
     ]);

     $staff_id  =  $request->staff_id;
     $username  =  $request->username;
     $password  =  $request->password;


     if (DB::table('user_account')->where('staff_id', $staff_id)->doesntExist()) {

       if (DB::table('user_account')->where('username', $username)->doesntExist()) {

         if(DB::insert('INSERT INTO user_account (staff_id, username, password, account_type) values (?, ?, ?, ?)', [$staff_id, $username, $password, "staff"])){

             return redirect()->back()->with('message', 'Account creation is Successful.');
         }

       }else{

         return redirect()->back()->withErrors("<strong>Unable to create:</strong> The given username already exists in the database.");

       }

     }else{

       return redirect()->back()->withErrors("<strong>Unable to create:</strong> The staff already has an account");

     }
   }
 }

 public function AcceptRequest($auto_id){

   $session_type = Session::get('Session_Type');
   $session_value = Session::get('Session_Value');

   if($session_type == "Admin"){

     if(DB::table('leave_data')->where(["auto_id"=>$auto_id])->update(['approval_status'=>"[ACCEPTED]"])){

         return redirect()->back()->with('message', 'Accepted');

     }else{

       return redirect()->back()->with('message', 'No changes made.');

     }

   }else{

        return Redirect::to("/");

   }

 }

 public function DeclineRequest($auto_id){

   $session_type = Session::get('Session_Type');
   $session_value = Session::get('Session_Value');

   if($session_type == "Admin"){

     if(DB::table('leave_data')->where(["auto_id"=>$auto_id])->update(['approval_status'=>"[DECLINED]"])){

         return redirect()->back()->with('message', 'Declined');

     }else{

       return redirect()->back()->with('message', 'No changes made.');

     }

   }else{

        return Redirect::to("/");

   }

 }

 public function ChangeUsernameOfStaffAccount(Request $request){

    $session_type = Session::get('Session_Type');
    $session_value = Session::get('Session_Value');

    if($session_type == "Staff"){

       $staff_data = DB::table('user_account')->where(["account_type" => "staff", "staff_id" => $session_value])->get(); // Get staff data.

       if($request->password == $staff_data[0]->password){

         if(DB::table('user_account')->where(["account_type" => "staff", "staff_id" => $session_value])->update(['username'=>$request->username])){

             return redirect()->back()->with('message', 'Username has been updated successfully.');

         }else{

           return redirect()->back()->with('message', 'No changes made.');

         }


       }else{

         return redirect()->back()->withErrors('The password is wrong.');
       }

    }else{

        return Redirect::to("/");

    }

  }

  public function ChangePasswordOfStaffAccount(Request $request){

    $session_type = Session::get('Session_Type');
    $session_value = Session::get('Session_Value');

    if($session_type == "Staff"){

       $staff_data = DB::table('user_account')->where(["account_type" => "staff", "staff_id" => $session_value])->get(); // Get staff data.

       if($request->current_password == $staff_data[0]->password){

         if($request->new_password == $request->confirm_password){

           if(DB::table('user_account')->where(["account_type" => "staff", "staff_id" => $session_value])->update(['password'=>$request->new_password])){

               return redirect()->back()->with('message', 'Password has been updated successfully.');

           }else{

             return redirect()->back()->with('message', 'No changes made.');

           }

         }else{

           return redirect()->back()->withErrors('The confirm password does not match');

         }

       }else{

         return redirect()->back()->withErrors('The current password is wrong.');
       }

    }else{

        return Redirect::to("/");

    }

  }


  public function InsertLeaveDataOfStaffAccount(Request $request){

  $session_type = Session::get('Session_Type');
  $session_value = Session::get('Session_Value');

  if($session_type == "Staff"){

    $this->validate($request, [
      'type_of_leave' => 'required',
      'description' => 'required',
      'date_of_leave' => 'required',
      'last_date_of_leave' => 'required',
    ]);

      $staff_id          =  $session_value;
      $type_of_leave     =  $request->type_of_leave;
      $description       =  $request->description;
      $date_of_leave     =  $request->date_of_leave;
      $last_date_of_leave = $request->last_date_of_leave;
      $date_of_request   =  date('Y-m-d H:i:s');
      $approval_status	  =  "[PENDING]";

      // $this->calculateLeaveDays($request);


      if(DB::insert('INSERT INTO leave_data (staff_id, type_of_leave, description, date_of_leave, last_date_of_leave, date_of_request, approval_status ) values (?, ?, ?, ?, ?, ?, ?)', [$staff_id, $type_of_leave, $description, $date_of_leave, $last_date_of_leave, $date_of_request, $approval_status])){

        return redirect()->back()->with('message', 'Leave request has been submited successfully.');

      }
    }
  }

  public function calculateLeaveDays(Request $request) {
    $session_type = Session::get('Session_Type');
    $session_value = Session::get('Session_Value');

    if ($session_type == "Staff") {
        $this->validate($request, [
            'type_of_leave' => 'required',
            'description' => 'required',
            'date_of_leave' => 'required',
            'last_date_of_leave' => 'required',
        ]);

        $staff_id = $session_value;
        $type_of_leave = $request->type_of_leave;
        $date_of_leave = $request->date_of_leave;
        $last_date_of_leave = $request->last_date_of_leave;

        // Define the default maternity leave days
        $defaultMaternityLeaveDays = 90;

        // Convert start and end dates to DateTime objects
        $startDateTime = $date_of_leave;
        $endDateTime = $last_date_of_leave;

        // Calculate total days between start and end dates
        $interval = $startDateTime->diff($endDateTime);
        $totalDays = $interval->days + 1; // Including both start and end dates

        // Perform leave type calculation
        switch ($type_of_leave) {
          case 'Maternity leave':
            $totalDays = $defaultMaternityLeaveDays;
            break;
          case 'Sick leave':
            $totalDays = $totalDays++;
            break;
          case 'Casual leave':
            $totalDays = $totalDays++;
            break;
          case 'Duty Leave':
            $totalDays = $totalDays++;
            break;
          case 'Paternity leave':
            $totalDays = $totalDays++;
            break;
          case 'Bereavement leave':
            $totalDays = $totalDays++;
            break;
          case 'Compensatory leave':
            $totalDays = $totalDays++;
            break;
          case 'Sabbatical leave':
            $totalDays = $totalDays++;
            break;
          case 'Unpaid Leave':
            $totalDays = $totalDays++;
            break;
            // Add other leave types here...
        }

        // Subtract from the default value (21) based on leave type
        $remainingDays = 21 - $totalDays;

        // Update leave days in the user_account table
        DB::table('user_account')->where('staff_id', $staff_id)->update(['leave_days_remaining' => $remainingDays]);
    }
}
   public function DeleteLeavePendingRequestInStaffAccount($auto_id){

      $session_type = Session::get('Session_Type');

      if($session_type == "Staff"){

        if(DB::table('leave_data')->where('auto_id', '=', $auto_id)->delete()){

            return redirect()->back()->with('message', 'Deletion is Successful.');
        }

      }else{

          return Redirect::to("/");

      }

  }


}

?>
