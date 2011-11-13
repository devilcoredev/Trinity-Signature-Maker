<html>
    <head>
        <title>Tabella colori HTML</title>
        <script language="JavaScript">
            function intToHex(d)
            {
                var hex = Number(d).toString(16);
                hex = "00".substr(0, 2 - hex.length) + hex;
                return hex;
            }

            function selectButton(input)
            {
                var colorValue = input.bgColor.replace('#', '');

                var r = 255 - parseInt(colorValue.substring(0, 2), 16);
                var g = 255 - parseInt(colorValue.substring(2, 4), 16);
                var b = 255 - parseInt(colorValue.substring(4, 6), 16);

                document.getElementById("testo_colore").bgColor = colorValue;
                document.getElementById("testo_colore").innerHTML = "<font size='2' color='" + intToHex(r) + intToHex(g) + intToHex(b) + "'><b>" + colorValue.toUpperCase() + "</b></font>";

                var field_edit = "<?php print $_GET["field_edit"]; ?>";
                if(field_edit != '')
                    eval("opener.document.getElementsByName('" + field_edit + "')[0].value = '" + colorValue + "';");
            }
        </script>
    </head>
    <body>
        <center>
            <h3>Tabella dei colori HTML</h3>
            <table cellSpacing="0" cellPadding="4" border="1" style="border-collapse: collapse" borderColor="111111">
                <tr>
                    <td onClick="selectButton(this);" bgColor="ccff00" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="ccff33" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="ccff66" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="ccff99" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="ccffcc" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="ccffff" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="ffff00" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="ffff33" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="ffff66" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="ffff99" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="ffffcc" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="ffffff" style="cursor: hand; width: 20px; height: 20px;"></td>
                </tr>
                <tr>
                    <td onClick="selectButton(this);" bgColor="ff0000" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="ff0033" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="ff0066" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="ff0099" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="ff00cc" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="ff00ff" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="cc0000" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="cc0033" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="cc0066" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="cc0099" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="cc00cc" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="cc00ff" style="cursor: hand; width: 20px; height: 20px;"></td>
                </tr>
                <tr>
                    <td onClick="selectButton(this);" bgColor="ff3300" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="ff3333" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="ff3366" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="ff3399" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="ff33cc" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="ff33ff" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="cc3300" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="cc3333" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="cc3366" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="cc3399" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="cc33cc" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="cc33ff" style="cursor: hand; width: 20px; height: 20px;"></td>
                </tr>
                <tr>
                    <td onClick="selectButton(this);" bgColor="ff6600" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="ff6633" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="ff6666" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="ff6699" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="ff66cc" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="ff66ff" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="cc6600" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="cc6633" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="cc6666" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="cc6699" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="cc66cc" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="cc66ff" style="cursor: hand; width: 20px; height: 20px;"></td>
                </tr>
                <tr>
                    <td onClick="selectButton(this);" bgColor="ff9900" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="ff9933" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="ff9966" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="ff9999" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="ff99cc" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="ff99ff" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="cc9900" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="cc9933" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="cc9966" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="cc9999" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="cc99cc" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="cc99ff" style="cursor: hand; width: 20px; height: 20px;"></td>
                </tr>
                <tr>
                    <td onClick="selectButton(this);" bgColor="ffcc00" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="ffcc33" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="ffcc66" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="ffcc99" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="ffcccc" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="ffccff" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="cccc00" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="cccc33" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="cccc66" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="cccc99" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="cccccc" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="ccccff" style="cursor: hand; width: 20px; height: 20px;"></td>
                </tr>
                <tr>
                    <td onClick="selectButton(this);" bgColor="66ff00" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="66ff33" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="66ff66" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="66ff99" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="66ffcc" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="66ffff" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="99ff00" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="99ff33" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="99ff66" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="99ff99" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="99ffcc" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="99ffff" style="cursor: hand; width: 20px; height: 20px;"></td>
                </tr>
                <tr>
                    <td onClick="selectButton(this);" bgColor="990000" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="990033" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="990066" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="990099" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="9900cc" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="9900ff" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="660000" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="660033" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="660066" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="660099" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="6600cc" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="6600ff" style="cursor: hand; width: 20px; height: 20px;"></td>
                </tr>
                <tr>
                    <td onClick="selectButton(this);" bgColor="993300" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="993333" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="993366" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="993399" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="9933cc" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="9933ff" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="663300" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="663333" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="663366" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="663399" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="6633cc" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="6633ff" style="cursor: hand; width: 20px; height: 20px;"></td>
                </tr>
                <tr>
                    <td onClick="selectButton(this);" bgColor="996600" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="996633" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="996666" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="996699" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="9966cc" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="9966ff" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="666600" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="666633" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="666666" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="666699" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="6666cc" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="6666ff" style="cursor: hand; width: 20px; height: 20px;"></td>
                </tr>
                <tr>
                    <td onClick="selectButton(this);" bgColor="999900" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="999933" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="999966" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="999999" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="9999cc" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="9999ff" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="669900" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="669933" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="669966" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="669999" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="6699cc" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="6699ff" style="cursor: hand; width: 20px; height: 20px;"></td>
                </tr>
                <tr>
                    <td onClick="selectButton(this);" bgColor="99cc00" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="99cc33" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="99cc66" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="99cc99" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="99cccc" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="99ccff" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="66cc00" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="66cc33" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="66cc66" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="66cc99" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="66cccc" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="66ccff" style="cursor: hand; width: 20px; height: 20px;"></td>
                </tr>
                <tr>
                    <td onClick="selectButton(this);" bgColor="00ff00" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="00ff33" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="00ff66" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="00ff99" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="00ffcc" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="00ffff" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="33ff00" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="33ff33" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="33ff66" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="33ff99" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="33ffcc" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="33ffff" style="cursor: hand; width: 20px; height: 20px;"></td>
                </tr>
                <tr>
                    <td onClick="selectButton(this);" bgColor="330000" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="330033" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="330066" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="330099" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="3300cc" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="3300ff" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="000000" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="000033" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="000066" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="000099" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="0000cc" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="0000ff" style="cursor: hand; width: 20px; height: 20px;"></td>
                </tr>
                <tr>
                    <td onClick="selectButton(this);" bgColor="333300" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="333333" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="333366" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="333399" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="3333cc" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="3333ff" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="003300" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="003333" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="003366" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="003399" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="0033cc" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="0033ff" style="cursor: hand; width: 20px; height: 20px;"></td>
                </tr>
                <tr>
                    <td onClick="selectButton(this);" bgColor="336600" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="336633" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="336666" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="336699" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="3366cc" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="3366ff" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="006600" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="006633" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="006666" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="006699" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="0066cc" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="0066ff" style="cursor: hand; width: 20px; height: 20px;"></td>
                </tr>
                <tr>
                    <td onClick="selectButton(this);" bgColor="339900" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="339933" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="339966" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="339999" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="3399cc" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="3399ff" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="009900" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="009933" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="009966" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="009999" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="0099cc" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="0099ff" style="cursor: hand; width: 20px; height: 20px;"></td>
                </tr>
                <tr>
                    <td onClick="selectButton(this);" bgColor="33cc00" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="33cc33" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="33cc66" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="33cc99" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="33cccc" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="33ccff" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="00cc00" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="00cc33" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="00cc66" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="00cc99" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="00cccc" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="00ccff" style="cursor: hand; width: 20px; height: 20px;"></td>
                </tr>
                <tr>
                    <td onClick="selectButton(this);" bgColor="ffffff" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="f2f2f2" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="e4e4e4" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="bebebe" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="999999" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="828282" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="666666" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="585858" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="505050" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="333333" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="171717" style="cursor: hand; width: 20px; height: 20px;"></td>
                    <td onClick="selectButton(this);" bgColor="000000" style="cursor: hand; width: 20px; height: 20px;"></td>
                </tr>
                <tr>
                    <td colspan="12">
                        <center>
                            Colore selezionato:
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