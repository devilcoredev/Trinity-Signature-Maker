<?php
    define("TOOL_INCLUDED", true);
    include("config.php");

    //Funzione che stampa un menu a tendina contente le stats supportate.
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
        <title>Crea la tua firma!</title>
        <script language="JavaScript" type="text/javascript" src="jquery-1.7.1.min.js"></script>
        <script language="JavaScript">
            //Funzione che apre una finestra di popup di dimensione XxY e indirizzo URL.
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

            //Funzione che trasforma il testo con la prima lettera maiuscola ed il resto minuscolo, es: test=>Test TEST=>Test tEsT=>Test.
            function massimizzaTesto(input)
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

            //Funzione che switcha firma<->caricamento, se posta a true passa da caricamento a firma, altrimenti da firma a caricamento.
            function switchImage(mode)
            {
                if(mode)
                {
                    $("#links").show();
                    $("#firma").show();
                    $("#caricamento").hide();

                    //Se le dimensioni coincidono con quelle dei dati errati svuoto i campi.
                    $("#firma").removeAttr("width");
                    $("#firma").removeAttr("height");
                    if($("#firma").width() == <?php print $w_dati_errati; ?> && $("#firma").height() == <?php print $h_dati_errati; ?>)
                        emptyFields();

                    scroll();
                }
                else
                {
                    $("#links").hide();
                    $("#firma").hide();
                    $("#caricamento").show();
                }
            }

            //Funzione richiamata quando le firme non vengono caricate correttamente.
            function showError(input)
            {
                //Azzero gli input.
                emptyFields();

                //Sostituisco l'immagine con una d'errore.
                $(input).attr("src", "images/<?php print $errore_caricamento; ?>");

                //Visualizzo l'immagine e nascondo il caricamento.
                $(input).show();
                $("#caricamento").hide();
            }

            //Funzione che carica la queryString della firma.
            function show_message()
            {
                var indirizzo = "gc_image.php";

                //Aggiungo nuovi campi alla queryString solo se sono stati definiti.

                //Effettua il cambio solo se � stato inserito il nome del pg.
                var nome_pg = $("#nome_pg").val();
                if(nome_pg == '')
                {
                    alert("<?php print $error_pg; ?>");
                    return false;
                }

                var server = $("#server").val();
                if(server != '')
                    indirizzo += "?server=" + server;

                indirizzo += "&nome_pg=" + nome_pg;

                var sfondo = $("#sfondo").val();
                if(sfondo != '')
                    indirizzo += "&sfondo=" + sfondo;

                if(sfondo.indexOf("bg_") != 0)
                {
                    var fine_sfondo = $("#fine_sfondo").val();
                    if(fine_sfondo != '')
                        indirizzo += "&fine_sfondo=" + fine_sfondo;

                    var metodo_sfondo = $("#metodo_sfondo").val();
                    if(metodo_sfondo != '')
                        indirizzo += "&metodo_sfondo=" + metodo_sfondo;
                }

                var effects = $("input[name=effects]:checked").val();
                if(effects != '')
                    indirizzo += "&effects=" + effects;

                var filter = $("#filter").val();
                if(filter != '')
                    indirizzo += "&filter=" + filter;

                var url_image = encodeURIComponent($("#url_image").val());
                if(url_image != '')
                    indirizzo += "&url_image=" + url_image;

                var type_image = $("input[name=type_image]:checked").val();
                if(type_image != '')
                    indirizzo += "&type_image=" + type_image;

                var colore_testo = $("#colore_testo").val();
                if(colore_testo != '')
                    indirizzo += "&colore_testo=" + colore_testo;

                var text_font = $("#text_font").val();
                if(text_font != '')
                    indirizzo += "&text_font=" + text_font;

                <?php
                    //Questa parte viene abilitata soltanto da config.
                    if($image_resize_enabled)
                    {
                        print "var x = $(\"#x\").val();\r\n";
                        print "                if(x != '')\r\n";
                        print "                    indirizzo += \"&x=\" + x;\r\n";

                        print "                var y = $(\"#y\").val();\r\n";
                        print "                if(y != '')\r\n";
                        print "                    indirizzo += \"&y=\" + y;\r\n";
                    }
                ?>

                for(var i = 1; i < 6; ++i)
                    if($("#enable_custom_stat" + i).attr("checked"))
                    {
                        var custom_stat = encodeURIComponent($("#custom_stat" + i).val());
                        if(custom_stat != '')
                            indirizzo += "&custom_stat" + i + '=' + custom_stat;
                    }
                    else
                    {
                        var stat = $("#stat" + i).val();
                        if(stat != '' && stat != '-')
                            indirizzo += "&stat" + i + '=' + stat;
                    }

                //Mi trovo l'indirizzo del collegamento escludendo index.php ed inserendo gc_image + la queryString.
                var indirizzo_path = location.href.replace('#', '');
                var temp_path = indirizzo_path;
                var pos = temp_path.indexOf("index.php");
                if(pos != -1)
                    indirizzo_path = temp_path.slice(0, pos);
                if(indirizzo_path[indirizzo_path.length - 1] != '/')
                    indirizzo_path += '/';

                var absolute_link = indirizzo_path + indirizzo;

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
                            if($index) print "                    ";
                            print "server_keys[$index]          = \"$i\";\r\n";
                            print "                    ";
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

                var armory_link = armory_template_link.replace("%s", armory_server_name).replace("%p", massimizzaTesto(nome_pg));

                if($("#firma").attr("src") != absolute_link)
                {
                    $("#output").show(); //Visualizzo l'output.

                    switchImage(false); //Visualizzo "Caricamento in corso".

                    $("#firma").attr("src", absolute_link); //Modifico il path dell'immagine.

                    //Inserisco i link nelle caselle di testo.
                    direct_link.val(absolute_link);
                    html_link.val("<img src=\"" + absolute_link + "\">");
                    html_armory_link.val("<a href=\"" + armory_link + "\"><img src=\"" + absolute_link + "\"></a>");
                    bbcode_link.val("[img]" + absolute_link + "[/img]");
                    bbcode_armory_link.val("[url=" + armory_link + "][img]" + absolute_link + "[/img][/url]");

                    scroll();
                }

                return true;
            }

            function selezionaTesto(testo)
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

            $(window).load(function() {
                for(var i = 1; i < 6; ++i)
                    switchStat(i);
            });
        </script>
    </head>
    <body>
        <center>
            <table width="<?php print $x; ?>" cellSpacing="0" border="1">
                <tr>
                    <td width="50%">Seleziona il server:</td>
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
                                            print "<option value=\"$i\">$value</option>\n";
                                        }
                                    }
                                    if(!$count) print "<option value=\"-\">---</option>\n";
                                ?>
                            </select>
                        </center>
                    </td>
                </tr>
                <tr>
                    <td>Inserisci il nome del personaggio:</td>
                    <td align="middle"><center><input type="text" id="nome_pg" /></center></td>
                </tr>
                <tr>
                    <td>Seleziona uno sfondo: (seleziona un <a href="javascript:popUp('colori/index.php?field_edit=sfondo', 350, 500)">colore</a> oppure una <a href="javascript:popUp('images/index.php?field_edit=sfondo', 400, 830)">immagine</a>).</td>
                    <td align="middle"><center><input type="text" id="sfondo" /></center></td>
                </tr>
                <tr>
                    <td>Seleziona il <a href="javascript:popUp('colori/index.php?field_edit=fine_sfondo', 350, 500)">colore</a> finale dello sfondo (opzionale):</td>
                    <td align="middle"><center><input type="text" id="fine_sfondo" /></center></td>
                </tr>
                <tr>
                    <td>Seleziona il metodo di sfumatura dello sfondo (opzionale):</td>
                    <td align="middle">
                        <select id="metodo_sfondo">
                            <option value="horizontal">Orizzontale</option>
                            <option value="vertical">Verticale</option>
                            <option value="radial">Radiale</option>
                            <option value="circle">Cerchio</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Seleziona un effetto per lo sfondo:</td>
                    <td align="middle">
                        <center>
                            <table border="0">
                                <tr>
                                    <td>Nessuno:</td>
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
                    <td>Seleziona un filtro per lo sfondo:</td>
                    <td align="middle">
                        <select id="filter">
                            <option value="none">Nessuno</option>
                            <option value="grayscale">Bianco e nero</option>
                            <option value="sepia">Seppia</option>
                            <option value="negate">Negativo</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Inserisci un link ad un avatar:<br />(<u><font color="ff0000"><b>ATTENZIONE</b></font>: un link esterno potrebbe ritardare il caricamento della firma</u>), lasciare vuoto per utilizzare l'immagine di default della classe.</td>
                    <td align="middle"><center><input type="text" id="url_image" /></center></td>
                </tr>
                <tr>
                    <td>Selezinare "s�" per inserire sia la classe che la razza nell'avatar, "no" per inserire solo la classe (avatar Cataclysm) nell'avatar:</td>
                    <td align="middle"><center>S� <input type="radio" name="type_image" value="race_class" checked="checked" />&nbsp&nbsp&nbsp&nbsp No <input type="radio" name="type_image" value="class" /></center></td>
                </tr>
                <tr>
                    <td>Seleziona il <a href="javascript:popUp('colori/index.php?field_edit=colore_testo', 350, 500)">colore</a> del testo:</td>
                    <td align="middle"><center><input type="text" id="colore_testo" /></center></td>
                </tr>
                <tr>
                    <td>Seleziona il <a href="javascript:popUp('fonts/index.php?field_edit=text_font', 300, 420)">carattere</a> del testo:</td>
                    <td align="middle"><center><input type="text" id="text_font" /></center></td>
                </tr>
                <?php
                    if($image_resize_enabled)
                    {
                        print "<tr>\r\n";
                        print "                    <td>Inserisci le dimensioni dell'immagine:</td>\r\n";
                        print "                    <td align=\"middle\">\r\n";
                        print "                        <center>\r\n";
                        print "                            Dimensione x: <input type=\"text\" id=\"x\" size=\"3\" /><br />\r\n";
                        print "                            Dimensione y: <input type=\"text\" id=\"y\" size=\"3\" />\r\n";
                        print "                        </center>\r\n";
                        print "                    </td>\r\n";
                        print "                </tr>\r\n";
                    }

                    $ordinary_numbers = array("primo", "secondo", "terzo", "quarto", "quinto");
                    for($i = 0; $i < 5; ++$i)
                    {
                        if($i || $image_resize_enabled)
                            print "                ";
                        print "<tr>\r\n";
                        print "                    <td>\r\n";
                        print "                        Seleziona la statistica per il " . $ordinary_numbers[$i] . " campo<br />\r\n";
                        print "                        (testo custom: <input type=\"checkbox\" id=\"enable_custom_stat" . ($i+1) . "\" onClick=\"switchStat(" . ($i+1) . ");\"/>):\r\n";
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
                <tr><td colspan="2"><center><input type="button" value="Crea immagine!" onClick="return show_message();" /></center></td></tr>
            </table>
            <div id="output" style="display: none">
                <br />
                <div id="caricamento">
                    Elaborazione firma in corso...<br />
                    <img src="images/loading.gif" />
                </div>
                <img id="firma" src="" style="display: none" onLoad="switchImage(true);" onError="showError(this);" onAbort="showError(this);" onContextMenu="return false;" /><br />
                <table align="center" id="links" width="680" border="0" style="display: none">
                    <tr>
                        <td>Link diretto all'immagine:</td>
                        <td><input type="text" id="direct_link" size="40" readOnly="readonly" onClick="selezionaTesto(this);" /></td>
                    </tr>
                    <tr>
                        <td>Link all'immagine con tag HTML:</td>
                        <td><input type="text" id="html_link" size="40" readOnly="readonly" onClick="selezionaTesto(this);" /></td>
                    </tr>
                    <tr>
                        <td>Link all'immagine con tag HTML e link all'armory:</td>
                        <td><input type="text" id="html_armory_link" size="40" readOnly="readonly" onClick="selezionaTesto(this);" /></td>
                    </tr>
                    <tr>
                        <td>Link all'immagine con tag BBCode (per il forum di <?php print $server_name; ?>):</td>
                        <td><input type="text" id="bbcode_link" size="40" readOnly="readonly" onClick="selezionaTesto(this);" /></td>
                    </tr>
                    <tr>
                        <td>Link all'immagine con tag BBCode e link all'armory:</td>
                        <td><input type="text" id="bbcode_armory_link" size="40" readonly="readonly" onClick="selezionaTesto(this);" /></td>
                    </tr>
                </table>
            </div>
        </center>
    </body>
</html>