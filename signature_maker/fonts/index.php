<html>
    <head>
        <title>Seleziona un font</title>
        <script language="JavaScript" type="text/javascript" src="../jquery-1.7.1.min.js"></script>
        <script language="JavaScript">
            function copiaTesto(input)
            {
                $("#testo_font").attr("src", $('#' + input).attr("src"));

                var field_edit = "<?php print $_GET["field_edit"]; ?>";
                if(field_edit != '')
                    opener.$("[name=" + field_edit + ']').eq(0).val(input); //eval("opener.document.getElementsByName('" + field_edit + "')[0].value = '" + input + "';");
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
                        print "                        <center><a href=\"#ancora_testo\" onClick=\"copiaTesto('$i');\"><img width=250 id=\"$i\" src=\"stampa_carattere.php?id_font=$i\" onContextMenu=\"return false;\" /></a></center>\r\n";
                        print "                    </td>\r\n";
                        print "                </tr>\r\n";
                    }
                ?>
                <tr>
                    <td>
                        <center>
                            <a name="ancora_testo" />
                            Font selezionato:<br />
                            <img id="testo_font" src="../images/blank_64.png" onContextMenu="return false;" />
                        </center>
                    </td>
                </tr>
            </table>
        </center>
    </body>
</html>