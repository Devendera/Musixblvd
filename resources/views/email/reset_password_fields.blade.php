@extends('layouts.email')

@section('content')

    <tr>
        <td class="email-body" width="100%">
            <table class="email-body_inner" align="center" width="570" cellpadding="0" cellspacing="0">
                <!-- Body content -->
                <tr>
                    <td class="content-cell">

                        @include('partials.errors')

                        <h1>Enter your new password</h1>

                        <!-- Action -->
                        <form action="{{ route('change.password') }}" method="post" style="margin-top: 20px">
                            <div class="form-group">
                                <label class="control-label mb-1">New Password</label>
                                <input name="password" type="password" dir="ltr" class="form-control" aria-required="true" aria-invalid="false">
                            </div>

                            <div class="form-group">
                                <label class="control-label mb-1">Confirm Password</label>
                                <input name="confirm_password" type="password" dir="ltr" class="form-control" aria-required="true" aria-invalid="false">
                            </div>

                            <input name="verification_code" type="text"  value="{{ $verification_code }}" hidden dir="ltr" class="form-control" aria-required="true" aria-invalid="false">

                            <div>
                                {{ csrf_field() }}
                                <button type="submit" class="btn btn-md btn-info btn-block" style="background-color: #0190ee">
                                    <span>Save</span>
                                </button>
                            </div>
                        </form>

                        <p style="margin-top: 20px">Thanks,<br>The Musixblvd Team</p>
                        <!-- Sub copy -->
                        <table class="body-sub">
                            <tr>
                                <td>
                                    <p class="sub" style="margin-top: 20px">If you’re having trouble resetting your password. Contact us.
                                    </p>
                                    <p class="sub"><a href="mailto:support@musixblvd.com">support@musixblvd.com</a></p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <table class="email-footer" align="center" width="570" cellpadding="0" cellspacing="0">
                <tr>
                    <td class="content-cell">
                        <p class="sub center">
                            Musixblvd
                            <br>Arizona, United States
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

@endsection