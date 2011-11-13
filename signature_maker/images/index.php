<html>
    <head>
        <title>Seleziona un'immagine</title>
        <script language="JavaScript">
            function select(input)
            {
                document.getElementById("testo_immagine").src = document.getElementById(input).src;

                var field_edit = "<?php print $_GET["field_edit"]; ?>";
                if(field_edit != '')
                    eval("opener.document.getElementsByName('" + field_edit + "')[0].value = '" + input + "';");
            }
        </script>
    </head>
    <body>
        <center>
            <h3>Seleziona un'immagine</h3>
            <table cellSpacing="0" cellPadding="4" border="1" style="border-collapse: collapse" borderColor="111111">
                <?php
                    include("/../config.php");
                    foreach($backgrounds as $i => $value)
                    {
                        if($i) print "                "; //Gli indici sono numeri, quindi non è necessario usare un altro contatore.
                        print "<tr>\r\n";
                        print "                    <td>\r\n";
                        print "                        <center><a href=\"#ancora_testo\" onClick=\"select('$value');\"><img width=\"350\" id=\"$value\" src=\"bg/$value.png\" onContextMenu=\"return false;\"></a></center>\r\n";
                        print "                    </td>\r\n";
                        print "                </tr>\r\n";
                    }
                ?>
                <tr>
                    <td>
                        <center>
                            <a name="ancora_testo">
                            Immagine selezionata:<br>
                            <img id="testo_immagine" src="blank_64.png" width="350" height="50" onContextMenu="return false;">
                        </center>
                    </td>
                </tr>
            </table>
        </center>
    </body>
</html>