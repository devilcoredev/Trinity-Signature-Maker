<html>
    <head>
        <title>Tabella colori HTML</title>
        <script language="JavaScript" type="text/javascript" src="../jquery-1.7.1.min.js"></script>
        <script language="JavaScript">
            function selectButton(input)
            {
                if(!opener)
                    return;

                var colorValue = input.bgColor.replace('#', '');

                var r = opener.intToHex(255 - parseInt(colorValue.substring(0, 2), 16));
                var g = opener.intToHex(255 - parseInt(colorValue.substring(2, 4), 16));
                var b = opener.intToHex(255 - parseInt(colorValue.substring(4, 6), 16));

                $("#testo_colore").css("background-color", colorValue);
                $("#testo_colore").html("<font size='2' color='" + r + g + b + "'><b>" + colorValue.toUpperCase() + "</b></font>");

                var field_edit = "<?php print $_GET["field_edit"]; ?>";
                if(field_edit != '')
                {
                    opener.$('#' + field_edit).val(colorValue);
                    opener.colorField(opener.$('#' + field_edit));
                }
            }
        </script>
    </head>
    <body>
        <center>
            <h3>Tabella dei colori HTML</h3>
            <table cellSpacing="0" cellPadding="4" border="1" style="border-collapse: collapse" borderColor="111111">
                <tr>
                    <td onClick="selectButton(this);" bgColor="#00FF00" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#00FF33" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#00FF66" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#00FF99" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#00FFCC" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#00FFFF" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#006600" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#006633" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#006666" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#006699" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#0066CC" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#0066FF" style="cursor: hand; width: 15px; height: 20px;"></td>
                </tr>
                <tr>
                    <td onClick="selectButton(this);" bgColor="#33FF00" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#33FF33" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#33FF66" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#33FF99" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#33FFCC" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#33FFFF" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#336600" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#336633" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#336666" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#336699" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#3366CC" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#3366FF" style="cursor: hand; width: 15px; height: 20px;"></td>
                </tr>
                <tr>
                    <td onClick="selectButton(this);" bgColor="#66FF00" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#66FF33" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#66FF66" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#66FF99" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#66FFCC" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#66FFFF" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#666600" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#666633" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#666666" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#666699" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#6666CC" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#6666FF" style="cursor: hand; width: 15px; height: 20px;"></td>
                </tr>
                <tr>
                    <td onClick="selectButton(this);" bgColor="#99FF00" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#99FF33" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#99FF66" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#99FF99" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#99FFCC" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#99FFFF" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#996600" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#996633" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#996666" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#996699" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#9966CC" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#9966FF" style="cursor: hand; width: 15px; height: 20px;"></td>
                </tr>
                <tr>
                    <td onClick="selectButton(this);" bgColor="#CCFF00" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#CCFF33" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#CCFF66" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#CCFF99" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#CCFFCC" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#CCFFFF" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#CC6600" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#CC6633" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#CC6666" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#CC6699" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#CC66CC" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#CC66FF" style="cursor: hand; width: 15px; height: 20px;"></td>
                </tr>
                <tr>
                    <td onClick="selectButton(this);" bgColor="#FFFF00" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#FFFF33" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#FFFF66" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#FFFF99" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#FFFFCC" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#FFFFFF" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#FF6600" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#FF6633" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#FF6666" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#FF6699" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#FF66CC" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#FF66FF" style="cursor: hand; width: 15px; height: 20px;"></td>
                </tr>
                <tr>
                    <td onClick="selectButton(this);" bgColor="#00CC00" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#00CC33" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#00CC66" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#00CC99" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#00CCCC" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#00CCFF" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#003300" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#003333" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#003366" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#003399" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#0033CC" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#0033FF" style="cursor: hand; width: 15px; height: 20px;"></td>
                </tr>
                <tr>
                    <td onClick="selectButton(this);" bgColor="#33CC00" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#33CC33" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#33CC66" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#33CC99" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#33CCCC" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#33CCFF" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#333300" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#333333" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#333366" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#333399" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#3333CC" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#3333FF" style="cursor: hand; width: 15px; height: 20px;"></td>
                </tr>
                <tr>
                    <td onClick="selectButton(this);" bgColor="#66CC00" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#66CC33" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#66CC66" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#66CC99" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#66CCCC" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#66CCFF" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#663300" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#663333" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#663366" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#663399" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#6633CC" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#6633FF" style="cursor: hand; width: 15px; height: 20px;"></td>
                </tr>
                <tr>
                    <td onClick="selectButton(this);" bgColor="#99CC00" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#99CC33" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#99CC66" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#99CC99" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#99CCCC" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#99CCFF" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#993300" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#993333" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#993366" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#993399" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#9933CC" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#9933FF" style="cursor: hand; width: 15px; height: 20px;"></td>
                </tr>
                <tr>
                    <td onClick="selectButton(this);" bgColor="#CCCC00" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#CCCC33" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#CCCC66" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#CCCC99" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#CCCCCC" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#CCCCFF" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#CC3300" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#CC3333" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#CC3366" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#CC3399" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#CC33CC" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#CC33FF" style="cursor: hand; width: 15px; height: 20px;"></td>
                </tr>
                <tr>
                    <td onClick="selectButton(this);" bgColor="#FFCC00" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#FFCC33" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#FFCC66" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#FFCC99" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#FFCCCC" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#FFCCFF" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#FF3300" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#FF3333" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#FF3366" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#FF3399" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#FF33CC" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#FF33FF" style="cursor: hand; width: 15px; height: 20px;"></td>
                </tr>
                <tr>
                    <td onClick="selectButton(this);" bgColor="#009900" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#009933" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#009966" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#009999" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#0099CC" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#0099FF" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#000000" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#000033" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#000066" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#000099" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#0000CC" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#0000FF" style="cursor: hand; width: 15px; height: 20px;"></td>
                </tr>
                <tr>
                    <td onClick="selectButton(this);" bgColor="#339900" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#339933" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#339966" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#339999" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#3399CC" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#3399FF" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#330000" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#330033" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#330066" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#330099" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#3300CC" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#3300FF" style="cursor: hand; width: 15px; height: 20px;"></td>
                </tr>
                <tr>
                    <td onClick="selectButton(this);" bgColor="#669900" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#669933" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#669966" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#669999" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#6699CC" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#6699FF" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#660000" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#660033" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#660066" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#660099" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#6600CC" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#6600FF" style="cursor: hand; width: 15px; height: 20px;"></td>
                </tr>
                <tr>
                    <td onClick="selectButton(this);" bgColor="#999900" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#999933" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#999966" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#999999" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#9999CC" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#9999FF" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#990000" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#990033" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#990066" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#990099" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#9900CC" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#9900FF" style="cursor: hand; width: 15px; height: 20px;"></td>
                </tr>
                <tr>
                    <td onClick="selectButton(this);" bgColor="#CC9900" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#CC9933" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#CC9966" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#CC9999" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#CC99CC" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#CC99FF" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#CC0000" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#CC0033" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#CC0066" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#CC0099" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#CC00CC" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#CC00FF" style="cursor: hand; width: 15px; height: 20px;"></td>
                </tr>
                <tr>
                    <td onClick="selectButton(this);" bgColor="#FF9900" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#FF9933" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#FF9966" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#FF9999" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#FF99CC" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#FF99FF" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#FF0000" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#FF0033" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#FF0066" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#FF0099" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#FF00CC" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="#FF00FF" style="cursor: hand; width: 15px; height: 20px;"></td>
                </tr>
                <tr>
                    <td colspan="2" onClick="selectButton(this);" bgColor="#000000" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td colspan="2" onClick="selectButton(this);" bgColor="#333333" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td colspan="2" onClick="selectButton(this);" bgColor="#666666" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td colspan="2" onClick="selectButton(this);" bgColor="#999999" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td colspan="2" onClick="selectButton(this);" bgColor="#CCCCCC" style="cursor: hand; width: 15px; height: 20px;"></td>
                    <td colspan="2" onClick="selectButton(this);" bgColor="#FFFFFF" style="cursor: hand; width: 15px; height: 20px;"></td>
                </tr>
                <tr>
                    <td colspan="12">
                        <center>
                            Colore selezionato:<br />
                            <table cellSpacing="0" cellPadding="4" border="1" style="border-collapse: collapse" borderColor="111111">
                                <tr>
                                    <td id="testo_colore" bgColor="ffffff" style="width: 160px; height: 25px;" align="middle"></td>
                                </tr>
                            </table>
                        </center>
                    </td>
                </tr>
            </table>
        </center>
    </body>
</html>