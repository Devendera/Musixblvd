@extends('layouts.email')

@section('content')
    <tr>
        <td class="email-body" width="100%">
            <table class="email-body_inner" align="center" width="570" cellpadding="0" cellspacing="0">
                <!-- Body content -->
                <tr>
                    <td class="content-cell">
                        <h1>Join Musixblvd</h1>
                        <p>Take one step forward towards success in the music industry. There's one common denominator that applies to each and every contact you'll make here:

                            A dedicated commitment to excellence in your craft, coupled with a willingness to meet or exceed the requirements for sustained success in the music industry. Make the administrative workflow fast, smooth, simple; so you have more time for the fun part of your job.</p>
                        <!-- Action -->
                        <table class="body-action" align="center" width="100%" cellpadding="0" cellspacing="0">
                            <tr>
                                <td align="center">
                                    <div>
                                        <a href="https://musixblvd.com/"><img class="img-responsive" width="200px" height="80px" alt="" src="{{ URL::to('/img/other/play_store.png') }}"></a>
                                    </div>
                                </td>

                                <td align="center">
                                    <div>
                                        <a href="https://musixblvd.com/"><img class="img-responsive" width="200px" height="80px" alt="" src="{{ URL::to('/img/other/app_store.png') }}"></a>
                                    </div>
                                </td>

                            </tr>
                        </table>
                        <p>Thanks,<br>The Musixblvd Team</p>
                        <!-- Sub copy -->
                        <table class="body-sub">
                            <tr>
                                <td>
                                    <p class="sub" style="margin-top: 20px">If you’re having trouble. Contact us.
                                    </p>
                                    <p class="sub"><a href="mailto:support@musicxblvd.com">support@musixblvd.com</a></p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
@endsection