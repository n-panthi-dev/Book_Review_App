<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    public function register()
    {
        return view('account.register');
    }
    public function processRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:5',
            'password_confirmation' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->route('account.register')->withInput()->withErrors($validator->errors());
        }
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('account.login')->with('success', 'You have registerd successfully.');
    }
    public function login()
    {
        return view('account.login');
    }
    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:5',
        ]);
        if ($validator->fails()) {
            return redirect()->route('account.login')->withInput()->withErrors($validator);
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->route('account.profile');
        } else {
            return redirect()->route('account.login')->with('error', 'Either email/password is incorrect');
        }
    }
    public function profile()
    {
        $user = Auth::user(); // Dynamically fetch the authenticated user
        if (!$user) {
            return redirect()->route('account.login'); // Redirect if user is not logged in
        }

        return view('account.profile', ['user' => $user]);
    }


    public function updateProfile(Request $request)
    {
        $rules = [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email,' . Auth::user()->id . ',id',
        ];

        if (!empty($request->image)) {
            $rules['image'] = 'image|mimes:jpeg,png,bmp'; // Allow images only, max size: 5MB
        }



        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->route('account.profile')->withInput()->withErrors($validator);
        }

        // Update user details
        $user = User::find(Auth::user()->id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        // Handle profile image upload
        if (!empty($request->image)) {
            File::delete(public_path('uploads/profile/') . $user->image);

            $image = $request->image;
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/profile'), $imageName);

            // Assign the uploaded file's name to the user
            $user->image = $imageName;
            $user->save();
        }


        return redirect()->route('account.profile')->with('success', 'Profile updated successfully');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('account.login');
    }

    public function myReviews()
    {
        $reviews = Review::where('user_id', Auth::user()->id)->orderBy('created_at', 'DESC')->paginate(10);

        return view('account.my-reviews', [
            'reviews' => $reviews
        ]);
    }

    public function editReview($id)
    {
        $review = Review::where([
            'id' => $id,
            'user_id' => Auth::user()->id
        ])->with('book')->first();
        return view('account.my-reviews.edit-reviews', [
            'review' => $review
        ]);
    }
    public function updateReview($id, Request $request)
    {
        $review = Review::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'review' => 'required',
            'rating' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->route('account.myreviews.edit', $review->id)->withInput()->withErrors($validator);
        }
        $review->review = $request->review;
        $review->rating = $request->rating;
        $review->save();

        session()->flash('success', 'Review updated successfully.');
        return redirect()->route('account.myReviews');
    }

    public function deleteReview(Request $request)
    {
        $id = $request->id;
        $review = Review::find($id);

        if ($review === null) {
            return response()->json([
                'status' => false
            ]);
        }
        $review->delete();
        return response()->json([
            'status'=>true,
            'message'=>'Review deleted successfully'
        ]);
    }
}
