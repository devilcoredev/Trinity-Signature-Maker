<html>
    <head>
        <title>Seleziona un font</title>
        <script language="JavaScript">
            function selezionaTesto()
            {
                document.getElementsByName("testo_font")[0].focus();
                document.getElementsByName("testo_font")[0].select();
            }

            function copiaTesto(input)
            {
                document.getElementsByName("testo_font")[0].value = input;
                selezionaTesto();

                var field_edit = "<?php print $_GET["field_edit"]; ?>";
                if(field_edit != '')
                    eval("opener.document.getElementsByName('" + field_edit + "')[0].value = '" + input + "';");
            }
        </script>
    </head>
    <body>
        <center>
            <h3>Seleziona un font</h3>
            <table cellSpacing="0" cellPadding="4" border="1" style="border-collapse: collapse" borderColor="111111">
                <?php
                    include("/../config.php");
                    $count = 0;
                    foreach($fonts as $i => $value)
                    {
                        if($count++) print "                ";
                        print "<tr>\r\n";
                        print "                    <td>\r\n";
                        print "                        <center><a href=\"#ancora_testo\" onClick=\"copiaTesto('$i');\"><img width=250 src=\"stampa_carattere.php?id_font=$i\" onContextMenu=\"return false;\"></a></center>\r\n";
                        print "                    </td>\r\n";
                        print "                </tr>\r\n";
                    }
                ?>
                <tr>
                    <td>
                        <center>
                            <a name="ancora_testo">
                            <input type="text" name="testo_font" size="15" style="text-align: center" onClick="selezionaTesto();"><br>
                        </center>
                    </td>
                </tr>
            </table>
        </center>
    </body>
</html>