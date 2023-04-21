<!doctype html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0,user-scalable=0"/> 
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Musixblvd</title>

    <!-- Fontfaces CSS-->
    <link href="{{ URL::to('/css/font-face.css') }}" rel="stylesheet" media="all"/>
    <link href="{{ URL::to('/vendor/font-awesome-5/css/fontawesome-all.min.css') }}" rel="stylesheet" media="all"/>
    <link href="{{ URL::to('/vendor/font-awesome-4.7/css/font-awesome.min.css') }}" rel="stylesheet" media="all"/>
    <link href="{{ URL::to('/vendor/mdi-font/css/material-design-iconic-font.min.css') }}" rel="stylesheet" media="all"/>

    <!-- Bootstrap CSS-->
    <link href="{{ URL::to('/vendor/bootstrap-4.1/bootstrap.min.css') }}" rel="stylesheet" media="all"/>

    <link type="text/css" rel="stylesheet" href="{{ URL::to('/css/email.css') }}"/>

</head>
<body>

<table class="email-wrapper" width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td align="center">
            <table class="email-content" width="100%" cellpadding="0" cellspacing="0">
                <!-- Logo -->
                <tr>
                    <td class="email-masthead">
                        <img class="email-masthead_name" style="width: 150px; height: 150px;" src="{{ URL::to('/img/other/logo.jpeg') }}"/>
                    </td>
                </tr>
                <!-- Email Body -->
                @yield('content')
            </table>
        </td>
    </tr>
</table>

</body>
</html>