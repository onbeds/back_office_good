<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta charset="utf-8"> <!-- utf-8 works for most cases -->
    <meta name="viewport" content="width=device-width"> <!-- Forcing initial-scale shouldn't be necessary -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge"> <!-- Use the latest (edge) version of IE rendering engine -->
    <meta name="x-apple-disable-message-reformatting">  <!-- Disable auto-scale in iOS 10 Mail entirely -->
    <title></title> <!-- The title tag shows in email notifications, like Android 4.4. -->

    <!-- Web Font / @font-face : BEGIN -->
    <!-- NOTE: If web fonts are not required, lines 10 - 27 can be safely removed. -->

    <!-- Desktop Outlook chokes on web font references and defaults to Times New Roman, so we force a safe fallback font. -->
    <!--[if mso]>
        <style>
            * {
                font-family: sans-serif !important;
            }
        </style>
    <![endif]-->

    <!-- All other clients get the webfont reference; some will render the font and others will silently fail to the fallbacks. More on that here: http://stylecampaign.com/blog/2015/02/webfont-support-in-email/ -->
    <!--[if !mso]><!-->
    <!-- insert web font reference, eg: <link href='https://fonts.googleapis.com/css?family=Roboto:400,700' rel='stylesheet' type='text/css'> -->
    <!--<![endif]-->

    <!-- Web Font / @font-face : END -->
    <link href="https://fonts.googleapis.com/css?family=Dosis:200,300,400,500,600,700,800" rel="stylesheet">
    <!-- CSS Reset : BEGIN -->
    <style>

        /* What it does: Remove spaces around the email design added by some email clients. */
        /* Beware: It can remove the padding / margin and add a background color to the compose a reply window. */
        html,
        body {
            margin: 0 auto !important;
            padding: 0 !important;
            height: 100% !important;
            width: 100% !important;
            font-family: 'Dosis', sans-serif;
        }

        /* What it does: Stops email clients resizing small text. */
        * {
            -ms-text-size-adjust: 100%;
            -webkit-text-size-adjust: 100%;
        }

        /* What it does: Centers email on Android 4.4 */
        div[style*="margin: 16px 0"] {
            margin: 0 !important;
        }

        /* What it does: Stops Outlook from adding extra spacing to tables. */
        table,
        td {
            mso-table-lspace: 0pt !important;
            mso-table-rspace: 0pt !important;
        }

        /* What it does: Fixes webkit padding issue. Fix for Yahoo mail table alignment bug. Applies table-layout to the first 2 tables then removes for anything nested deeper. */
        table {
            border-spacing: 0 !important;
            border-collapse: collapse !important;
            table-layout: fixed !important;
            margin: 0 auto !important;
        }
        table table table {
            table-layout: auto;
        }

        /* What it does: Uses a better rendering method when resizing images in IE. */
        img {
            -ms-interpolation-mode:bicubic;
        }

        /* What it does: A work-around for email clients meddling in triggered links. */
        *[x-apple-data-detectors],  /* iOS */
        .x-gmail-data-detectors,    /* Gmail */
        .x-gmail-data-detectors *,
        .aBn {
            border-bottom: 0 !important;
            cursor: default !important;
            color: inherit !important;
            text-decoration: none !important;
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
        }

        /* What it does: Prevents Gmail from displaying an download button on large, non-linked images. */
        .a6S {
            display: none !important;
            opacity: 0.01 !important;
        }
        /* If the above doesn't work, add a .g-img class to any image in question. */
        img.g-img + div {
            display: none !important;
        }

        /* What it does: Prevents underlining the button text in Windows 10 */
        .button-link {
            text-decoration: none !important;
        }

        /* What it does: Removes right gutter in Gmail iOS app: https://github.com/TedGoas/Cerberus/issues/89  */
        /* Create one of these media queries for each additional viewport size you'd like to fix */
        /* Thanks to Eric Lepetit @ericlepetitsf) for help troubleshooting */
        @media only screen and (min-device-width: 375px) and (max-device-width: 413px) { /* iPhone 6 and 6+ */
            .email-container {
                min-width: 375px !important;
            }
        }

        /* What it does: Forces Gmail app to display email full width */
        u ~ div .email-container {
          min-width: 100vw;
        }

    </style>
    <!-- CSS Reset : END -->

    <!-- Progressive Enhancements : BEGIN -->
    <style>

    /* What it does: Hover styles for buttons */
    .button-td,
    .button-a {
        transition: all 100ms ease-in;
    }
    .button-td:hover,
    .button-a:hover {
        background: #555555 !important;
        border-color: #555555 !important;
    }

    /* Media Queries */
    @media screen and (max-width: 480px) {

        /* What it does: Forces elements to resize to the full width of their container. Useful for resizing images beyond their max-width. */
        .fluid {
            width: 100% !important;
            max-width: 100% !important;
            height: auto !important;
            margin-left: auto !important;
            margin-right: auto !important;
        }

        /* What it does: Forces table cells into full-width rows. */
        .stack-column,
        .stack-column-center {
            display: block !important;
            width: 100% !important;
            max-width: 100% !important;
            direction: ltr !important;
        }
        /* And center justify these ones. */
        .stack-column-center {
            text-align: center !important;
        }

        /* What it does: Generic utility class for centering. Useful for images, buttons, and nested tables. */
        .center-on-narrow {
            text-align: center !important;
            display: block !important;
            margin-left: auto !important;
            margin-right: auto !important;
            float: none !important;
        }
        table.center-on-narrow {
            display: inline-block !important;
        }

        /* What it does: Adjust typography on small screens to improve readability */
        .email-container p {
            font-size: 17px !important;
        }
    }

    </style>
    <!-- Progressive Enhancements : END -->

    <!-- What it does: Makes background images in 72ppi Outlook render at correct size. -->
    <!--[if gte mso 9]>
    <xml>
        <o:OfficeDocumentSettings>
            <o:AllowPNG/>
            <o:PixelsPerInch>96</o:PixelsPerInch>
        </o:OfficeDocumentSettings>
    </xml>
    <![endif]-->
    
</head>
<body width="100%" style="margin: 0; mso-line-height-rule: exactly; background: url(https://cdn.shopify.com/s/files/1/2256/3751/files/concrete_seamless.png?9056281280774010986); background-position: top left" >
    <center style="width: 100%;  text-align: left;">

        <!-- Visually Hidden Preheader Text : BEGIN -->
        <div style="display: none; font-size: 1px; line-height: 1px; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all; font-family: sans-serif;">
            (Optional) This text will appear in the inbox preview, but not the email body. It can be used to supplement the email subject line or even summarize the email's contents. Extended text preheaders (~490 characters) seems like a better UX for anyone using a screenreader or voice-command apps like Siri to dictate the contents of an email. If this text is not included, email clients will automatically populate it using the text (including image alt text) at the start of the email's body.
        </div>
        <!-- Visually Hidden Preheader Text : END -->

        <!--
            Set the email width. Defined in two places:
            1. max-width for all clients except Desktop Windows Outlook, allowing the email to squish on narrow but never go wider than 680px.
            2. MSO tags for Desktop Windows Outlook enforce a 680px width.
            Note: The Fluid and Responsive templates have a different width (600px). The hybrid grid is more "fragile", and I've found that 680px is a good width. Change with caution.
        -->
        <div style="max-width: 680px; margin: auto; background: url(https://cdn.shopify.com/s/files/1/2256/3751/files/mailing-bienvenida.jpg?3438792757920694350); background-size: cover; background-position: center; background-repeat: no-repeat; box-shadow: 0px 0px 2px 3px rgba(0,0,0,.3);" class="email-container" >
            <!--[if mso]>
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="680" align="center">
            <tr>
            <td>
            <![endif]-->

            <!-- Email Header : BEGIN -->
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width: 680px;">
                <tr>
                    <td style="padding: 20px 0; text-align: center; ">
                        <img src="https://cdn.shopify.com/s/files/1/2256/3751/files/favicon.png?9056281280774010986" width="270" height="50" alt="alt_text" border="0" style="height: auto; font-family: sans-serif; font-size: 15px; line-height: 140%; color: #555555;">
                    </td>
                </tr>
            </table>
            <!-- Email Header : END -->

            <!-- Email Body : BEGIN -->
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width: 680px;" class="email-container">


                <!-- 1 Column Text + Button : BEGIN -->
                <tr>
                    <td>
                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tr>
                                <td style="padding: 40px 40px 20px; text-align: center;">
                                    <h1 style="margin: 0; font-family: 'Dosis', sans-serif; font-size: 34px; line-height: 125%; color: white; font-weight: bolder; ">¡Bienvenido a Good!</h1>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 0 40px 40px; font-family: sans-serif; font-size: 19px; line-height: 140%; color: white; text-align: center; font-family: 'Dosis', sans-serif;">
                                    <p style="margin: 0;">A partir de este momento tu vida tendrá un antes y un después, comenzarás a cuestionarte sobre todas las compras que has realizado en todo este tiempo, ya que Good cambiará la forma en cómo funciona el mercado Online y Offline en el mundo.<br>
                                    </p>
                                </td>
                            </tr>
                            

                        </table>
                    </td>
                </tr>
                <!-- 1 Column Text + Button : END -->

                  <!-- Hero Image, Flush : BEGIN -->
                <!--<tr>
                    <td align="center">
                        <img src="http://placehold.it/1360x600" width="680" height="" alt="alt_text" border="0" align="center" style="width: 100%; max-width: 680px; height: auto; font-family: sans-serif; font-size: 15px; line-height: 140%; color: #555555; margin: auto;" class="fluid g-img">
                    </td>
                </tr>-->
                <!-- Hero Image, Flush : END -->

                <!-- Background Image with Text : BEGIN -->
                <tr>
                    <!-- Bulletproof Background Images c/o https://backgrounds.cm -->
                    <td background="https://cdn.shopify.com/s/files/1/2256/3751/files/fondo_0b97d532-7f39-4c21-a21a-992c783cf061.png?14667147426592629453"  valign="middle" style="text-align: center; background-position: center center !important; background-size: cover !important; box-shadow: 0px 2px 3px 3px rgba(0,0,0,.3);">
                        <!--[if gte mso 9]>
                        <v:image xmlns:v="urn:schemas-microsoft-com:vml" fill="true" stroke="false" style="border: 0; display: inline-block; width: 680px; height: 180px;" src="http://placehold.it/680x180/222222/666666" />
                        <v:rect xmlns:v="urn:schemas-microsoft-com:vml" fill="true" stroke="false" style="border: 0; display: inline-block; position: absolute; width: 680px; height: 180px;">
                        <v:fill opacity="0%" color="#222222" />
                        <![endif]-->
                        <div>
                            <!--[if mso]>
                            <table role="presentation" border="0" cellspacing="0" cellpadding="0" align="center" width="500">
                            <tr>
                            <td align="center" valign="top" width="500">
                            <![endif]-->
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" align="center" width="100%" style="max-width:400px; margin: auto;">
                                <tr>
                                    <td valign="middle" style="text-align: center; padding: 40px 20px; font-family: 'Dosis'; font-size: 15px; line-height: 140%; color: #ffffff;">
                                        <p style="margin: 0;"> Para todos nosotros es normal hacer nuestras compras de mercado, ropa, tecnología, restaurantes y mil cosas más de la manera habitual, ya sea a través de una aplicación o directamente en la tienda, pero te has preguntado. ¿Cuantas personas al igual que tu hacen lo mismo todos los días?. </p>
                                    </td>
                                </tr>
                            </table>
                            <!--[if mso]>
                            </td>
                            </tr>
                            </table>
                            <![endif]-->
                        </div>
                        <!--[if gte mso 9]>
                        </v:fill>
                        </v:rect>
                        </v:image>
                        <![endif]-->
                    </td>
                </tr>
                <!-- Background Image with Text : END -->

                <!-- 2 Even Columns : BEGIN -->
    
                <!-- 2 Even Columns : END -->

                <!-- 3 Even Columns : BEGIN -->
             
                <!-- 3 Even Columns : END -->

                <!-- Thumbnail Left, Text Right : BEGIN -->
              
                <!-- Clear Spacer : END -->

                <!-- 1 Column Text : BEGIN -->
                <tr>
                    <td>
                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tr>
                                <td style="padding: 40px; font-family: sans-serif; font-size: 15px; line-height: 140%; color: white; font-family: 'Dosis'; text-align: center">
                                    <h1 style="margin: 0; font-family: 'Dosis', sans-serif; font-size: 34px; line-height: 125%; color: white; font-weight: bolder; ">Credenciales</h1>
                                    <p style="margin: 0 0 10px 0;">
                        Su usuario es: {{$usuario['usario']}} <br>
                        Su contraseña es: {{$usuario['password']}}                                      
                                    </p>

                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 0 40px 40px; font-family: sans-serif; font-size: 15px; line-height: 140%; color: #555555;">
                                    <!-- Button : BEGIN -->
                                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" style="margin: auto;">
                                        <tr>
                                            <td style="border-radius: 3px;  text-align: center;" class="button-td">
                                                <a href="http://tiendagood.com/" style="background: #222222; border: 15px solid #222222; font-family: sans-serif; font-size: 13px; line-height: 110%; text-align: center; text-decoration: none; display: block; border-radius: 3px; font-weight: bold;" class="button-a">
                                                    <span style="color:#ffffff;" class="button-link">Visita nuestra página</span>
                                                </a>
                                            </td>
                                        </tr>
                                    </table>
                                    <!-- Button : END -->
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <!-- 1 Column Text : END -->

            </table>
            <!-- Email Body : END -->

            <!-- Email Footer : BEGIN -->
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width: 680px; font-family: sans-serif; color: white; font-size: 12px; line-height: 140%; background: url(https://cdn.shopify.com/s/files/1/2256/3751/files/footer.png?9056281280774010986); background-size: cover; background-position: top; background-repeat: no-repeat;">
                <tr>
                    <td style="padding: 40px 10px; width: 100%; font-family: 'Dosis'; font-size: 22px; line-height: 140%; text-align: center; color: #888888; " class="x-gmail-data-detectors">
                        <webversion style="color: #cccccc; text-decoration: underline; font-weight: bold; font-family: 'Dosis'"></webversion>
                        <br><br>
                        Dirección: <br>Carrera 10 # 67A -09<br>
                    </td>
                </tr>
            </table>
            <!-- Email Footer : END -->

            <!--[if mso]>
            </td>
            </tr>
            </table>
            <![endif]-->
        </div>

        <!-- Full Bleed Background Section : BEGIN -->
        <table role="presentation" bgcolor="orange" cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
            <tr>
                <td valign="top" align="center">
                    <div style="max-width: 680px; margin: auto;" class="email-container">
                        <!--[if mso]>
                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="680" align="center">
                        <tr>
                        <td>
                        <![endif]-->
                        <!--[if mso]>
                        </td>
                        </tr>
                        </table>
                        <![endif]-->
                    </div>
                </td>
            </tr>
        </table>
        <!-- Full Bleed Background Section : END -->

    </center>
</body>
</html>