@extends('layouts.email')

@section('content')

    <tr>
        <td class="email-body" width="100%">
            <table class="email-body_inner" align="center" width="570" cellpadding="0" cellspacing="0">
                <!-- Body content -->
                <tr>
                    <td class="content-cell">
                        <h1>Reset your password</h1>
                        <p>We have received a forgot password request. If you didn't take that action please ignore that email.</p>
                        <!-- Action -->
                        <table class="body-action" align="center" width="100%" cellpadding="0" cellspacing="0">
                            <tr>
                                <td align="center">
                                    <div>
                                        <!--[if mso]><v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="{{ url('user/verify', $data['verification_code']) }}" style="height:45px;v-text-anchor:middle;width:200px;" arcsize="7%" stroke="f" fill="t">
                                            <v:fill type="tile" color="#414EF9" />
                                            <w:anchorlock/>
                                            <center style="color:#ffffff;font-family:sans-serif;font-size:15px;">Reset Password</center>
                                        </v:roundrect><![endif]-->
                                        <a href="{{ url('user/reset-password', $data['verification_code']) }}" class="button button--blue">Reset Password</a>
                                    </div>
                                </td>
                            </tr>
                        </table>
                        <p>Thanks,<br>The Musixblvd Team</p>
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