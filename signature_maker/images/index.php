<html>
    <head>
        <title>Select an image</title>
        <script language="JavaScript">
            function select(input)
            {
                document.getElementById("text_image").src = document.getElementById(input).src;

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
                        print "                        <center><a href=\"#text_link\" onClick=\"select('$value');\"><img width=\"350\" id=\"$value\" src=\"bg/$value.png\" onContextMenu=\"return false;\" /></a></center>\r\n";
                        print "                    </td>\r\n";
                        print "                </tr>\r\n";
                    }
                ?>
                <tr>
                    <td>
                        <center>
                            <a name="text_link" />
                            Selected image:<br />
                            <img id="text_image" src="blank_64.png" width="350" height="50" onContextMenu="return false;" />
                        </center>
                    </td>
                </tr>
            </table>
        </center>
    </body>
</html>