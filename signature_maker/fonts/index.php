<html>
    <head>
        <title>Seleziona un font</title>
        <script language="JavaScript">
            function selezionaTesto()
            {
                document.getElementsByName("testo_font")[0].focus();
                document.getElementsByName("testo_font")[0].select();
            }

            function selecttesto(input)
            {
                document.getElementsByName("testo_font")[0].value = input;
                open_field = input;
                selezionaTesto();
            }
        </script>
    </head>
    <body>
        <center>
            <h3>Seleziona un font</h3>
            <table cellSpacing="0" cellPadding="4" border="1" style="border-collapse: collapse" bordercolor="111111">
                <?php
                    include("/../config.php");
                    $count = 0;
                    foreach($fonts as $i => $value)
                    {
                        if($count++) print "                ";
                        print "<tr>\n";
                        print "                    <td>\n";
                        print "                        <center><a href=\"#ancora_testo\" onClick=\"selecttesto('$i');\"><img width=250 src=\"stampa_carattere.php?id_font=$i\"></a></center>\n";
                        print "                    </td>\n";
                        print "                </tr>\n";
                    }
                ?>
                <tr>
                    <td>
                        <center>
                            <a name="ancora_testo">
                            <input type="text" name="testo_font" size="15" style="text-align: center" onClick="selezionaTesto();"><br>(usa CTRL+C per copiare il font)
                        </center>
                    </td>
                </tr>
            </table>
        </center>
    </body>
</html>