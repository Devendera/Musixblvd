@extends('layouts.email')

@section('content')

    <tr>
        <td class="email-body" width="100%">
            <table class="email-body_inner" align="center" width="570" cellpadding="0" cellspacing="0">
                <!-- Body content -->
                <tr>
                    <td class="content-cell">
                        <h1>Email Verified!</h1>
                        <p>Your email verified successfully you can login</p>

                        <p>Thanks,<br>The Musixblvd Team</p>
                        <!-- Sub copy -->
                        <table class="body-sub">
                            <tr>
                                <td>
                                    <p class="sub" style="margin-top: 20px">If you’re having any trouble. Contact us.
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