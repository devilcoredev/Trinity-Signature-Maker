<?php
    include("config.php");

    //Function that prints a drop-down menu containing the supported stats.
    function printMenu($input)
    {
        print "<option value=\"-\" selected>---</option>\n";
        foreach($input as $i => $value)
        {
            print "                                    <option value=\"" . $value["field_name"] . "\">" . $value["name"] . "</option>\n";
        }
    }
?>
<html>
    <head>
        <title>Create yout signature!</title>
        <script language="JavaScript">
            //Function that opens a popup window size XxY and location URL.
            function popUp(URL, X, Y)
            {
                day = new Date();
                id = day.getTime();
                eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0, scrollbars=1, location=0, statusbar=0, menubar=0, resizable=1, width=" + X + ", height=" + Y + "');");
            }

            //Function that transforms text with the first letter capitalized and the rest lowercase, eg: test=>Text TEXT=>Text tExT=>Test.
            function maximizeText(input)
            {
                if(input != '')
                {
                    var string = input.toLowerCase();
                    return string.charAt(0).toUpperCase() + string.slice(1);
                }else return '';
            }

            //Function that switches signature<->load, if set to true changes from loading to signature, else changes from signature to loading.
            function switchImage(mode)
            {
                document.getElementById("links").style.display        = (mode ? "block" : "none");
                document.getElementById("signature").style.display        = (mode ? "block" : "none");
                document.getElementById("loading").style.display  = (mode ? "none" : "block");
            }

            //Function called when the signatures are not loaded properly.
            function showError(input)
            {
                //I reset inputs.
                document.getElementsByName("direct_link")[0].value         = '';
                document.getElementsByName("html_link")[0].value           = '';
                document.getElementsByName("html_armory_link")[0].value    = '';
                document.getElementsByName("bbcode_link")[0].value         = '';
                document.getElementsByName("bbcode_armory_link")[0].value  = '';

                //I replace the image with an error.
                input.src = "images/<?php print $charging_error; ?>";

                //Display the image and hide the loading.
                input.style.display = 'block';
                document.getElementById("loading").style.display = "none";
            }

            //Function that loads the queryString of the signature.
            function showMessage()
            {
                var pLocation = "gc_image.php";
                var count = 0;

                //Add new fields to the queryString if they are defined.

                var server = document.getElementsByName("server")[0].value;
                if(server!='' && server!="undefined")
                {
                    count++;
                    pLocation += "?server=" + server;
                }

                var pg_name = document.getElementsByName("pg_name")[0].value;
                if(pg_name!='' && pg_name!="undefined")
                {
                    pLocation += (count++ ? '&' : '?');
                    pLocation += "pg_name=" + pg_name;
                }

                var background = document.getElementsByName("background")[0].value;
                if(background!='' && background!="undefined")
                {
                    pLocation += (count++ ? '&' : '?');
                    pLocation += "background=" + background;
                }

                var url_image = document.getElementsByName("url_image")[0].value;
                if(url_image!='' && url_image!="undefined")
                {
                    pLocation += (count++ ? '&' : '?');
                    pLocation += "url_image=" + url_image;
                }

                var type_image = document.getElementsByName("type_image");
                for(var i=0; i<type_image.length; ++i)
                    if(type_image[i].checked==1)
                    {
                        pLocation += (count++ ? '&' : '?');
                        pLocation += "type_image=" + type_image[i].value;
                    }

                var text_color = document.getElementsByName("text_color")[0].value;
                if(text_color!='' && text_color!="undefined")
                {
                    pLocation += (count++ ? '&' : '?');
                    pLocation += "text_color=" + text_color;
                }

                var text_font = document.getElementsByName("text_font")[0].value;
                if(text_font!='' && text_font!="undefined")
                {
                    pLocation += (count++ ? '&' : '?');
                    pLocation += "text_font=" + text_font;
                }

                //This part is only enabled by config.
                <?php
                    if($image_resize_enabled)
                    {
                        print "var x = document.getElementsByName('x')[0].value;\n";
                        print "                if(x!='' && x!=\"undefined\")\n";
                        print "                {\n";
                        print "                    pLocation += (count++ ? '&' : '?');\n";
                        print "                    pLocation += \"x=\" + x;\n";
                        print "                }\n\n";

                        print "                var y = document.getElementsByName('y')[0].value;\n";
                        print "                if(y!='' && y!=\"undefined\")\n";
                        print "                {\n";
                        print "                    pLocation += (count++ ? '&' : '?');\n";
                        print "                    pLocation += \"y=\" + y;\n";
                        print "                }\n";
                    }
                ?>

                for(var i=1; i<6; ++i)
                {
                    var stat = document.getElementsByName("stat"+i)[0].value;
                    if(stat!='' && stat!="undefined" && stat!='-')
                    {
                        pLocation += (count++ ? '&' : '?');
                        pLocation += "stat" + i + '=' + stat;
                    }
                }

                //I find the location of the link excluding the index.php and entering gc_image + queryString.
                var location_path = location.href;
                var temp_path = location_path;
                var pos = temp_path.indexOf("index.php");
                if(pos != -1)
                    location_path = temp_path.slice(0, pos);
                if(location_path[location_path.length - 1] != '/')
                    location_path += '/';

                //Change only if you have inserted the name of the pg.
                if(pg_name!='' && pg_name!="undefined")
                {
                    var absolute_link = location_path + pLocation;

                    var direct_link         = document.getElementsByName("direct_link")[0];
                    var html_link           = document.getElementsByName("html_link")[0];
                    var html_armory_link    = document.getElementsByName("html_armory_link")[0];
                    var bbcode_link         = document.getElementsByName("bbcode_link")[0];
                    var bbcode_armory_link  = document.getElementsByName("bbcode_armory_link")[0];

                    //I find the size of the error file (PNG will be much larger than the size of signatures).
                    var req = new XMLHttpRequest();
                    req.open("GET", absolute_link, false);
                    req.send(null);
                    var headers = req.getResponseHeader("Content-Length");

                    //If the size of the image matches that error then I empty fields, otherwise the normal view.
                    if(headers != <?php print $dim_incorrect_data; ?>)
                    {
                        var armory_template_link = "<?php print $armory_template_link; ?>";
                        var armory_link = armory_template_link.replace("%s", server.toUpperCase()).replace("%p", maximizeText(pg_name));

                        //I put links in the text boxes.
                        direct_link.value         = absolute_link;
                        html_link.value           = "<img src=\"" + absolute_link + "\">";
                        html_armory_link.value    = "<a href=\"" + armory_link + "\"><img src=\"" + absolute_link + "\"></a>";
                        bbcode_link.value         = "[img]" + absolute_link + "[/img]";
                        bbcode_armory_link.value  = "[url=" + armory_link + "][img]" + absolute_link + "[/img][/url]";
                    }
                    else
                    {
                        direct_link.value         = '';
                        html_link.value           = '';
                        html_armory_link.value    = '';
                        bbcode_link.value         = '';
                        bbcode_armory_link.value  = '';
                    }

                    document.getElementById("signature").src = absolute_link; //I change the path of the image.
                    document.getElementById("output").style.display = "block"; //Display the output.

                    //I do the switch images only if the signature is not already loaded.
                    if(!document.getElementById("signature").complete)
                        switchImage(false);
                }else alert("<?php print $error_pg; ?>");

                return true;
            }

            function selectText(testo)
            {
                testo.focus();
                testo.select();
            }
        </script>
    </head>
    <body>
        <center>
            <form>
                <table width="<?php print $x; ?>" cellSpacing="0" border="1">
                    <tr>
                        <td width="50%">Select the server:</td>
                        <td width="50%">
                            <center>
                                <select name="server">
                                    <?php
                                        $count = 0;
                                        foreach($realm_name as $i => $value)
                                        {
                                            if($count++) print "                                    ";
                                            print "<option value=\"$i\">$value</option>\n";
                                        }
                                    ?>
                                </input>
                            </center>
                        </td>
                    </tr>
                    <tr>
                        <td>Insert the name of the character:</td>
                        <td><center><input type="text" name="pg_name"></center></td>
                    </tr>
                    <tr>
                        <td>Select a background: (select a <a href="javascript:popUp('colors/table.htm', 350, 540)">color</a> or an <a href="javascript:popUp('images/', 400, 830)">image</a> and copy the code in the text box).</td>
                        <td align="middle"><center><input type="text" name="background"></center></td>
                    </tr>
                    <tr>
                        <td>Insert a link to an avatar:<br>(<u><font color="ff0000"><b>ATTENCTION</b></font>: The image must be in <i>png</i> format, also an external link could delay the loading of the signature</u>), leave blank to use the default class image.</td>
                        <td align="middle"><center><input type="text" name="url_image"></center></td>
                    </tr>
                    <tr>
                        <td>Select "yes" to enter the race and the class in the avatar, "no" to insert only the class (Cataclysm Avatar) in the avatar:</td>
                        <td align="middle"><center>Yes <input type="radio" name="type_image" value="race_class" checked="checked">&nbsp&nbsp&nbsp&nbspNo <input type="radio" name="type_image" value="class"></center></td>
                    </tr>
                    <tr>
                        <td>Select the text <a href="javascript:popUp('colors/table.htm', 350, 540)">color</a>:</td>
                        <td><center><input type="text" name="text_color"></center></td>
                    </tr>
                    <tr>
                        <td>Select the text <a href="javascript:popUp('fonts/', 300, 420)">font</a>:</td>
                        <td><center><input type="text" name="text_font"></center></td>
                    </tr>
                    <?php
                        if($image_resize_enabled)
                        {
                            print "<tr>\n";
                            print "                        <td>Insert the image size:</td>\n";
                            print "                        <td>\n";
                            print "                            <center>\n";
                            print "                                X size: <input type=\"text\" name=\"x\" size=3><br>\n";
                            print "                                Y size: <input type=\"text\" name=\"y\" size=3>\n";
                            print "                            </center>\n";
                            print "                        </td>\n";
                            print "                    </tr>\n";
                        }
                    ?>
                    <tr>
                        <td>Select the stat for the first field:</td>
                        <td>
                            <center>
                                <select name="stat1">
                                    <?php printMenu($stats); ?>
                                </select>
                            </center>
                        </td>
                    </tr>
                    <tr>
                        <td>Select the stat for the second field:</td>
                        <td>
                            <center>
                                <select name="stat2">
                                    <?php printMenu($stats); ?>
                                </select>
                            </center>
                        </td>
                    </tr>
                    <tr>
                        <td>Select the stat for the third field:</td>
                        <td>
                            <center>
                                <select name="stat3">
                                    <?php printMenu($stats); ?>
                                </select>
                            </center>
                        </td>
                    </tr>
                    <tr>
                        <td>Select the stat for the fourth field:</td>
                        <td>
                            <center>
                                <select name="stat4">
                                    <?php printMenu($stats); ?>
                                </select>
                            </center>
                        </td>
                    </tr>
                    <tr>
                        <td>Select the stat for the fifth field:</td>
                        <td>
                            <center>
                                <select name="stat5">
                                    <?php printMenu($stats); ?>
                                </select>
                            </center>
                        </td>
                    </tr>
                    <tr>
                        <td colspan=2><center><input type="button" value="Create signature!" onClick="return showMessage();"></center></td>
                    </tr>
                </table>
            </form>
            <div id="output" style="display: none">
                <br>
                <div id="loading">
                    Signature processing in progress...<br>
                    <img src="images/loading.gif"><br>
                </div>
                <img id="signature" src="" style="display: none" onLoad="switchImage(true);" onError="showError(this);" onAbort="showError(this);" onContextMenu="return false;"><br>
                <table id="links" border="0">
                    <tr>
                        <td>Direct link to the image:</td>
                        <td><input type="text" name="direct_link" size="40" readOnly="readonly" onClick="selectText(this);"></td>
                    </tr>
                    <tr>
                        <td>Link to the image with HTML tag:</td>
                        <td><input type="text" name="html_link" size="40" readOnly="readonly" onClick="selectText(this);"></td>
                    </tr>
                    <tr>
                        <td>Link to the image with HTML tag and link to the Armory:</td>
                        <td><input type="text" name="html_armory_link" size="40" readOnly="readonly" onClick="selectText(this);"></td>
                    </tr>
                    <tr>
                        <td>Link to images with BBCode tag (for the <?php print $server_name; ?> forum):</td>
                        <td><input type="text" name="bbcode_link" size="40" readOnly="readonly" onClick="selectText(this);"></td>
                    </tr>
                    <tr>
                        <td>Link to the image with BBCode tag and link to the Armory:</td>
                        <td><input type="text" name="bbcode_armory_link" size="40" readonly="readonly" onclick="selectText(this);"></td>
                    </tr>
                </table>
            </div>
        </center>
    </body>
</html>