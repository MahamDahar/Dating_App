@extends('layouts.user')

@section('usercontent')

<div style="display:flex; justify-content:center; align-items:center; height:80vh;">
    <div style="text-align:center; padding:40px; border:1px solid #ddd; border-radius:10px; width:400px;">

        <h2 style="color:red;">Delete Account</h2>

        <p>Are you sure you want to delete your account?</p>
        <p style="font-size:14px; color:gray;">
            This action cannot be undone.
        </p>

        <div style="margin-top:20px;">

            <!-- YES BUTTON -->
            <form action="{{ route('frontend.index') }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        style="background:red; color:white; padding:10px 20px; border:none; border-radius:5px;">
                    Yes, Delete
                </button>
            </form>

            <!-- NO BUTTON -->
            <a href="{{ url()->previous() }}"
               style="margin-left:10px; padding:10px 20px; background:gray; color:white; border-radius:5px; text-decoration:none;">
                No, Go Back
            </a>

        </div>

    </div>
</div>

@endsection