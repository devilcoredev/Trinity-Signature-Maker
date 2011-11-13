<?php
    include("config.php");

    //Funzione che stampa un menu a tendina contente le stats supportate.
    function printMenu($input)
    {
        print "<option value=\"-\" selected>---</option>\r\n";
        foreach($input as $i => $value)
        {
            print "                                <option value=\"$i\">" . $value["name"] . "</option>\r\n";
        }
    }
?>
<html>
    <head>
        <title>Crea la tua firma!</title>
        <script language="JavaScript">
            //Funzione che apre una finestra di popup di dimensione XxY e indirizzo URL.
            function popUp(URL, X, Y)
            {
                day = new Date();
                id = day.getTime();
                eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0, scrollbars=1, location=0, statusbar=0, menubar=0, resizable=0, width=" + X + ", height=" + Y + ", top=0, left=0');");
                eval("page" + id + ".creator = self;");
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

            //Funzione che switcha firma<->caricamento, se posta a true passa da caricamento a firma, altrimenti da firma a caricamento.
            function switchImage(mode)
            {
                document.getElementById("links").style.display        = (mode ? "block" : "none");
                document.getElementById("firma").style.display        = (mode ? "block" : "none");
                document.getElementById("caricamento").style.display  = (mode ? "none" : "block");
            }

            //Funzione richiamata quando le firme non vengono caricate correttamente.
            function showError(input)
            {
                //Azzero gli input.
                document.getElementsByName("direct_link")[0].value         = '';
                document.getElementsByName("html_link")[0].value           = '';
                document.getElementsByName("html_armory_link")[0].value    = '';
                document.getElementsByName("bbcode_link")[0].value         = '';
                document.getElementsByName("bbcode_armory_link")[0].value  = '';

                //Sostituisco l'immagine con una d'errore.
                input.src = "images/<?php print $errore_caricamento; ?>";

                //Visualizzo l'immagine e nascondo il caricamento.
                input.style.display = "block";
                document.getElementById("caricamento").style.display = "none";
            }

            //Funzione che carica la queryString della firma.
            function show_message()
            {
                var indirizzo = "gc_image.php";
                var count = 0;

                //Aggiungo nuovi campi alla queryString solo se sono stati definiti.

                var server = document.getElementsByName("server")[0].value;
                if(server!='' && server!="undefined")
                {
                    count++;
                    indirizzo += "?server=" + server;
                }

                var nome_pg = document.getElementsByName("nome_pg")[0].value;
                if(nome_pg!='' && nome_pg!="undefined")
                {
                    indirizzo += (count++ ? '&' : '?');
                    indirizzo += "nome_pg=" + nome_pg;
                }

                var sfondo = document.getElementsByName("sfondo")[0].value;
                if(sfondo!='' && sfondo!="undefined")
                {
                    indirizzo += (count++ ? '&' : '?');
                    indirizzo += "sfondo=" + sfondo;
                }

                var effects = document.getElementsByName("effects");
                for(var i=0; i<effects.length; ++i)
                    if(effects[i].checked==1)
                    {
                        indirizzo += (count++ ? '&' : '?');
                        indirizzo += "effects=" + effects[i].value;
                    }

                var filter = document.getElementsByName("filter")[0].value;
                if(filter!='' && filter!="undefined")
                {
                    indirizzo += (count++ ? '&' : '?');
                    indirizzo += "filter=" + filter;
                }

                var url_image = encodeURIComponent(document.getElementsByName("url_image")[0].value);
                if(url_image!='' && url_image!="undefined")
                {
                    indirizzo += (count++ ? '&' : '?');
                    indirizzo += "url_image=" + url_image;
                }

                var type_image = document.getElementsByName("type_image");
                for(var i=0; i<type_image.length; ++i)
                    if(type_image[i].checked==1)
                    {
                        indirizzo += (count++ ? '&' : '?');
                        indirizzo += "type_image=" + type_image[i].value;
                    }

                var colore_testo = document.getElementsByName("colore_testo")[0].value;
                if(colore_testo!='' && colore_testo!="undefined")
                {
                    indirizzo += (count++ ? '&' : '?');
                    indirizzo += "colore_testo=" + colore_testo;
                }

                var text_font = document.getElementsByName("text_font")[0].value;
                if(text_font!='' && text_font!="undefined")
                {
                    indirizzo += (count++ ? '&' : '?');
                    indirizzo += "text_font=" + text_font;
                }

                //Questa parte viene abilitata soltanto da config.
                <?php
                    if($image_resize_enabled)
                    {
                        print "var x = document.getElementsByName('x')[0].value;\r\n";
                        print "                if(x!='' && x!=\"undefined\")\r\n";
                        print "                {\r\n";
                        print "                    indirizzo += (count++ ? '&' : '?');\r\n";
                        print "                    indirizzo += \"x=\" + x;\r\n";
                        print "                }\r\n\r\n";

                        print "                var y = document.getElementsByName('y')[0].value;\r\n";
                        print "                if(y!='' && y!=\"undefined\")\r\n";
                        print "                {\r\n";
                        print "                    indirizzo += (count++ ? '&' : '?');\r\n";
                        print "                    indirizzo += \"y=\" + y;\r\n";
                        print "                }\r\n";
                    }
                ?>

                for(var i=1; i<6; ++i)
                {
                    var stat = document.getElementsByName("stat"+i)[0].value;
                    if(stat!='' && stat!="undefined" && stat!='-')
                    {
                        indirizzo += (count++ ? '&' : '?');
                        indirizzo += "stat" + i + '=' + stat;
                    }
                }

                //Mi trovo l'indirizzo del collegamento escludendo index.php ed inserendo gc_image + la queryString.
                var indirizzo_path = location.href;
                var temp_path = indirizzo_path;
                var pos = temp_path.indexOf("index.php");
                if(pos != -1)
                    indirizzo_path = temp_path.slice(0, pos);
                if(indirizzo_path[indirizzo_path.length - 1] != '/')
                    indirizzo_path += '/';

                //Effettua il cambio solo se è stato inserito il nome del pg.
                if(nome_pg!='' && nome_pg!="undefined")
                {
                    var absolute_link = indirizzo_path + indirizzo;

                    var direct_link         = document.getElementsByName("direct_link")[0];
                    var html_link           = document.getElementsByName("html_link")[0];
                    var html_armory_link    = document.getElementsByName("html_armory_link")[0];
                    var bbcode_link         = document.getElementsByName("bbcode_link")[0];
                    var bbcode_armory_link  = document.getElementsByName("bbcode_armory_link")[0];

                    //Trovo la dimensione del file d'errore (dato che è png sarà molto maggiore delle dimensioni delle firme).
                    var req = new XMLHttpRequest();
                    req.open("GET", absolute_link, false);
                    req.send(null);
                    var headers = req.getResponseHeader("Content-Length");

                    //Se la dimensione combacia con quella dell'immagine d'errore allora svuoto i campi, altrimenti la visualizzo normalmente.
                    if(headers != <?php print $dim_dati_errati; ?>)
                    {
                        var armory_template_link = "<?php print $armory_template_link; ?>";
                        var armory_link = armory_template_link.replace("%s", server.toUpperCase()).replace("%p", massimizzaTesto(nome_pg));

                        //Inserisco i link nelle caselle di testo.
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

                    document.getElementById("firma").src = absolute_link; //Modifico il path dell'immagine.
                    document.getElementById("output").style.display = "block"; //Visualizzo l'output.

                    //Effettuo lo switch delle immagini solo se la firma non è stata già caricata.
                    if(!document.getElementById("firma").complete)
                        switchImage(false);
                }else alert("<?php print $error_pg; ?>");

                return true;
            }

            function selezionaTesto(testo)
            {
                testo.focus();
                testo.select();
            }
        </script>
    </head>
    <body>
        <center>
            <table width="<?php print $x; ?>" cellSpacing="0" border="1">
                <tr>
                    <td width="50%">Seleziona il server:</td>
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
                    <td align="middle"><center><input type="text" name="nome_pg"></center></td>
                </tr>
                <tr>
                    <td>Seleziona uno sfondo: (seleziona un <a href="javascript:popUp('colori/index.php?field_edit=sfondo', 350, 500)">colore</a> oppure una <a href="javascript:popUp('images/index.php?field_edit=sfondo', 400, 830)">immagine</a>).</td>
                    <td align="middle"><center><input type="text" name="sfondo"></center></td>
                </tr>
                <tr>
                    <td>Seleziona un effetto per lo sfondo:</td>
                    <td align="middle">
                        <center>
                            <table border="0">
                                <tr>
                                    <td>Nessuno:</td>
                                    <td><input type="radio" name="effects" value="none" checked="checked"></td>
                                </tr>
                                <?php
                                    foreach($effects as $i => $value)
                                    {
                                        if($i) print "                                ";
                                        print "<tr>\r\n                                    ";
                                        print "<td><img src=\"images/effects/$value.png\" onContextMenu=\"return false;\"></td>";
                                        print "\r\n                                    ";
                                        print "<td><input type=\"radio\" name=\"effects\" value=\"$value\"></td>\r\n";
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
                        <select name="filter">
                            <option value="none">Nessuno</option>
                            <option value="grayscale">Bianco e nero</option>
                            <option value="sepia">Seppia</option>
                            <option value="negate">Negativo</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Inserisci un link ad un avatar:<br>(<u><font color="ff0000"><b>ATTENZIONE</b></font>: un link esterno potrebbe ritardare il caricamento della firma</u>), lasciare vuoto per utilizzare l'immagine di default della classe.</td>
                    <td align="middle"><center><input type="text" name="url_image"></center></td>
                </tr>
                <tr>
                    <td>Selezinare "sì" per inserire sia la classe che la razza nell'avatar, "no" per inserire solo la classe (avatar Cataclysm) nell'avatar:</td>
                    <td align="middle"><center>Sì <input type="radio" name="type_image" value="race_class" checked="checked">&nbsp&nbsp&nbsp&nbspNo <input type="radio" name="type_image" value="class"></center></td>
                </tr>
                <tr>
                    <td>Seleziona il <a href="javascript:popUp('colori/index.php?field_edit=colore_testo', 350, 500)">colore</a> del testo:</td>
                    <td align="middle"><center><input type="text" name="colore_testo"></center></td>
                </tr>
                <tr>
                    <td>Seleziona il <a href="javascript:popUp('fonts/index.php?field_edit=text_font', 300, 420)">carattere</a> del testo:</td>
                    <td align="middle"><center><input type="text" name="text_font"></center></td>
                </tr>
                <?php
                    if($image_resize_enabled)
                    {
                        print "<tr>\r\n";
                        print "                    <td>Inserisci le dimensioni dell'immagine:</td>\r\n";
                        print "                    <td align=\"middle\">\r\n";
                        print "                        <center>\r\n";
                        print "                            Dimensione x: <input type=\"text\" name=\"x\" size=3><br>\r\n";
                        print "                            Dimensione y: <input type=\"text\" name=\"y\" size=3>\r\n";
                        print "                        </center>\r\n";
                        print "                    </td>\r\n";
                        print "                </tr>\r\n";
                    }
                ?>
                <tr>
                    <td>Seleziona la statistica per il primo campo:</td>
                    <td align="middle">
                        <center>
                            <select name="stat1">
                                <?php printMenu($stats); ?>
                            </select>
                        </center>
                    </td>
                </tr>
                <tr>
                    <td>Seleziona la statistica per il secondo campo:</td>
                    <td align="middle">
                        <center>
                            <select name="stat2">
                                <?php printMenu($stats); ?>
                            </select>
                        </center>
                    </td>
                </tr>
                <tr>
                    <td>Seleziona la statistica per il terzo campo:</td>
                    <td align="middle">
                        <center>
                            <select name="stat3">
                                <?php printMenu($stats); ?>
                            </select>
                        </center>
                    </td>
                </tr>
                <tr>
                    <td>Seleziona la statistica per il quarto campo:</td>
                    <td align="middle">
                        <center>
                            <select name="stat4">
                                <?php printMenu($stats); ?>
                            </select>
                        </center>
                    </td>
                </tr>
                <tr>
                    <td>Seleziona la statistica per il quinto campo:</td>
                    <td align="middle">
                        <center>
                            <select name="stat5">
                                <?php printMenu($stats); ?>
                            </select>
                        </center>
                    </td>
                </tr>
                <tr><td colspan="2"><center><input type="button" value="Crea immagine!" onClick="return show_message();"></center></td></tr>
            </table>
            <div id="output" style="display: none">
                <br>
                <div id="caricamento">
                    Elaborazione firma in corso...<br>
                    <img src="images/loading.gif"><br>
                </div>
                <img id="firma" src="" style="display: none" onLoad="switchImage(true);" onError="showError(this);" onAbort="showError(this);" onContextMenu="return false;"><br>
                <table id="links" border="0">
                    <tr>
                        <td>Link diretto all'immagine:</td>
                        <td><input type="text" name="direct_link" size="40" readOnly="readonly" onClick="selezionaTesto(this);"></td>
                    </tr>
                    <tr>
                        <td>Link all'immagine con tag HTML:</td>
                        <td><input type="text" name="html_link" size="40" readOnly="readonly" onClick="selezionaTesto(this);"></td>
                    </tr>
                    <tr>
                        <td>Link all'immagine con tag HTML e link all'armory:</td>
                        <td><input type="text" name="html_armory_link" size="40" readOnly="readonly" onClick="selezionaTesto(this);"></td>
                    </tr>
                    <tr>
                        <td>Link all'immagine con tag BBCode (per il forum di <?php print $server_name; ?>):</td>
                        <td><input type="text" name="bbcode_link" size="40" readOnly="readonly" onClick="selezionaTesto(this);"></td>
                    </tr>
                    <tr>
                        <td>Link all'immagine con tag BBCode e link all'armory:</td>
                        <td><input type="text" name="bbcode_armory_link" size="40" readonly="readonly" onclick="selezionaTesto(this);"></td>
                    </tr>
                </table>
            </div>
        </center>
    </body>
</html>