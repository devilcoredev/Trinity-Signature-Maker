<html>
    <head>
        <title>Seleziona un'immagine</title>
        <script language="JavaScript">
            function selezionaTesto()
            {
                document.getElementsByName("testo_immagine")[0].focus();
                document.getElementsByName("testo_immagine")[0].select();
            }

            function select(input)
            {
                document.getElementsByName("testo_immagine")[0].value = input;
                selezionaTesto();
            }
        </script>
    </head>
    <body>
        <center>
            <h3>Seleziona un'immagine</h3>
            <table cellSpacing="0" cellPadding="4" border="1" style="border-collapse: collapse" bordercolor="111111">
                <?php
                    include("/../config.php");
                    foreach($backgrounds as $i => $value)
                    {
                        if($i) print "                "; //Gli indici sono numeri, quindi non è necessario usare un altro contatore.
                        print "<tr>\n";
                        print "                    <td>\n";
                        print "                        <center><a href=\"#ancora_testo\" onClick=\"select('$value');\"><img width=\"350\" src=\"bg/$value.png\"></a></center>\n";
                        print "                    </td>\n";
                        print "                </tr>\n";
                    }
                ?>
                <tr>
                    <td>
                        <center>
                            <a name="ancora_testo">
                            <input type="text" name="testo_immagine" size="15" style="text-align: center" onClick="selezionaTesto();"><br>(usa CTRL+C per copiare l'immagine)
                        </center>
                    </td>
                </tr>
            </table>
        </center>
    </body>
</html>