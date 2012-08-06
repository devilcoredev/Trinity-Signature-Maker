<html>
    <head>
        <title>Select an image</title>
        <script language="JavaScript" type="text/javascript" src="../jquery-1.7.1.min.js"></script>
        <script language="JavaScript">
            function select(input)
            {
                $("#text_image").attr("src", $('#' + input).attr("src"));

                var field_edit = "<?php print $_GET["field_edit"]; ?>";
                if(field_edit != '')
                {
                    opener.$('#' + field_edit).val(input);
                    opener.colorField(opener.$('#' + field_edit));
                }
            }
        </script>
    </head>
    <body>
        <center>
            <h3>Select an image</h3>
            <table cellSpacing="0" cellPadding="4" border="1" style="border-collapse: collapse" borderColor="111111">
                <?php
                    define("TOOL_INCLUDED", true);
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