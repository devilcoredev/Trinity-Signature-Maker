<?php
    include("config.php");

    //Function that prints a drop-down menu containing the supported stats.
    function printMenu()
    {
        global $stats;
        print "                                    <option value=\"-\" selected>---</option>\r\n";
        foreach($stats as $i => $value)
        {
            print "                                    <option value=\"$i\">" . $value["name"] . "</option>\r\n";
        }
    }
?>
<html>
    <head>
        <title>Create your signature!</title>
        <script language="JavaScript" type="text/javascript" src="jquery-1.7.1.min.js"></script>
        <script language="JavaScript">
            //Function that opens a popup window size XxY and location URL.
            function popUp(URL, X, Y)
            {
                day = new Date();
                id = day.getTime();
                eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0, scrollbars=1, location=0, statusbar=0, menubar=0, resizable=0, width=" + X + ", height=" + Y + ", top=0, left=0');");
                eval("page" + id + ".creator = self;");
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
                $("#links").css("display", (mode ? "block" : "none"));
                $("#signature").css("display", (mode ? "block" : "none"));
                $("#loading").css("display", (mode ? "none" : "block"));
            }

            //Function called when the signatures are not loaded properly.
            function showError(input)
            {
                //I reset inputs.
                $("input[name=direct_link]").eq(0).val('');
                $("input[name=html_link]").eq(0).val('');
                $("input[name=html_armory_link]").eq(0).val('');
                $("input[name=bbcode_link]").eq(0).val('');
                $("input[name=bbcode_armory_link]").eq(0).val('');

                //I replace the image with an error.
                input.src = "images/<?php print $charging_error; ?>";

                //Display the image and hide the loading.
                input.style.display = "block";
                $("#loading").css("display", "none");
            }

            //Function that loads the queryString of the signature.
            function showMessage()
            {
                var pLocation = "gc_image.php";

                //Add new fields to the queryString if they are defined.

                //Change only if you have inserted the name of the pg.
                var pg_name = $("input[name=pg_name]").eq(0).val();
                if(pg_name == '')
                {
                    alert("<?php print $error_pg; ?>");
                    return false;
                }

                var server = $("select[name=server]").eq(0).val();
                if(server != '')
                    pLocation += "?server=" + server;

                pLocation += "&pg_name=" + pg_name;

                var background = $("input[name=background]").eq(0).val();
                if(background != '')
                    pLocation += "&background=" + background;

                var end_background = $("input[name=end_background]").eq(0).val();
                if(end_background != '' && background.indexOf("bg_") != 0)
                    pLocation += "&end_background=" + end_background;

                var background_method = $("select[name=background_method]").eq(0).val();
                if(background_method != '' && background.indexOf("bg_") != 0)
                    pLocation += "&background_method=" + background_method;

                var effects = $("input[name=effects]:checked").val();
                if(effects != '')
                    pLocation += "&effects=" + effects;

                var filter = $("select[name=filter]").eq(0).val();
                if(filter != '')
                    pLocation += "&filter=" + filter;

                var url_image = encodeURIComponent($("input[name=url_image]").eq(0).val());
                if(url_image != '')
                    pLocation += "&url_image=" + url_image;

                var type_image = $("input[name=type_image]:checked").val();
                if(type_image != '')
                    pLocation += "&type_image=" + type_image;

                var text_color = $("input[name=text_color]").eq(0).val();
                if(text_color != '')
                    pLocation += "&text_color=" + text_color;

                var text_font = $("input[name=text_font]").eq(0).val();
                if(text_font != '')
                    pLocation += "&text_font=" + text_font;

                <?php
                    //This part is only enabled by config.
                    if($image_resize_enabled)
                    {
                        print "var x = $(\"input[name=x]\").eq(0).val();\r\n";
                        print "                if(x != '')\r\n";
                        print "                    pLocation += \"&x=\" + x;\r\n";

                        print "                var y = $(\"input[name=y]\").eq(0).val();\r\n";
                        print "                if(y != '')\r\n";
                        print "                    pLocation += \"&y=\" + y;\r\n";
                    }
                ?>

                for(var i=1; i<6; ++i)
                    if($("input[name=enable_custom_stat" + i + "]").eq(0).attr("checked"))
                    {
                        var custom_stat = encodeURIComponent($("input[name=custom_stat" + i + "]").eq(0).val());
                        if(custom_stat != '')
                            pLocation += "&custom_stat" + i + '=' + custom_stat;
                    }
                    else
                    {
                        var stat = $("select[name=stat" + i + "]").eq(0).val();
                        if(stat != '' && stat != '-')
                            pLocation += "&stat" + i + '=' + stat;
                    }

                //I find the location of the link excluding the index.php and entering gc_image + queryString.
                var location_path = location.href;
                var temp_path = location_path;
                var pos = temp_path.indexOf("index.php");
                if(pos != -1)
                    location_path = temp_path.slice(0, pos);
                if(location_path[location_path.length - 1] != '/')
                    location_path += '/';

                var absolute_link = location_path + pLocation;

                var direct_link         = $("input[name=direct_link]").eq(0);
                var html_link           = $("input[name=html_link]").eq(0);
                var html_armory_link    = $("input[name=html_armory_link]").eq(0);
                var bbcode_link         = $("input[name=bbcode_link]").eq(0);
                var bbcode_armory_link  = $("input[name=bbcode_armory_link]").eq(0);

                //I find the size of the error file (PNG will be much larger than the size of signatures).
                var req = new XMLHttpRequest();
                req.open("GET", absolute_link, false);
                req.send(null);
                var headers = req.getResponseHeader("Content-Length");

                //If the size of the image matches that error then I empty fields, otherwise the normal view.
                if(headers != <?php print $dim_incorrect_data; ?>)
                {
                    var armory_template_link = "<?php print $armory_template_link; ?>";

                    var server_keys          = new Array();
                    var server_armory_names  = new Array();

                    <?php
                        $index = 0;
                        foreach($armory_name as $i => $value)
                        {
                            if($value != '')
                            {
                                if($index) print "                        ";
                                print "server_keys[$index]          = \"$i\";\r\n";
                                print "                        ";
                                print "server_armory_names[$index]  = \"$value\";\r\n";
                                $index++;
                            }
                        }
                    ?>

                    var armory_server_name = '';

                    for(var i=0; i<server_keys.length; ++i)
                        if(server_keys[i] == server)
                        {
                            armory_server_name = server_armory_names[i];
                            break;
                        }

                    var armory_link = armory_template_link.replace("%s", armory_server_name).replace("%p", maximizeText(pg_name));

                    //I put links in the text boxes.
                    direct_link.val(absolute_link);
                    html_link.val("<img src=\"" + absolute_link + "\">");
                    html_armory_link.val("<a href=\"" + armory_link + "\"><img src=\"" + absolute_link + "\"></a>");
                    bbcode_link.val("[img]" + absolute_link + "[/img]");
                    bbcode_armory_link.val("[url=" + armory_link + "][img]" + absolute_link + "[/img][/url]");
                }
                else
                {
                    direct_link.val('');
                    html_link.val('');
                    html_armory_link.val('');
                    bbcode_link.val('');
                    bbcode_armory_link.val('');
                }

                $("#signature").attr("src", absolute_link); //I change the path of the image.
                $("#output").css("display", "block"); //Display the output.

                //I do the switch images only if the signature is not already loaded.
                if(!$("#signature").get().complete)
                    switchImage(false);

                return true;
            }

            function selectText(testo)
            {
                testo.focus();
                testo.select();
            }

            function switchStat(index)
            {
                var isChecked = $("input[name=enable_custom_stat" + index + "]").eq(0).attr("checked");

                $("#display_stat" + index).css("display", (isChecked ? "none" : "block"));
                $("#display_custom_stat" + index).css("display", (isChecked ? "block" : "none"));
            }

            function initializeText()
            {
                for(var i=1; i<6; ++i)
                    switchStat(i);
            }
        </script>
    </head>
    <body onLoad="initializeText()">
        <center>
            <table width="<?php print $x; ?>" cellSpacing="0" border="1">
                <tr>
                    <td width="50%">Select the server:</td>
                    <td width="50%" align="middle">
                        <center>
                            <select name="server">
                                <?php
                                    $count = 0;
                                    foreach($realm_name as $i => $value)
                                    {
                                        if($i!='' && $value!='')
                                        {
                                            if($count++) print "                                ";
                                            print "<option value=\"$i\">$value</option>\r\n";
                                        }
                                    }
                                    if(!$count) print "<option value=\"-\">---</option>\r\n";
                                ?>
                            </select>
                        </center>
                    </td>
                </tr>
                <tr>
                    <td>Insert the name of the character:</td>
                    <td align="middle"><center><input type="text" name="pg_name" /></center></td>
                </tr>
                <tr>
                    <td>Select a background: (select a <a href="javascript:popUp('colors/index.php?field_edit=background', 350, 500)">color</a> or an <a href="javascript:popUp('images/index.php?field_edit=background', 400, 830)">image</a>).</td>
                    <td align="middle"><center><input type="text" name="background" /></center></td>
                </tr>
                <tr>
                    <td>Select a end background <a href="javascript:popUp('colors/index.php?field_edit=end_background', 350, 500)">color</a> (optional):</td>
                    <td align="middle"><center><input type="text" name="end_background" /></center></td>
                </tr>
                <tr>
                    <td>Select a background color shade method (optional):</td>
                    <td align="middle">
                        <select name="background_method">
                            <option value="horizontal">Horizontal</option>
                            <option value="vertical">Vertical</option>
                            <option value="radial">Radial</option>
                            <option value="circle">Circle</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Select a background effect:</td>
                    <td align="middle">
                        <center>
                            <table border="0">
                                <tr>
                                    <td>None:</td>
                                    <td><input type="radio" name="effects" value="none" checked="checked" /></td>
                                </tr>
                                <?php
                                    foreach($effects as $i => $value)
                                    {
                                        if($i) print "                                ";
                                        print "<tr>\r\n                                    ";
                                        print "<td><img src=\"images/effects/$value.png\" onContextMenu=\"return false;\" /></td>";
                                        print "\r\n                                    ";
                                        print "<td><input type=\"radio\" name=\"effects\" value=\"$value\" /></td>\r\n";
                                        print "                                </tr>\r\n";
                                    }
                                ?>
                            </table>
                        </center>
                    </td>
                </tr>
                <tr>
                    <td>Select a background filter:</td>
                    <td align="middle">
                        <select name="filter">
                            <option value="none">None</option>
                            <option value="grayscale">Grayscale</option>
                            <option value="sepia">Sepia</option>
                            <option value="negate">Negate</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Insert a link to an avatar:<br />(<u><font color="ff0000"><b>ATTENCTION</b></font>: an external link could delay the loading of the signature</u>), leave blank to use the default class image.</td>
                    <td align="middle"><center><input type="text" name="url_image" /></center></td>
                </tr>
                <tr>
                    <td>Select "yes" to enter the race and the class in the avatar, "no" to insert only the class (Cataclysm Avatar) in the avatar:</td>
                    <td align="middle"><center>Yes <input type="radio" name="type_image" value="race_class" checked="checked" />&nbsp&nbsp&nbsp&nbsp No <input type="radio" name="type_image" value="class" /></center></td>
                </tr>
                <tr>
                    <td>Select the text <a href="javascript:popUp('colors/index.php?field_edit=text_color', 350, 500)">color</a>:</td>
                    <td align="middle"><center><input type="text" name="text_color" /></center></td>
                </tr>
                <tr>
                    <td>Select the text <a href="javascript:popUp('fonts/index.php?field_edit=text_font', 300, 420)">font</a>:</td>
                    <td align="middle"><center><input type="text" name="text_font" /></center></td>
                </tr>
                <?php
                    if($image_resize_enabled)
                    {
                        print "<tr>\r\n";
                        print "                    <td>Insert the image size:</td>\r\n";
                        print "                    <td align=\"middle\">\r\n";
                        print "                        <center>\r\n";
                        print "                            X size: <input type=\"text\" name=\"x\" size=\"3\" /><br />\r\n";
                        print "                            Y size: <input type=\"text\" name=\"y\" size=\"3\" />\r\n";
                        print "                        </center>\r\n";
                        print "                    </td>\r\n";
                        print "                </tr>\r\n";
                    }

                    $ordinary_numbers = array("first", "second", "third", "fourth", "fifth");
                    for($i=0; $i<5; ++$i)
                    {
                        if($i || $image_resize_enabled)
                            print "                ";
                        print "<tr>\r\n";
                        print "                    <td>\r\n";
                        print "                        Select the stat for the " . $ordinary_numbers[$i] . " field<br />\r\n";
                        print "                        (custom text: <input type=\"checkbox\" name=\"enable_custom_stat" . ($i+1) . "\" onClick=\"switchStat(" . ($i+1) . ");\"/>):\r\n";
                        print "                    </td>\r\n";
                        print "                    <td align=\"middle\">\r\n";
                        print "                        <center>\r\n";
                        print "                            <div id=\"display_stat" . ($i+1) . "\" style=\"display: block;\">\r\n";
                        print "                                <select name=\"stat" . ($i+1) . "\">\r\n";
                        printMenu();
                        print "                                </select>\r\n";
                        print "                            </div><br />\r\n";
                        print "                            <div id=\"display_custom_stat" . ($i+1) . "\" style=\"display: none;\"><input type=\"text\" name=\"custom_stat" . ($i+1) . "\" maxlength=\"20\" /></div>\r\n";
                        print "                        </center>\r\n";
                        print "                    </td>\r\n";
                        print "                </tr>\r\n";
                    }
                ?>
                <tr><td colspan="2"><center><input type="button" value="Create signature!" onClick="return showMessage();" /></center></td></tr>
            </table>
            <div id="output" style="display: none">
                <br />
                <div id="loading">
                    Signature processing in progress...<br />
                    <img src="images/loading.gif" /><br />
                </div>
                <img id="signature" src="" style="display: none" onLoad="switchImage(true);" onError="showError(this);" onAbort="showError(this);" onContextMenu="return false;" /><br />
                <table align="center" id="links" width="680" border="0" style="display: none">
                    <tr>
                        <td>Direct link to the image:</td>
                        <td><input type="text" name="direct_link" size="40" readOnly="readonly" onClick="selectText(this);" /></td>
                    </tr>
                    <tr>
                        <td>Link to the image with HTML tag:</td>
                        <td><input type="text" name="html_link" size="40" readOnly="readonly" onClick="selectText(this);" /></td>
                    </tr>
                    <tr>
                        <td>Link to the image with HTML tag and link to the Armory:</td>
                        <td><input type="text" name="html_armory_link" size="40" readOnly="readonly" onClick="selectText(this);" /></td>
                    </tr>
                    <tr>
                        <td>Link to images with BBCode tag (for the <?php print $server_name; ?> forum):</td>
                        <td><input type="text" name="bbcode_link" size="40" readOnly="readonly" onClick="selectText(this);" /></td>
                    </tr>
                    <tr>
                        <td>Link to the image with BBCode tag and link to the Armory:</td>
                        <td><input type="text" name="bbcode_armory_link" size="40" readonly="readonly" onClick="selectText(this);" /></td>
                    </tr>
                </table>
            </div>
        </center>
    </body>
</html>