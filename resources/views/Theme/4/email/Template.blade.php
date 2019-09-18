<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Quantum Scripts</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>
<body style="margin: 0; padding: 0; font-size: 16px; background-image: url('https://clients.quantumscripts.co.uk/assets/images/bg_2.png')">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td>
            <table align="center" border="1" cellpadding="0" cellspacing="0" width="600" style="border-collapse: collapse;">
                <tr>
                    <td>
                        <table align="center" border="0" cellpadding="0" cellspacing="0" width="600">
                            <tr>
                                <td bgcolor="#3B556F" align="center" style="padding: 40px 0 30px 0;">
                                    <img src="{!! url('/images/QheaderBase.png') !!}" alt="QuantumScripts" width="300" height="51" style="display: block;" />
                                </td>
                            </tr>
                            <tr>
                                <td bgcolor="#ffffff" style="padding: 40px 30px 40px 30px;">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                        <tr>
                                            <td style="font-weight: bold; font-size: 20px">
                                                @yield('title')
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 20px 0 30px 0;">
                                                @yield('content_html')
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td bgcolor="#34485E" align="center" style="padding: 10px 0 10px 0; color:#bababa; font-size: 14px">
                                    {!! date('Y') !!} &copy; Quantum Scripts
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
