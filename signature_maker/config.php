<?php
    //Disable errors reporting.
    error_reporting(0);

    //Name of the server showed in the signature (it is recommended to use an acronym).
    $server_name = "";

    //Connection's data server's DBS (to add a new server just to copy a block and change the key with the acronym of the new server),
    //the field "$realm_name" will be displayed in signature.
    //Data connection to the database of the first server.
    $realm_name[""]  = "";
    $host[""]        = "";
    $username[""]    = "";
    $password[""]    = "";
    $database[""]    = "";

    //Data connection to the database of the second server (if present, otherwise comment on this block).
    $realm_name[""]  = "";
    $host[""]        = "";
    $username[""]    = "";
    $password[""]    = "";
    $database[""]    = "";

    //Data connection to the database of the third server (if present, otherwise comment on this block).
    $realm_name[""]  = "";
    $host[""]        = "";
    $username[""]    = "";
    $password[""]    = "";
    $database[""]    = "";

    //Data connection to the database of titles and achievements.
    $site_host      = "";
    $site_username  = "";
    $site_password  = "";
    $site_database  = "";

    //Name of the image displayed when the data entered are incorrect.
    $incorrect_data      = "incorrect_data.png";
    $dim_incorrect_data  = filesize("images/$incorrect_data"); //File's size.

    //Name of the image displayed when the signature doesn't load well.
    $charging_error      = "charging_error.png";
    $dim_charging_error  = filesize("images/$charging_error"); //File's size.

    //Error message printed if you do not enter a valid PG's name.
    $error_pg = "Insert a valid PG name!";

    //A generic link to the armory, %s will be the server name, %p will be the character's name. (EG: http://mysite/character-sheet.xml?r=MyServer&cn=MyPG)
    $armory_template_link = "http://mysite/character-sheet.xml?r=%s&cn=%p";

    //Time in seconds after which the signatures are automatically deleted if not used.
    $image_expire_time = 10 * 60;

    //Signature's size.
    $x = 500; //Width.
    $y = 70;  //Height.

    //Enable resizing of images (in proportion) using query string.
    $image_resize_enabled = false;

    //Default background color (red).
    $to_img          = false;
    $start_bg_red    = 255;
    $start_bg_green  = 0;
    $start_bg_blue   = 0;

    //Default text color (gold).
    $text_vet_color[0] = 255; //Red graduation.
    $text_vet_color[1] = 215; //Green graduation.
    $text_vet_color[2] = 0;   //Blue graduation.

    //Changing this value can change the start of the gradient to black.
    $gradient_proportion_y = 2.5;

    //Fonts.
    //To add a new font, copy the font file in the folder "fonts" and add a new block with its characteristics as described below.
    //Description of the fields:
    // - text: displayed name of the font,
    // - name: font file's name (put the files in the folder "fonts"),
    // - pg_name: size of the text containing the character's name in the signature,
    // - stats: size of the text containing the stats in the signature,
    // - addictional_info: size of the text containing the additional informations (class, race, level, guild, rank, server) in the signature,
    // - x: width of the preview of the font,
    // - y: height of the preview of the font.
    $fonts["jokerman"]["text"]              = "Jokerman";
    $fonts["jokerman"]["name"]              = "JOKERMAN.TTF";
    $fonts["jokerman"]["pg_name"]           = 15;
    $fonts["jokerman"]["stats"]             = 7;
    $fonts["jokerman"]["addictional_info"]  = 9;
    $fonts["jokerman"]['x']                 = 215;
    $fonts["jokerman"]['y']                 = 50;

    $fonts["morpheus"]["text"]              = "Morpheus";
    $fonts["morpheus"]["name"]              = "MORPHEUS_0.TTF";
    $fonts["morpheus"]["pg_name"]           = 16;
    $fonts["morpheus"]["stats"]             = 9;
    $fonts["morpheus"]["addictional_info"]  = 10;
    $fonts["morpheus"]['x']                 = 185;
    $fonts["morpheus"]['y']                 = 55;

    $fonts["verdana"]["text"]              = "Verdana";
    $fonts["verdana"]["name"]              = "verdanab.ttf";
    $fonts["verdana"]["pg_name"]           = 15;
    $fonts["verdana"]["stats"]             = 6;
    $fonts["verdana"]["addictional_info"]  = 8;
    $fonts["verdana"]['x']                 = 210;
    $fonts["verdana"]['y']                 = 50;

    $fonts["flashd"]["text"]              = "Flash D";
    $fonts["flashd"]["name"]              = "FlashD.ttf";
    $fonts["flashd"]["pg_name"]           = 20;
    $fonts["flashd"]["stats"]             = 9;
    $fonts["flashd"]["addictional_info"]  = 11;
    $fonts["flashd"]['x']                 = 190;
    $fonts["flashd"]['y']                 = 50;

    //Default font (verdana).
    $font                  = $fonts["verdana"]["name"];
    $dim_pg_name           = $fonts["verdana"]["pg_name"];
    $dim_stats             = $fonts["verdana"]["stats"];
    $dim_addictional_info  = $fonts["verdana"]["addictional_info"];

    //Vector containing the races.
    $races[1]   = "Human";
    $races[2]   = "Orc";
    $races[3]   = "Dwarf";
    $races[4]   = "Night Elf";
    $races[5]   = "Undead";
    $races[6]   = "Tauren";
    $races[7]   = "Gnome";
    $races[8]   = "Troll";
    $races[10]  = "Blood Elf";
    $races[11]  = "Draenei";

    //Vector containing the classes and their names.
    $classes[1]["name"]   = "Warrior";
    $classes[1]["img"]    = "180px-Warrior_crest";
    $classes[2]["name"]   = "Paladin";
    $classes[2]["img"]    = "180px-Paladin_crest";
    $classes[3]["name"]   = "Hunter";
    $classes[3]["img"]    = "180px-Hunter_crest";
    $classes[4]["name"]   = "Rogue";
    $classes[4]["img"]    = "180px-Rogue_crest";
    $classes[5]["name"]   = "Priest";
    $classes[5]["img"]    = "180px-Priest_crest";
    $classes[6]["name"]   = "Death Knight";
    $classes[6]["img"]    = "180px-Death_knight_crest";
    $classes[7]["name"]   = "Shaman";
    $classes[7]["img"]    = "180px-Shaman_crest";
    $classes[8]["name"]   = "Mage";
    $classes[8]["img"]    = "180px-Mage_crest";
    $classes[9]["name"]   = "Warlock";
    $classes[9]["img"]    = "180px-Warlock_crest";
    $classes[11]["name"]  = "Druid";
    $classes[11]["img"]   = "180px-Druid_crest";

    //Tab's names.
    $tab_names[1][0] = "Arms";
    $tab_names[1][1] = "Fury";
    $tab_names[1][2] = "Protection";

    $tab_names[2][0] = "Holy";
    $tab_names[2][1] = "Protection";
    $tab_names[2][2] = "Retribution";

    $tab_names[3][0] = "Beast Mastery";
    $tab_names[3][1] = "Marksmanship";
    $tab_names[3][2] = "Survival";

    $tab_names[4][0] = "Assassination";
    $tab_names[4][1] = "Combat";
    $tab_names[4][2] = "Subtlety";

    $tab_names[5][0] = "Discipline";
    $tab_names[5][1] = "Holy";
    $tab_names[5][2] = "Shadow";

    $tab_names[6][0] = "Blood";
    $tab_names[6][1] = "Frost";
    $tab_names[6][2] = "Unholy";

    $tab_names[7][0] = "Elemental";
    $tab_names[7][1] = "Enhancement";
    $tab_names[7][2] = "Restoration";

    $tab_names[8][0] = "Arcane";
    $tab_names[8][1] = "Fire";
    $tab_names[8][2] = "Frost";

    $tab_names[9][0] = "Affliction";
    $tab_names[9][1] = "Demonology";
    $tab_names[9][2] = "Destruction";

    $tab_names[11][0] = "Balance";
    $tab_names[11][1] = "Feral Combat";
    $tab_names[11][2] = "Restoration";

    //Vector containing the stats to print,
    //"name" will be displayed in the index's drop-down menu,
    //"field_name" will be the field name to search in the database,
    //"text" is the text to be printed in the signature with the value of the stat instead of %s.
    $stats["maxhealth"]["name"]                = "Health";
    $stats["maxhealth"]["field_name"]          = "maxhealth";
    $stats["maxhealth"]["text"]                = "Health: %s";
    $stats["mana"]["name"]                     = "Mana";
    $stats["mana"]["field_name"]               = "mana";
    $stats["mana"]["text"]                     = "Mana: %s";
    $stats["rune"]["name"]                     = "Rune";
    $stats["rune"]["field_name"]               = "rune";
    $stats["rune"]["text"]                     = "Rune: %s";
    $stats["runicpower"]["name"]               = "Runic Power";
    $stats["runicpower"]["field_name"]         = "runicPower";
    $stats["runicpower"]["text"]               = "Runic Power: %s";
    $stats["strength"]["name"]                 = "Strength";
    $stats["strength"]["field_name"]           = "strength";
    $stats["strength"]["text"]                 = "Strength: %s";
    $stats["agility"]["name"]                  = "Agility";
    $stats["agility"]["field_name"]            = "agility";
    $stats["agility"]["text"]                  = "Agility: %s";
    $stats["stamina"]["name"]                  = "Stamina";
    $stats["stamina"]["field_name"]            = "stamina";
    $stats["stamina"]["text"]                  = "Stamina: %s";
    $stats["intellect"]["name"]                = "Intellect";
    $stats["intellect"]["field_name"]          = "intellect";
    $stats["intellect"]["text"]                = "Intellect: %s";
    $stats["spirit"]["name"]                   = "Spirit";
    $stats["spirit"]["field_name"]             = "spirit";
    $stats["spirit"]["text"]                   = "Spirit: %s";
    $stats["armor"]["name"]                    = "Armor";
    $stats["armor"]["field_name"]              = "armor";
    $stats["armor"]["text"]                    = "Armor: %s";
    $stats["blockpct"]["name"]                 = "Block Pct";
    $stats["blockpct"]["field_name"]           = "blockPct";
    $stats["blockpct"]["text"]                 = "Block: %s%";
    $stats["dodgepct"]["name"]                 = "Dodge Pct";
    $stats["dodgepct"]["field_name"]           = "dodgePct";
    $stats["dodgepct"]["text"]                 = "Dodge: %s%";
    $stats["parrypct"]["name"]                 = "Parry Pct";
    $stats["parrypct"]["field_name"]           = "parryPct";
    $stats["parrypct"]["text"]                 = "Parry: %s%";
    $stats["critpct"]["name"]                  = "Crit Pct";
    $stats["critpct"]["field_name"]            = "critPct";
    $stats["critpct"]["text"]                  = "Crit Chance: %s%";
    $stats["rangedcritpct"]["name"]            = "Ranged Crit Pct";
    $stats["rangedcritpct"]["field_name"]      = "rangedCritPct";
    $stats["rangedcritpct"]["text"]            = "Ranged Crit: %s%";
    $stats["spellcritpct"]["name"]             = "Spell Crit Pct";
    $stats["spellcritpct"]["field_name"]       = "spellCritPct";
    $stats["spellcritpct"]["text"]             = "Spell Crit: %s%";
    $stats["attackpower"]["name"]              = "Attack Power";
    $stats["attackpower"]["field_name"]        = "attackPower";
    $stats["attackpower"]["text"]              = "Attack Power: %s";
    $stats["rangedattackpower"]["name"]        = "Ranged Attack Power";
    $stats["rangedattackpower"]["field_name"]  = "rangedAttackPower";
    $stats["rangedattackpower"]["text"]        = "Ranged AP: %s";
    $stats["spellpower"]["name"]               = "Spell Power";
    $stats["spellpower"]["field_name"]         = "spellPower";
    $stats["spellpower"]["text"]               = "Spell Power: %s";
    $stats["resilience"]["name"]               = "Resilience";
    $stats["resilience"]["field_name"]         = "resilience";
    $stats["resilience"]["text"]               = "Resilience: %s";
    $stats["talents"]["name"]                  = "Talents";
    $stats["talents"]["field_name"]            = "talents";
    $stats["talents"]["text"]                  = "Talents: (%s)";

    //Once every 24 hours are re-written information on achievements in the configuration file so you must not always read them from db.
    $to_fill_achievements = false;
    if($file = fopen("custom/check_achievements.lock", 'r'))
    {
        if(!feof($file))
        {
            $time = fgets($file, 255);
            if($time < (time() - (24*60*60)))
                $to_fill_achievements = true;
        }else $to_fill_achievements = true;
        fclose($file);
    }else $to_fill_achievements = true;

    if($to_fill_achievements)
    {
        if($file = fopen("custom/check_achievements.lock", 'w'))
        {
            fputs($file, time());
            fclose($file);
        }

        if($ach_file = fopen("custom/achievements.php", 'w'))
        {
            fputs($ach_file, "<?php\r\n");

            //Total number of achievements (extracted from db).
            $num_ach = 0;
            if($conn = mysql_connect($site_host, $site_username, $site_password, true))
            {
                if(mysql_select_db($site_database, $conn))
                    if($result = mysql_query("SELECT COUNT(*) AS number FROM achievement WHERE points <> 0;", $conn))
                    {
                        if($row = mysql_fetch_array($result, MYSQL_ASSOC)) //I select only the obtanible achievements (those that give points) and insert them into the vector $achievements.
                        {
                            $num_ach = $row["number"];
                            fputs($ach_file, "    \$num_achievements = $num_ach;\r\n\r\n");
                        }
                        mysql_free_result($result);
                    }
                mysql_close($conn);
            }

            //If there are achievements i put the last 2 entries for stat's configuring.
            if($num_ach)
            {
                fputs($ach_file, "    \$stats[\"achievements\"][\"name\"]             = \"Achievements\";\r\n");
                fputs($ach_file, "    \$stats[\"achievements\"][\"field_name\"]       = \"achievements\";\r\n");
                fputs($ach_file, "    \$stats[\"achievements\"][\"text\"]             = \"Ach.: %s/\$num_achievements\"; //Obtained achievements / Obtanible achievements.\r\n");
                fputs($ach_file, "    \$stats[\"achievementpoints\"][\"name\"]        = \"Achievement Points\";\r\n");
                fputs($ach_file, "    \$stats[\"achievementpoints\"][\"field_name\"]  = \"achievementPoints\";\r\n");
                fputs($ach_file, "    \$stats[\"achievementpoints\"][\"text\"]        = \"Ach. Points: %s\";\r\n");
            }

            fputs($ach_file, "?>");

            fclose($file);
        }
    }

    if(file_exists("custom/achievements.php"))
        include("custom/achievements.php");
?>
<?php
    //Code that automatically deletes all the images are not used for 10 minutes (check carried out every 2 minutes to avoid spam).
    $todo_clean = false;
    if($file = fopen("custom/check_clean.lock", 'r'))
    {
        if(!feof($file))
        {
            $time = fgets($file, 255);
            if($time < (time() - 120))
                $todo_clean = true;
        }else $todo_clean = true;
        fclose($file);
    }else $todo_clean = true;

    if($todo_clean)
    {
        if($file = fopen("custom/check_clean.lock", 'w'))
        {
            fputs($file, time());
            fclose($file);
        }

        if($connessione = mysql_connect($site_host, $site_username, $site_password, true))
        {
            if(mysql_select_db($site_database, $connessione))
            {
                if($result = mysql_query("SELECT * FROM savedimages WHERE lastEdit < (UNIX_TIMESTAMP() - $image_expire_time);", $connessione))
                {
                    while($row = mysql_fetch_array($result, MYSQL_ASSOC))
                        if(file_exists("saved/" . $row["imageName"]) && is_file("saved/" . $row["imageName"]))
                            unlink("saved/" . $row["imageName"]);
                    mysql_free_result($result);
                }
                mysql_query("DELETE FROM savedimages WHERE lastEdit < (UNIX_TIMESTAMP() - $image_expire_time);", $connessione);
            }
            mysql_close($connessione);
        }
    }
?>
<?php
    //The function returns 0 if the achievement is not valid (eg achievement of first kill, or Feast of Strength) or achievement's points if it's valid.
    function isValidAchievement($achievement_id)
    {
        global $site_host, $site_username, $site_password, $site_database;
        if($my_conn = mysql_connect($site_host, $site_username, $site_password, true))
        {
            if(mysql_select_db($site_database, $my_conn))
                if($my_result = mysql_query("SELECT points FROM achievement WHERE ID = $achievement_id;", $my_conn))
                {
                    if($my_row = mysql_fetch_array($my_result, MYSQL_ASSOC))
                    {
                        mysql_free_result($my_result);
                        return $my_row["points"];
                    }
                    mysql_free_result($my_result);
                }
            mysql_close($my_conn);
        }

        return 0;
    }

    //The function returns information about a given talents.
    function getTalentInfo($spellId)
    {
        global $site_host, $site_username, $site_password, $site_database;
        if($my_conn = mysql_connect($site_host, $site_username, $site_password, true))
        {
            if(mysql_select_db($site_database, $my_conn))
                if($my_result = mysql_query("SELECT rankId, tabPage FROM talent WHERE spellTalent = $spellId;", $my_conn))
                {
                    if($my_row = mysql_fetch_array($my_result, MYSQL_ASSOC))
                    {
                        mysql_free_result($my_result);
                        return $my_row;
                    }
                    mysql_free_result($my_result);
                }
            mysql_close($my_conn);
        }

        return 0;
    }
?>