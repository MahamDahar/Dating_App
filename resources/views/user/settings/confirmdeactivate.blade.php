@extends('layouts.user')

@section('usercontent')

<div style="display:flex; justify-content:center; align-items:center; height:90vh; background:#f8f9fa;">

    <div style="background:white; padding:40px; width:420px; border-radius:12px; box-shadow:0 5px 20px rgba(0,0,0,0.1); text-align:center;">

        <h2 style="color:#ff9800; margin-bottom:20px;">
            Deactivate Account
        </h2>

        <p style="font-size:15px; margin-bottom:10px;">
            Your account will be hidden immediately.
        </p>

        <p style="color:#d32f2f; font-size:14px; margin-bottom:25px;">
            ⚠️ If you do not log back in within 15 days,
            your account will be permanently deleted.
        </p>

        <!-- YES BUTTON -->
        <form action="{{ route('login') }}" method="POST">
            @csrf
            <button type="submit"
                    style="background:#ff9800; color:white; border:none; padding:10px 20px; border-radius:6px; cursor:pointer; width:100%; margin-bottom:10px;">
                Yes, Deactivate My Account
            </button>
        </form>

        <!-- NO BUTTON -->
        <a href="{{ url()->previous() }}"
           style="display:block; background:#6c757d; color:white; padding:10px 20px; border-radius:6px; text-decoration:none;">
            No, Go Back
        </a>

    </div>

</div>

@endsection