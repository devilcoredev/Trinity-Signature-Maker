<html>
    <head>
        <title>Select a font</title>
        <script language="JavaScript">
            function selectText()
            {
                document.getElementsByName("font_text")[0].focus();
                document.getElementsByName("font_text")[0].select();
            }

            function copyText(input)
            {
                document.getElementsByName("font_text")[0].value = input;
                selectText();

                var field_edit = "<?php print $_GET["field_edit"]; ?>";
                if(field_edit != '')
                    eval("opener.document.getElementsByName('" + field_edit + "')[0].value = '" + input + "';");
            }
        </script>
    </head>
    <body>
        <center>
            <h3>Select a font</h3>
            <table cellSpacing="0" cellPadding="4" border="1" style="border-collapse: collapse" borderColor="111111">
                <?php
                    include("/../config.php");
                    $count = 0;
                    foreach($fonts as $i => $value)
                    {
                        if($count++) print "                ";
                        print "<tr>\r\n";
                        print "                    <td>\r\n";
                        print "                        <center><a href=\"#text_link\" onClick=\"copyText('$i');\"><img width=250 src=\"print_font.php?id_font=$i\" onContextMenu=\"return false;\"></a></center>\r\n";
                        print "                    </td>\r\n";
                        print "                </tr>\r\n";
                    }
                ?>
                <tr>
                    <td>
                        <center>
                            <a name="text_link">
                            <input type="text" name="font_text" size="15" style="text-align: center" onClick="selectText();"><br>
                        </center>
                    </td>
                </tr>
            </table>
        </center>
    </body>
</html>