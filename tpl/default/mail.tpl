<!-- BEGIN menu -->
<FORM style="padding: 0px; margin: 0px">
  <INPUT TYPE="button" value="{L_NEW}" class="globaltab" onClick="parent.location='./mail?mode=new'">
  <INPUT TYPE="button" value="{L_INBOX}" class="globaltab" onClick="parent.location='./mail'">
  <INPUT TYPE="button" value="{L_SENTBOX}" class="globaltab" onClick="parent.location='./mail?mode=sent'">
</FORM>
<!-- END menu -->
<!-- BEGIN new -->
<div class="content">{L_NEW_MAIL}</div>
<div class="content">
  <table width="100%" border="0" cellspacing="2" cellpadding="4">
    <form action="" method="post" name="post" target="_self">
      <tr>

        <td width="11%">
          <div align="left">
            <font class="normfont">{L_USER}:</font>
          </div>
        </td>

        <td width="89%">
          <div align="left">

            <input name="user" type="text" class="formcss" size="40" maxlength="40" value="{USERNAME}" />

          </div>
        </td>




      <tr>
      <tr>

        <td width="11%">
          <div align="left">
            <font class="normfont">{L_TITLE}:</font>
          </div>
        </td>

        <td width="89%">
          <div align="left">

            <input name="title" type="text" class="formcss" size="40" maxlength="40" />

          </div>
        </td>




      <tr>

        <td colspan="2">
          <div align="left">

            <input type="button" value="Bold" onclick="wrapText('message','[b]','[/b]')" class="formcss" />
            <input type="button" value="Italic" onclick="wrapText('message','[i]','[/i]')" class="formcss" />
            <input type="button" value="Underline" onclick="wrapText('message','[u]','[/u]')" class="formcss" />
            <input type="button" value="Size" onclick="wrapText('message','[size=8]','[/size]')" class="formcss" />
            <input type="button" value="Color" onclick="wrapText('message','[color=000000]','[/color]')"
              class="formcss" />
            <input type="button" value="Align" onclick="wrapText('message','[align=center]','[/align]')"
              class="formcss" />
            <input type="button" value="WWW" onclick="wrapText('message','[url=http://link here]','[/url]')"
              class="formcss" />
            <input type="button" value="Img" onclick="wrapText('message','[img]','[/img]')" class="formcss" />
            <input type="button" value="Youtube" onclick="wrapText('message','[youtube]','[/youtube]')"
              class="formcss" />
            <input type="button" value="Spoiler" onclick="wrapText('message','[spoiler= button text]','[/spoiler]')"
              class="formcss" />
          </div>
        </td>

      </tr>

      <tr>

        <td colspan="2">
          <div align="center">
            {BBCODES}
          </div>
        </td>

      </tr>

      <tr>

        <td colspan="2">
          <div align="center">

            <textarea name="message" id="message" rows="10" cols="40" style="width: 98%; height: 50"
              class="formcss"></textarea>

          </div>
        </td>

      </tr>

      <tr>

        <td colspan="2">
          <div align="center"><input name="Submit" type="submit" value="{L_SUBMIT}" class="formcss" /></div>
        </td>

      </tr>
    </form>
  </table>
</div>


<!-- END new -->
<!-- BEGIN view -->
<div class="content">
  <table width="100%" border="0" cellspacing="2" cellpadding="4">
    <tr class="boxone">
      <td colspan="5">{TITLE}</td>
    </tr>
    <!-- BEGIN row -->
    <tr class="content">

      <td width="20%">
        <div align="left" class="navfont"><b>{ID}</b></div>
      </td>
      <td width="80%">
        <div align="left" class="navfont"><b>{DATE}</b></div>
      </td>

    </tr>

    <tr class="row0">

      <td valign="top">
        <div align="center" class="normfont">{AUTHOR}{STATUS}<br />{RANK}<br />{AVATAR}<br />
        </div>
      </td>
      <td valign="top">


        <font class="normfont">

          {TEXT}
        </font>
      </td>

    </tr>
  </table>
</div>

<!-- BEGIN reply -->
<div class="content">
  <div class="content">
    <font class="navfont">{L_REPLY}</font>
  </div>

  <div class="content">



    <table width="100%" border="0" cellspacing="2" cellpadding="4">
      <form action="" method="post" name="post">
        <tr>

          <td colspan="2">
            <div align="left">

              <input type="button" value="Bold" onclick="wrapText('message','[b]','[/b]')" class="formcss" />
              <input type="button" value="Italic" onclick="wrapText('message','[i]','[/i]')" class="formcss" />
              <input type="button" value="Underline" onclick="wrapText('message','[u]','[/u]')" class="formcss" />
              <input type="button" value="Size" onclick="wrapText('message','[size=8]','[/size]')" class="formcss" />
              <input type="button" value="Color" onclick="wrapText('message','[color=000000]','[/color]')"
                class="formcss" />
              <input type="button" value="Align" onclick="wrapText('message','[align=center]','[/align]')"
                class="formcss" />
              <input type="button" value="WWW" onclick="wrapText('message','[url=http://link here]','[/url]')"
                class="formcss" />
              <input type="button" value="Img" onclick="wrapText('message','[img]','[/img]')" class="formcss" />
              <input type="button" value="Youtube" onclick="wrapText('message','[youtube]','[/youtube]')"
                class="formcss" />
              <input type="button" value="Spoiler" onclick="wrapText('message','[spoiler= button text]','[/spoiler]')"
                class="formcss" />
            </div>
          </td>

        </tr>

        <tr>

          <td colspan="2">
            <div align="center">

              {BBCODES}
            </div>
          </td>

        </tr>

        <tr>

          <td width="100%">
            <div align="center">

              <textarea name="message" id="message" rows="4" cols="40" style="width: 98%; height: 50"
                class="formcss"></textarea>

            </div>
          </td>

        </tr>

        <tr>

          <td>
            <div align="center"><input name="Submit" type="submit" value="{L_SUBMIT}" class="formcss" /></div>
          </td>

        </tr>
      </form>
    </table>
  </div>
</div>
<!-- END reply -->

<!-- END view -->

<!-- BEGIN normal -->

<div class="content">
  <div class="pages">{PAGES}</div>
</div>
<div class="content">
  <form name="form1" method="post" action="">
    <table width="100%" border="0" cellspacing="2" cellpadding="4">
      <tr class="content">
        <td colspan="5"> {L_TITLE}</td>
      </tr>
      <tr class="content">
        <td width="1%">&nbsp; </td>
        <td>
          <div align="left" class="navfont">
            <b>{L_MESSAGE}</b>
          </div>
        </td>
        <td width="1%">
          <div align="center" class="navfont">{L_TO_FROM}</div>
        </td>
        </td>
        <td width="1%">
          <div align="center" class="navfont">{L_DATE}</div>
        </td>
        <td width="1%">
          <div align="center" class="navfont">{L_DELETE}</div>
        </td>
      </tr>
      <!-- BEGIN row -->
      <tr class="row{CLASS}">
        <td width="5%"><img src="./tpl/default/img/{ICON}.gif" alt=""></td>
        <td width="55%">
          <div align="left">
            <font class="normfont">{TITLE}</font>
          </div>
        </td>
        <td width="20%">
          <div align="center">
            <font class="normfont">{AUTHOR}</font>
          </div>
        </td>
        <td width="20%">
          <div align="center">
            <font class="normfont">{DATE}</font>
          </div>
        </td>
        <td width="20%">
          <div align="center">
            <input name="checkbox[]" type="checkbox" id="checkbox[]" value="{ID}">
          </div>
        </td>
      </tr>
      <!-- END row -->
      <!-- BEGIN delete -->
      <tr>
        <td colspan="5" align="right" class="boxtwo"><input name="delete" type="submit" id="delete" value="{L_DELETE}"
            class="formcss"></td>
      </tr>
      <!-- END delete -->
    </table>
  </form>
</div>
<div class="content">
  <div class="pages">{PAGES}</div>
</div>
</div>
<!-- END normal -->
<script>
  function wrapText(elementID, openTag, closeTag) {
    var textArea = $('#' + elementID);
    var len = textArea.val().length;
    var start = textArea[0].selectionStart;
    var end = textArea[0].selectionEnd;
    var selectedText = textArea.val().substring(start, end);
    var replacement = openTag + selectedText + closeTag;
    textArea.val(textArea.val().substring(0, start) + replacement + textArea.val().substring(end, len));
  }
</script>