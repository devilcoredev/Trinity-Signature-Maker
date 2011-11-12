<html>
    <head>
        <title>Select an image</title>
        <script language="JavaScript">
            function selectText()
            {
                document.getElementsByName("text_image")[0].focus();
                document.getElementsByName("text_image")[0].select();
            }

            function select(input)
            {
                document.getElementsByName("text_image")[0].value = input;
                selectText();

                var field_edit = "<?php print $_GET["field_edit"]; ?>";
                if(field_edit != '')
                    eval("opener.document.getElementsByName('" + field_edit + "')[0].value = '" + input + "';");
            }
        </script>
    </head>
    <body>
        <center>
            <h3>Select an image</h3>
            <table cellSpacing="0" cellPadding="4" border="1" style="border-collapse: collapse" borderColor="111111">
                <?php
                    include("/../config.php");
                    foreach($backgrounds as $i => $value)
                    {
                        if($i) print "                "; //Indexes are number, so you don't need to use another counter.
                        print "<tr>\r\n";
                        print "                    <td>\r\n";
                        print "                        <center><a href=\"#text_link\" onClick=\"select('$value');\"><img width=\"350\" src=\"bg/$value.png\" onContextMenu=\"return false;\"></a></center>\r\n";
                        print "                    </td>\r\n";
                        print "                </tr>\r\n";
                    }
                ?>
                <tr>
                    <td>
                        <center>
                            <a name="text_link">
                            <input type="text" name="text_image" size="15" style="text-align: center" onClick="selectText();"><br>
                        </center>
                    </td>
                </tr>
            </table>
        </center>
    </body>
</html>