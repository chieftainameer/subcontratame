<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0;">
    <meta name="format-detection" content="telephone=no" />

    <!-- Responsive Mobile-First Email Template by Konstantin Savchenko, 2015.
 https://github.com/konsav/email-templates/  -->

    <style>
        /* Reset styles */
        body {
            margin: 0;
            padding: 0;
            min-width: 100%;
            width: 100% !important;
            height: 100% !important;
        }

        body,
        table,
        td,
        div,
        p,
        a {
            -webkit-font-smoothing: antialiased;
            text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            -webkit-text-size-adjust: 100%;
            line-height: 100%;
        }

        table,
        td {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
            border-collapse: collapse !important;
            border-spacing: 0;
        }

        img {
            border: 0;
            line-height: 100%;
            outline: none;
            text-decoration: none;
            -ms-interpolation-mode: bicubic;
        }

        #outlook a {
            padding: 0;
        }

        .ReadMsgBody {
            width: 100%;
        }

        .ExternalClass {
            width: 100%;
        }

        .ExternalClass,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td,
        .ExternalClass div {
            line-height: 100%;
        }

        /* Rounded corners for advanced mail clients only */
        @media all and (min-width: 560px) {
            .container {
                border-radius: 8px;
                -webkit-border-radius: 8px;
                -moz-border-radius: 8px;
                -khtml-border-radius: 8px;
            }
        }

        /* Set color for auto links (addresses, dates, etc.) */
        a,
        a:hover {
            color: #127DB3;
        }

        .footer a,
        .footer a:hover {
            color: #999999;
        }
    </style>

    <!-- MESSAGE SUBJECT -->
    <title>Recuperación de acceso</title>

</head>

<!-- BODY -->
<!-- Set message background color (twice) and text color (twice) -->

<body topmargin="0" rightmargin="0" bottommargin="0" leftmargin="0" marginwidth="0" marginheight="0" width="100%"
    style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; width: 100%; height: 100%; -webkit-font-smoothing: antialiased; text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; line-height: 100%;
 background-color: #F0F0F0;
 color: #000000;"
    bgcolor="#F0F0F0" text="#000000">
    <!-- SECTION / BACKGROUND -->
    <!-- Set message background color one again -->
    <table width="100%" align="center" border="0" cellpadding="0" cellspacing="0"
        style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; width: 100%;" class="background">
        <tr>
            <td align="center" valign="top"
                style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0;" bgcolor="#F0F0F0">

                <!-- WRAPPER / CONTEINER -->
                <!-- Set conteiner background color -->
                <table border="0" cellpadding="0" cellspacing="0" align="center" bgcolor="#FFFFFF" width="560"
                    style="border-collapse: collapse; border-spacing: 0; padding: 0; width: inherit;
 max-width: 560px;"
                    class="container">

                    <!-- HEADER -->
                    <!-- Set text color and font family ("sans-serif" or "Georgia, serif") -->
                    <tr>
                        <td align="center" valign="top"
                            style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%; font-size: 24px; font-weight: bold; line-height: 130%;
   padding-top: 25px;
   color: #000000;
   font-family: sans-serif;"
                            class="header">
                        </td>
                    </tr>

                    <!-- SUBHEADER -->
                    <!-- Set text color and font family ("sans-serif" or "Georgia, serif") -->
                    <tr>
                        <td align="center" valign="top"
                            style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-bottom: 3px; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%; font-size: 18px; font-weight: 300; line-height: 150%;
   padding-top: 5px;
   color: #000000;
   font-family: sans-serif;"
                            class="subheader">

                            <img border="0" vspace="0" hspace="0"
                                src="{{ asset('storage/default/pic2.jpg') }}" width="300" alt="Logo"
                                title="Logo"
                                style="
    color: #000000;
    font-size: 10px; margin: 0; padding: 0; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; border: none; display: block;" />
                        </td>
                    </tr>


                    <tr>
                        <td align="center" valign="top"
                            style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-bottom: 3px; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%; font-size: 18px; font-weight: 300; line-height: 150%;
   padding-top: 5px;
   color: #000000;
   font-family: sans-serif;"
                            class="subheader">
                            ENLACE PARA REESTABLECER SU CONTRASEÑA
                        </td>
                    </tr>

                    <!-- PARAGRAPH -->
                    <tr>
                        <td align="center" valign="top"
                            style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%; font-size: 17px; font-weight: 400; line-height: 160%;
   padding-top: 25px;
   color: #000000;
   font-family: sans-serif;"
                            class="paragraph">
                            Recientemente se te ha proporcionado un enlace para que reestablezaca tu contraseña y poder
                            acceder a tu cuenta. Para acceder debes darle click en el siguiente bot&oacute;n:
                        </td>
                    </tr>
                    @php
                        $route = route('client.reset-password') . '?token=' . $user->token_password;
                    @endphp
                    <!-- BUTTON -->
                    <tr>
                        <td align="center" valign="top"
                            style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%;
   padding-top: 25px;
   padding-bottom: 5px;"
                            class="button">
                            <a href="{{ $route }}" target="_blank" style="text-decoration: underline;">
                                <table border="0" cellpadding="0" cellspacing="0" align="center"
                                    style="max-width: 240px; min-width: 120px; border-collapse: collapse; border-spacing: 0; padding: 0;">
                                    <tr>
                                        <td align="center" valign="middle"
                                            style="padding: 12px 24px; margin: 0; text-decoration: underline; border-collapse: collapse; border-spacing: 0; border-radius: 4px; -webkit-border-radius: 4px; -moz-border-radius: 4px; -khtml-border-radius: 4px;"
                                            bgcolor="#8BC4F3"><a target="_blank"
                                                style="text-decoration: underline;
     color: #FFFFFF; font-family: sans-serif; font-size: 17px; font-weight: 400; line-height: 120%;"
                                                href="{{ $route }}">
                                                Cambiar contraseña
                                            </a>
                                        </td>
                                    </tr>
                                </table>
                            </a>
                        </td>
                    </tr>

                    <!-- LINE -->
                    <!-- Set line color -->
                    <tr>
                        <td align="center" valign="top"
                            style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%;
   padding-top: 25px;"
                            class="line">
                            <hr color="#E0E0E0" align="center" width="100%" size="1" noshade
                                style="margin: 0; padding: 0;" />
                        </td>
                    </tr>

                    <!-- LIST -->
                    <tr>
                        <td align="center" valign="top"
                            style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%;"
                            class="list-item">
                            <table align="center" border="0" cellspacing="0" cellpadding="0"
                                style="width: inherit; margin: 0; padding: 0; border-collapse: collapse; border-spacing: 0;">

                                <!-- LIST ITEM -->
                                <tr>

                                    <!-- LIST ITEM TEXT -->
                                    <!-- Set text color and font family ("sans-serif" or "Georgia, serif"). Duplicate all text styles in links, including line-height -->
                                    <td align="left" valign="top"
                                        style="font-size: 12px; font-weight: 400; line-height: 160%; border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0;
     padding-top: 25px;
     color: #000000;
     font-family: sans-serif;"
                                        class="paragraph">
                                        Si el botón para ir a reestablecer tu contraseña no funciona por favor copia y
                                        pega
                                        el
                                        siguiente enlace en tu navegador <span
                                            style="font-size:11px;">{{ $route }}
                                        </span>

                                    </td>

                                </tr>

                                <!-- LIST ITEM -->
                                <tr>



                            </table>
                        </td>
                    </tr>

                    <!-- LINE -->
                    <!-- Set line color -->
                    <tr>
                        <td align="center" valign="top"
                            style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%;
   padding-top: 25px;"
                            class="line">
                            <hr color="#E0E0E0" align="center" width="100%" size="1" noshade
                                style="margin: 0; padding: 0;" />
                        </td>
                    </tr>

                    <!-- PARAGRAPH -->
                    <!-- Set text color and font family ("sans-serif" or "Georgia, serif"). Duplicate all text styles in links, including line-height -->

                    <!-- End of WRAPPER -->
</body>

</html>
