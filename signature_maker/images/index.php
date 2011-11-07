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
            }
        </script>
    </head>
    <body>
        <center>
            <h3>Select an image</h3>
            <table cellSpacing="0" cellPadding="4" border="1" style="border-collapse: collapse" bordercolor="111111">
                <?php
                    include("/../config.php");
                    $count = 0;
                    foreach($backgrounds as $i => $value)
                    {
                        if($count++) print "                ";
                        print "<tr>\n";
                        print "                    <td>\n";
                        print "                        <center><a href=\"#text_link\" onClick=\"select('$value');\"><img width=\"350\" src=\"bg/$value.png\"></a></center>\n";
                        print "                    </td>\n";
                        print "                </tr>\n";
                    }
                ?>
                <tr>
                    <td>
                        <center>
                            <a name="text_link">
                            <input type="text" name="text_image" size="15" style="text-align: center" onClick="selectText();"><br>(use CTRL+C to copy the image)
                        </center>
                    </td>
                </tr>
            </table>
        </center>
    </body>
</html>