<?php
    define("TOOL_INCLUDED", true);
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
                eval("var page" + id + " = window.open(URL, '" + id + "', 'toolbar=0, scrollbars=1, location=0, statusbar=0, menubar=0, resizable=0, width=" + X + ", height=" + Y + ", top=0, left=0');");
                eval("page" + id + ".creator = self;");
            }

            function scroll()
            {
                $('html, body').animate({
                    scrollTop: $(document).height()
                }, 1000);
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

            function emptyFields()
            {
                $("#direct_link").val('');
                $("#html_link").val('');
                $("#html_armory_link").val('');
                $("#bbcode_link").val('');
                $("#bbcode_armory_link").val('');
            }

            //Function that switches signature<->load, if set to true changes from loading to signature, else changes from signature to loading.
            function switchImage(mode)
            {
                if(mode)
                {
                    $("#links").show();
                    $("#signature").show();
                    $("#loading").hide();

                    //If the dimension are equal with the incorrect data images empty the fields.
                    $("#signature").removeAttr("width");
                    $("#signature").removeAttr("height");
                    if($("#signature").width() == <?php print $w_incorrect_data; ?> && $("#signature").height() == <?php print $h_incorrect_data; ?>)
                        emptyFields();

                    scroll();
                }
                else
                {
                    $("#links").hide();
                    $("#signature").hide();
                    $("#loading").show();
                }
            }

            //Function called when the signatures are not loaded properly.
            function showError(input)
            {
                //I reset inputs.
                emptyFields();

                //I replace the image with an error.
                $(input).attr("src", "images/<?php print $charging_error; ?>");

                //Display the image and hide the loading.
                $(input).show();
                $("#loading").hide();
            }

            //Function that loads the queryString of the signature.
            function showMessage()
            {
                var pLocation = "gc_image.php";

                //Add new fields to the queryString if they are defined.

                //Change only if you have inserted the name of the pg.
                var pg_name = $("#pg_name").val();
                if(pg_name == '')
                {
                    alert("<?php print $error_pg; ?>");
                    return false;
                }

                var server = $("#server").val();
                if(server != '')
                    pLocation += "?server=" + server;

                pLocation += "&pg_name=" + pg_name;

                var background = $("#background").val();
                if(background != '')
                    pLocation += "&background=" + background;

                if(background.indexOf("bg_") != 0)
                {
                    var end_background = $("#end_background").val();
                    if(end_background != '')
                        pLocation += "&end_background=" + end_background;

                    var background_method = $("#background_method").val();
                    if(background_method != '')
                        pLocation += "&background_method=" + background_method;
                }

                var effects = $("input[name=effects]:checked").val();
                if(effects != '')
                    pLocation += "&effects=" + effects;

                var filter = $("#filter").val();
                if(filter != '')
                    pLocation += "&filter=" + filter;

                var url_image = encodeURIComponent($("#url_image").val());
                if(url_image != '')
                    pLocation += "&url_image=" + url_image;

                var type_image = $("input[name=type_image]:checked").val();
                if(type_image != '')
                    pLocation += "&type_image=" + type_image;

                var text_color = $("#text_color").val();
                if(text_color != '')
                    pLocation += "&text_color=" + text_color;

                var text_font = $("#text_font").val();
                if(text_font != '')
                    pLocation += "&text_font=" + text_font;

                <?php
                    //This part is only enabled by config.
                    if($image_resize_enabled)
                    {
                        print "var x = $(\"#x\").val();\r\n";
                        print "                if(x != '')\r\n";
                        print "                    pLocation += \"&x=\" + x;\r\n";

                        print "                var y = $(\"#y\").val();\r\n";
                        print "                if(y != '')\r\n";
                        print "                    pLocation += \"&y=\" + y;\r\n";
                    }
                ?>

                for(var i = 1; i < 6; ++i)
                    if($("#enable_custom_stat" + i).attr("checked"))
                    {
                        var custom_stat = encodeURIComponent($("#custom_stat" + i).val());
                        if(custom_stat != '')
                            pLocation += "&custom_stat" + i + '=' + custom_stat;
                    }
                    else
                    {
                        var stat = $("#stat" + i).val();
                        if(stat != '' && stat != '-')
                            pLocation += "&stat" + i + '=' + stat;
                    }

                //I find the location of the link excluding the index.php and entering gc_image + queryString.
                var location_path = location.href.replace('#', '');
                var temp_path = location_path;
                var pos = temp_path.indexOf("index.php");
                if(pos != -1)
                    location_path = temp_path.slice(0, pos);
                if(location_path[location_path.length - 1] != '/')
                    location_path += '/';

                var absolute_link = location_path + pLocation;

                var direct_link         = $("#direct_link");
                var html_link           = $("#html_link");
                var html_armory_link    = $("#html_armory_link");
                var bbcode_link         = $("#bbcode_link");
                var bbcode_armory_link  = $("#bbcode_armory_link");

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

                for(var i = 0; i < server_keys.length; ++i)
                    if(server_keys[i] == server)
                    {
                        armory_server_name = server_armory_names[i];
                        break;
                    }

                var armory_link = armory_template_link.replace("%s", armory_server_name).replace("%p", maximizeText(pg_name));

                if($("#signature").attr("src") != absolute_link)
                {
                    $("#output").show(); //Display the output.

                    switchImage(false); //Display "charging".

                    $("#signature").attr("src", absolute_link); //I change the path of the image.

                    //I put links in the text boxes.
                    direct_link.val(absolute_link);
                    html_link.val("<img src=\"" + absolute_link + "\">");
                    html_armory_link.val("<a href=\"" + armory_link + "\"><img src=\"" + absolute_link + "\"></a>");
                    bbcode_link.val("[img]" + absolute_link + "[/img]");
                    bbcode_armory_link.val("[url=" + armory_link + "][img]" + absolute_link + "[/img][/url]");

                    scroll();
                }

                return true;
            }

            function selectText(testo)
            {
                testo.focus();
                testo.select();
            }

            function switchStat(index)
            {
                if($("#enable_custom_stat" + index).attr("checked"))
                {
                    $("#display_stat" + index).hide();
                    $("#display_custom_stat" + index).show();
                }
                else
                {
                    $("#display_stat" + index).show();
                    $("#display_custom_stat" + index).hide();
                }
            }

            function isHexColor(sNum)
            {
                var strPattern = /(^#?[0-9A-F]{6}$)/i;
                return strPattern.test(sNum);
            }

            function intToHex(d)
            {
                var hex = Number(d).toString(16);
                hex = "00".substr(0, 2 - hex.length) + hex;
                return hex;
            }

            function colorField(field)
            {
                var colorValue = $(field).val().replace('#', '');

                if(isHexColor(colorValue))
                {
                    var r = intToHex(255 - parseInt(colorValue.substring(0, 2), 16));
                    var g = intToHex(255 - parseInt(colorValue.substring(2, 4), 16));
                    var b = intToHex(255 - parseInt(colorValue.substring(4, 6), 16));

                    $(field).css("color", r + g + b);
                    $(field).css("background-color", colorValue);
                }
                else
                {
                    $(field).css("color", "000000");
                    $(field).css("background-color", "FFFFFF");
                }
            }

            $(window).load(function() {
                for(var i=1; i<6; ++i)
                    switchStat(i);
            });
        </script>
    </head>
    <body>
        <center>
            <table width="<?php print $x; ?>" cellSpacing="0" border="1">
                <tr>
                    <td width="50%">Select the server:</td>
                    <td width="50%" align="middle">
                        <center>
                            <select id="server">
                                <?php
                                    $count = 0;
                                    foreach($realm_name as $i => $value)
                                    {
                                        if($i != '' && $value != '')
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
                    <td align="middle"><center><input type="text" id="pg_name" /></center></td>
                </tr>
                <tr>
                    <td>Select a background: (select a <a href="javascript:popUp('colors/index.php?field_edit=background', 350, 500)">color</a> or an <a href="javascript:popUp('images/index.php?field_edit=background', 400, 830)">image</a>).</td>
                    <td align="middle"><center><input type="text" id="background" onKeyUp="colorField(this);" /></center></td>
                </tr>
                <tr>
                    <td>Select a end background <a href="javascript:popUp('colors/index.php?field_edit=end_background', 350, 500)">color</a> (optional):</td>
                    <td align="middle"><center><input type="text" id="end_background" onKeyUp="colorField(this);" /></center></td>
                </tr>
                <tr>
                    <td>Select a background color shade method (optional):</td>
                    <td align="middle">
                        <select id="background_method">
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
                        <select id="filter">
                            <option value="none">None</option>
                            <option value="grayscale">Grayscale</option>
                            <option value="sepia">Sepia</option>
                            <option value="negate">Negate</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Insert a link to an avatar:<br />(<u><font color="ff0000"><b>ATTENCTION</b></font>: an external link could delay the loading of the signature</u>), leave blank to use the default class image.</td>
                    <td align="middle"><center><input type="text" id="url_image" /></center></td>
                </tr>
                <tr>
                    <td>Select "yes" to enter the race and the class in the avatar, "no" to insert only the class (Cataclysm Avatar) in the avatar:</td>
                    <td align="middle"><center>Yes <input type="radio" name="type_image" value="race_class" checked="checked" />&nbsp&nbsp&nbsp&nbsp No <input type="radio" name="type_image" value="class" /></center></td>
                </tr>
                <tr>
                    <td>Select the text <a href="javascript:popUp('colors/index.php?field_edit=text_color', 350, 500)">color</a>:</td>
                    <td align="middle"><center><input type="text" id="text_color" onKeyUp="colorField(this);" /></center></td>
                </tr>
                <tr>
                    <td>Select the text <a href="javascript:popUp('fonts/index.php?field_edit=text_font', 300, 420)">font</a>:</td>
                    <td align="middle"><center><input type="text" id="text_font" /></center></td>
                </tr>
                <?php
                    if($image_resize_enabled)
                    {
                        print "<tr>\r\n";
                        print "                    <td>Insert the image size:</td>\r\n";
                        print "                    <td align=\"middle\">\r\n";
                        print "                        <center>\r\n";
                        print "                            X size: <input type=\"text\" id=\"x\" size=\"3\" /><br />\r\n";
                        print "                            Y size: <input type=\"text\" id=\"y\" size=\"3\" />\r\n";
                        print "                        </center>\r\n";
                        print "                    </td>\r\n";
                        print "                </tr>\r\n";
                    }

                    $ordinary_numbers = array("first", "second", "third", "fourth", "fifth");
                    for($i = 0; $i < 5; ++$i)
                    {
                        if($i || $image_resize_enabled)
                            print "                ";
                        print "<tr>\r\n";
                        print "                    <td>\r\n";
                        print "                        Select the stat for the " . $ordinary_numbers[$i] . " field<br />\r\n";
                        print "                        (custom text: <input type=\"checkbox\" id=\"enable_custom_stat" . ($i+1) . "\" onClick=\"switchStat(" . ($i+1) . ");\"/>):\r\n";
                        print "                    </td>\r\n";
                        print "                    <td align=\"middle\">\r\n";
                        print "                        <center>\r\n";
                        print "                            <div id=\"display_stat" . ($i+1) . "\" style=\"display: block;\">\r\n";
                        print "                                <select id=\"stat" . ($i+1) . "\">\r\n";
                        printMenu();
                        print "                                </select>\r\n";
                        print "                            </div><br />\r\n";
                        print "                            <div id=\"display_custom_stat" . ($i+1) . "\" style=\"display: none;\"><input type=\"text\" id=\"custom_stat" . ($i+1) . "\" maxlength=\"20\" /></div>\r\n";
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
                    <img src="images/loading.gif" />
                </div>
                <img id="signature" src="" style="display: none" onLoad="switchImage(true);" onError="showError(this);" onAbort="showError(this);" onContextMenu="return false;" /><br />
                <table align="center" id="links" width="680" border="0" style="display: none">
                    <tr>
                        <td>Direct link to the image:</td>
                        <td><input type="text" id="direct_link" size="40" readOnly="readonly" onClick="selectText(this);" /></td>
                    </tr>
                    <tr>
                        <td>Link to the image with HTML tag:</td>
                        <td><input type="text" id="html_link" size="40" readOnly="readonly" onClick="selectText(this);" /></td>
                    </tr>
                    <tr>
                        <td>Link to the image with HTML tag and link to the Armory:</td>
                        <td><input type="text" id="html_armory_link" size="40" readOnly="readonly" onClick="selectText(this);" /></td>
                    </tr>
                    <tr>
                        <td>Link to images with BBCode tag (for the <?php print $server_name; ?> forum):</td>
                        <td><input type="text" id="bbcode_link" size="40" readOnly="readonly" onClick="selectText(this);" /></td>
                    </tr>
                    <tr>
                        <td>Link to the image with BBCode tag and link to the Armory:</td>
                        <td><input type="text" id="bbcode_armory_link" size="40" readonly="readonly" onClick="selectText(this);" /></td>
                    </tr>
                </table>
            </div>
        </center>
    </body>
</html>