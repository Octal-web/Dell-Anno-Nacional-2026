<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dell Anno</title>
</head>
<body style="margin:0;padding:0;background-color:#ffffff;color:#161616;font-family:Calibri,Helvetica,Arial,sans-serif;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" bgcolor="#ffffff">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellspacing="0" cellpadding="0" border="0" style="width:100%;max-width:600px;margin:0 auto;">
                    <tr>
                        <td align="center" style="padding:30px 20px 20px;border-bottom:1px solid #161616;">
                            <img src="{{ $message->embed(public_path('site/img/logo-black.png')) }}" alt="Dell Anno" width="150" style="display:block;width:150px;height:auto;border:0;">
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:40px 30px;">
                            @yield('content')
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="padding:30px;border-top:1px solid #161616;border-bottom:1px solid #161616;font-size:13px;">
                            <a href="https://www.facebook.com/DellAnnoOficial/" style="color:#161616;">Facebook</a>
                            &nbsp;&nbsp;
                            <a href="https://www.instagram.com/dellannooficial/" style="color:#161616;">Instagram</a>
                            &nbsp;&nbsp;
                            <a href="https://br.pinterest.com/dellanno/" style="color:#161616;">Pinterest</a>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="padding:20px 30px;">
                            <img src="{{ $message->embed(public_path('site/img/logo-black.png')) }}" alt="Dell Anno" width="150" style="display:block;width:150px;height:auto;border:0;">
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="padding:0 30px 40px;color:#002939;font-size:11px;line-height:17px;">
                            Você está recebendo este conteúdo em seu e-mail pois seu e-mail está cadastrado no site
                            <a href="{{ config('app.url') }}" style="color:#000000;font-weight:bold;">{{ config('app.url') }}</a>.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
