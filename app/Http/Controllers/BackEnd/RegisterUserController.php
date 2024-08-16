<?php

namespace App\Http\Controllers\BackEnd;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class RegisterUserController extends Controller
{
    public function index(Request $request)
    {
        $term = $request->term;

        $users = User::when($term, function ($query, $term) {
            $query->where('username', 'like', '%' . $term . '%')->orWhere('email', 'like', '%' . $term . '%');
        })->orderBy('id', 'desc')->paginate(10);
        return view('backend.register_user.index', compact('users'));
    }

    public function create()
    {
        return view('backend.register_user.create');
    }

    public function store(Request $request)
    {
        $rules = [
            'image' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'contact_number' => 'required',
            'city' => 'required',
            'country' => 'required',
            'username' => 'required|unique:users|max:255',
            'email' => 'required|email:rfc,dns|unique:users',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required'
        ];

        $message = [
            'password_confirmation.required' => 'The confirm password field is required.',
        ];

        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 400);
        }

        $in = $request->all();

        if ($request->hasFile('image')) {
            $extension = $request->file('image')->getClientOriginalExtension();
            $directory = public_path('assets/img/users/');
            $fileName = uniqid() . '.' . $extension;
            @mkdir($directory, 0775, true);
            $request->file('image')->move($directory, $fileName);
            $in['image'] = $fileName;
        }

        User::create($in);
        Session::flash('success', 'New user added successfully..!');
        return 'success';
    }

    public function update(Request $request, $id)
    {
        $user = User::where('id', $id)->first();
        $rules = [
            'first_name' => 'required',
            'last_name' => 'required',
            'contact_number' => 'required',
            'city' => 'required',
            'country' => 'required',
            'username' => [
                'required',
                'max:255',
            ],
            'email' => [
                'required',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ]
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 400);
        }

        $in = $request->all();

        if ($request->hasFile('image')) {
            $extension = $request->file('image')->getClientOriginalExtension();
            $directory = public_path('assets/img/users/');
            $fileName = uniqid() . '.' . $extension;
            @mkdir($directory, 0775, true);
            $request->file('image')->move($directory, $fileName);
            @unlink($directory . $user->image);
            $in['image'] = $fileName;
        }

        $user->update($in);
        Session::flash('success', 'User Updated successfully..!');
        return 'success';
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $information = [];
        $information['user'] = $user;
        return view('backend.register_user.edit', $information);
    }

    public function view($id)
    {
        $user = User::where('id', $id)->firstOrFail();
        return view('backend.register_user.details', compact('user'));
    }


    public function userban(Request $request)
    {
        $user = User::where('id', $request->user_id)->first();
        $user->update([
            'status' => $request->status,
        ]);

        Session::flash('success', $user->username . ' status update successfully!');
        return back();
    }


    public function emailStatus(Request $request)
    {
        $user = User::where('id', $request->user_id)->first();
        $user->update([
            'email_verified' => $request->email_verified,
        ]);

        Session::flash('success', 'Email status updated for ' . $user->username);
        return back();
    }

    public function delete(Request $request)
    {
        $user = User::where('id', $request->user_id)->first();

        if ($user->bookHotelRoom()->count() > 0) {
            $roomBookings = $user->bookHotelRoom()->get();
            foreach ($roomBookings as $key => $rb) {
                @unlink(public_path('assets/img/attachments/rooms/') . $rb->attachment);
                @unlink(public_path('assets/invoices/rooms/') . $rb->invoice);
                $rb->delete();
            }
        }
        //delete all support ticket
        $support_tickets = SupportTicket::where([['user_id', $user->id], ['user_type', 'user']])->get();

        if (count($support_tickets) > 0) {
            foreach ($support_tickets as $support_ticket) {
                //delete conversation 
                $messages = $support_ticket->messages()->get();
                foreach ($messages as $message) {
                    @unlink(public_path('assets/img/support-ticket/' . $message->file));
                    $message->delete();
                }
                @unlink(public_path('assets/img/support-ticket/') . $support_ticket->attachment);
                $support_ticket->delete();
            }
        }

        if ($user->giveReviewForRoom()->count() > 0) {
            $user->giveReviewForRoom()->delete();
        }

        if ($user->bookTourPackage()->count() > 0) {
            $packageBookings = $user->bookTourPackage()->get();
            foreach ($packageBookings as $key => $pb) {
                @unlink(public_path('assets/img/attachments/packages/') . $pb->attachment);
                @unlink(public_path('assets/invoices/packages/') . $pb->invoice);
                $pb->delete();
            }
        }

        if ($user->giveReviewForPackage()->count() > 0) {
            $user->giveReviewForPackage()->delete();
        }

        @unlink(public_path('assets/img/users/') . $user->image);
        $user->delete();

        Session::flash('success', 'User deleted successfully!');
        return back();
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            $user = User::where('id', $id)->first();

            if ($user->bookHotelRoom()->count() > 0) {
                $roomBookings = $user->bookHotelRoom()->get();
                foreach ($roomBookings as $key => $rb) {
                    @unlink(public_path('assets/img/attachments/rooms/') . $rb->attachment);
                    @unlink(public_path('assets/invoices/rooms/') . $rb->invoice);
                    $rb->delete();
                }
            }

            if ($user->giveReviewForRoom()->count() > 0) {
                $user->giveReviewForRoom()->delete();
            }

            if ($user->bookTourPackage()->count() > 0) {
                $packageBookings = $user->bookTourPackage()->get();
                foreach ($packageBookings as $key => $pb) {
                    @unlink(public_path('assets/img/attachments/packages/') . $pb->attachment);
                    @unlink(public_path('assets/invoices/packages/') . $pb->invoice);
                    $pb->delete();
                }
            }

            //delete all support ticket
            $support_tickets = SupportTicket::where([['user_id', $user->id], ['user_type', 'user']])->get();

            if (count($support_tickets) > 0) {
                foreach ($support_tickets as $support_ticket) {
                    //delete conversation 
                    $messages = $support_ticket->messages()->get();
                    foreach ($messages as $message) {
                        @unlink(public_path('assets/img/support-ticket/' . $message->file));
                        $message->delete();
                    }
                    @unlink(public_path('assets/img/support-ticket/') . $support_ticket->attachment);
                    $support_ticket->delete();
                }
            }

            if ($user->giveReviewForPackage()->count() > 0) {
                $user->giveReviewForPackage()->delete();
            }

            @unlink(public_path('assets/img/users/') . $user->image);
            $user->delete();
        }

        Session::flash('success', 'Users deleted successfully!');
        return "success";
    }


    public function changePass($id)
    {
        $data['user'] = User::where('id', $id)->firstOrFail();
        return view('backend.register_user.password', $data);
    }


    public function updatePassword(Request $request)
    {

        $messages = [
            'npass.required' => 'New password is required',
            'cfpass.required' => 'Confirm password is required',
        ];

        $request->validate([
            'npass' => 'required',
            'cfpass' => 'required',
        ], $messages);


        $user = User::where('id', $request->user_id)->first();
        if ($request->npass == $request->cfpass) {
            $input['password'] = Hash::make($request->npass);
        } else {
            return back()->with('error', __('Confirm password does not match.'));
        }

        $user->update($input);

        Session::flash('success', 'Password update for ' . $user->username);
        return back();
    }

    public function secret_login($id)
    {
        $user = User::where('id', $id)->first();
        Auth::guard('web')->login($user);
        Session::put('secret_login', 1);
        return redirect()->route('user.dashboard');
    }
}
