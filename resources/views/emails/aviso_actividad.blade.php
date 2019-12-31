<!DOCTYPE html>
<html lang="es-ES">
<head>
    <meta charset="utf-8">
    <style type="text/css">
        @media only screen and (max-width : 640px) {
    
        table[class="container"] {
            width: 98% !important;
        } 
        td[class="bodyCopy"] p {
            padding: 0 15px !important; 
            text-align: left !important;
        }
        td[class="spacer"] {
            width: 15px !important;
        }
        td[class="belowFeature"] {
            width: 95% !important;
            display: inline-block;
            padding-left: 15px;
            margin-bottom: 20px;
        }
        td[class="belowFeature"] img {
            float: left;
            margin-right: 15px;
        }
        
        table[class="belowConsoles"] {
            width: 100% !important;
            display: inline-block;
        }

        table[class="belowConsoles"] img {
            margin-right: 15px;
            margin-bottom: 15px;
            float: left;
        }
       
        
        }
        
        @media only screen and (min-width: 481px) and (max-width: 560px) {
        
        td[class="Logo"] {
            width: 560px !important;
            text-align: center;
        }
        
        td[class="viewWebsite"] {
            width: 560px !important;
            height: inherit !important;
            text-align: center;
        }    

        }
        
        @media only screen and (min-width: 250px) and (max-width: 480px) {
        
        td[class="Logo"] {
            width: 480px !important;
            text-align: center;
        }
        
        td[class="viewWebsite"] {
            width: 480px !important;
            height: inherit !important;
            text-align: center;
        }
        
        td[class="spacer"] {
            display: none !important;
        }
        
        td[class="bodyCopy"] p {
            padding: 0 15px !important; 
            text-align: left !important;
        }
        
        td[class="bodyCopy"] h1 {
            padding: 0 10px !important;
        }
        
        h1, h2 {
            line-height: 120% !important;
        }
        
        td[class="force-width"] {width: 98% !important; padding: 0 10px;}
        
        [class="consoleImage"] {
            display: inline-block;
        }

        [class="consoleImage"] img {
            text-align: center !important;
            display: inline-block;
        }

        table[class="belowConsoles"] {
            text-align: center;
            float: none;
            margin-bottom: 15px;
            width: 100% !important;
        }

        table[class="belowConsoles"] img {
            margin-bottom: 0;
        }
        
        }
    </style>
</head>
<body bgcolor="#f6f6f6" style="font-family: Arial; background-color: #f6f6f6;">

<table width="630" style="background-color: #3D3C3C;" class="container" align="center" cellpadding="0" cellspacing="0">
    <tr>
        <td>
            <table align="right">
                <tr>
                    <td height="70" class="viewWebsite">
                        <p style="font-family: Arial, Helvetica, sans-serif; color: #f6f6f6; font-size: 18; padding: 25px; margin: 10px;">Actividades pendientes</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<table width="630" bgcolor="#fcfcfc" style="border: 1px solid #dddddd; line-height: 135%;" class="container" align="center" cellpadding="0" cellspacing="0">
    <tr>
        <td bgcolor="#fcfcfc" colspan="3" width="25%" height="10">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="3" height="15">&nbsp;</td>
    </tr>
    <tr>
        <td bgcolor="#fcfcfc" colspan="3">
            <table>
                <tr>
                    <td width="30" class="spacer">&nbsp;</td>
                    <td align="left" class="bodyCopy">
                        <h1 style="font-family: Arial, Helvetica, sans-serif; font-size: 30px; color: #404040; margin-top: 0; margin-bottom: 20px; padding: 0; line-height: 135%" class="headline">Hola {{ $responsable }}, tienes una actividad sin completar:</h1>
                        <p><b>Titulo:</b> <a href="{{ env('APP_URL').'/actividad/show/'.$id_actividad.'/Menu' }}">{{ $titulo }}</a></p>
                        <p><b>Fecha:</b> {{ date('Y-M-d',strtotime($fecha)) }}</p>
                        <p><b>Hora:</b> {{ date('H:i A',strtotime($hora)) }}</p>
                        @if ($prospecto != '' and $prospecto != null)
                        <p><b>Prospecto:</b> {{ $prospecto }}</p>
                        @endif
                        <p><b>Descripción:</b> {{ $descripcion }}</p>
                        <p></p>
                        <p style="text-align: center; font-style: italic;">Buena suerte hoy!</p>
                    </td>
                    <td width="30" class="spacer">&nbsp;</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="3" height="3">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="3" height="3">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="3" class="force-width">
            <table width="213" align="left" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="belowConsoles">
                <tr>
                    <td width="35" class="spacer"></td>
                    <td>
                        <p style="font-style: italic; font-weight: bold; font-size: 10pt;">No responder a este mensaje</p>
                    </td>
                    <td width="15" class="spacer"></td>
                </tr>
            </table>
        </td>
    </tr>
     <tr>
        <td colspan="3" height="3">&nbsp;</td>
    </tr>
</table>
</body>
</html>