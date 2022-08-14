<div class="content">
<table border="0" cellspacing="0" cellpadding="2">
<tr>
<td>
<INPUT TYPE="button" onClick="parent.location='{URL}acp/?s=forums&amp;mode=new-forum'" name = "add_forum" value ="{L_ADD_FORUM}" class="globaltab" style="width:120px"></td>
<td >
<INPUT TYPE="button" onClick="parent.location='{URL}acp/?s=forums&amp;mode=new-category'" name = "add_category" value ="{L_ADD_CATEGORY}" class="globaltab" style="width:120px"></td>
</tr>
</table>
</div>

<!-- BEGIN new-category -->
<div class="transparent"><font class="navfont">{L_ADD_CATEGORY}</font></div>
<div class="content">
<table width="100%" border="0" cellspacing="0" cellpadding="0">

  <tr>
    <td>
    
    <form action="" method="post" name="rssinput" target="_self"><table width="100%" border="0" cellspacing="0" cellpadding="4">
  <tr>
    <td width="11%"><div align="left"><font class="normfont">{L_NAME}:</font></div></td>
    <td><div align="left">
      <input name="name" type="text" class="globaltab" size="40" maxlength="40" id="name" />
      &nbsp;</div></td>
  </tr>
  <tr>
    <td colspan="2"><div align="center">
      <input type="submit" name="Submit" value="{L_SUBMIT}" class="globaltab"/>
    </div></td>
  </tr>
</table>
</form>
    
    
    </td>
  </tr>
</table>
</div>
<!-- END new-category -->
<!-- BEGIN new-forum -->
<div class="transparent"><font class="navfont">{L_ADD_FORUM}</font></div>
<div class="content">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td> 
    <form action="" method="post" target="_self">
	<table width="100%" border="0" cellspacing="0" cellpadding="4">
  <tr>
    <td width="11%"><div align="left"><font class="normfont">{L_NAME}:</font></div></td>
    <td><div align="left">
      <input name="name" type="text" class="globaltab" size="40" maxlength="40" id="name" />
      &nbsp;</div></td>
  </tr>
  <tr>
    <td width="11%"><div align="left"><font class="normfont">{L_INFO}:</font></div></td>
    <td><div align="left">
      <input name="info" type="text" class="globaltab" size="40" maxlength="60" id="info" />
      &nbsp;</div></td>
  </tr>
<tr>
    <td><div align="left"><font class="normfont">{L_CATEGORY}:</font></div></td>
    <td><div align="left">
      <select size="1" name="category" class="globaltab">
        
                                                               {CATEGORIES}           
    
      </select>
    </div></td>
  </tr>
<tr>
    <td><div align="left"><font class="normfont">{L_PARENT}:</font></div></td>
    <td><div align="left">
      <select size="1" name="parent" class="globaltab">
        
                                                               {FORUMS}           
    
      </select>
    </div></td>
  </tr>
  <tr>
    <td colspan="2"><div align="center">
      <input type="submit" name="Submit" value="{L_SUBMIT}" class="globaltab"/>
    </div></td>
  </tr>
</table>
</form>
    
    
    </td>
  </tr>
</table>
</div>
<!-- END new-forum -->

<!-- BEGIN edit-category -->

<div class="transparent"><font class="navfont">{L_EDIT_CATEGORY}</font></div>
<div class="content"><table width="100%" border="0" cellspacing="0" cellpadding="0">

  <tr>
    <td>
    
    <form action="" method="post" name="rssinput" target="_self"><table width="100%" border="0" cellspacing="0" cellpadding="4">
  <tr>
    <td width="11%"><div align="left"><font class="normfont">{L_NAME}:</font></div></td>
    <td><div align="left">
      <input name="name" type="text" class="globaltab" id="name" value="{NAME}" size="40" maxlength="40" />
      &nbsp;</div></td>
  </tr>
  <tr>
    <td colspan="2"><div align="center">
      <input type="submit" name="Submit" value="{L_SUBMIT}" class="globaltab"/>
    </div></td>
  </tr>
</table>
</form>
    
    
    </td>
  </tr>
</table></div>

<!-- END edit-category -->


<!-- BEGIN edit-forum -->
<div class="transparent"><font class="navfont">{L_EDIT_FORUM}</font></div>
<div class="content"><table width="100%" border="0" cellspacing="0" cellpadding="0">

  <tr>
    <td>
    
    <form action="" method="post" target="_self"><table width="100%" border="0" cellspacing="0" cellpadding="4">
  <tr>
    <td width="11%"><div align="left"><font class="normfont">{L_NAME}:</font></div></td>
    <td><div align="left">
      <input name="name" type="text" class="globaltab" size="40" maxlength="40" id="name" value="{NAME}" />
      &nbsp;</div></td>
  </tr>
  <tr>
    <td width="11%"><div align="left"><font class="normfont">{L_INFO}:</font></div></td>
    <td><div align="left">
      <input name="info" type="text" class="globaltab" size="40" maxlength="60" id="info" value="{INFO}" />
      &nbsp;</div></td>
  </tr>
<tr>
    <td><div align="left"><font class="normfont">{L_CATEGORY}:</font></div></td>
    <td><div align="left">
      <select size="1" name="category" class="globaltab">
        
                                                               {CATEGORIES}           
    
      </select>
    </div></td>
  </tr>
<tr>
    <td><div align="left"><font class="normfont">{L_PARENT}:</font></div></td>
    <td><div align="left">
      <select size="1" name="parent" class="globaltab">
        
                                                               {FORUMS}           
    
      </select>
    </div></td>
  </tr>
  <tr>
    <td colspan="2"><div align="center">
      <input type="submit" name="Submit" value="{L_SUBMIT}" class="globaltab"/>
    </div></td>
  </tr>
</table>
</form>
    
    

    </td>
  </tr>
</table></div>
<!-- END edit-forum -->

<!-- BEGIN category-permission -->
<div class="content">
<h1 class="header">{CATEGORY_NAME}</h1>
<table width="100%" border="0" cellspacing="2" cellpadding="4">


<tr class="content">
<td width="20%"><div align="left" class="navfont">{L_GROUP_LIST}</div></td>
<td width="80%"><div align="left" class="navfont">{L_CURRENT_GROUPS}</div></td>
</tr>
<tr class="row0"><td valign="top">
<form action="" method="post" target="_self">
<select class="globaltab" style="width: 100%; margin:1;"name="all-groups" multiple="multiple" size="5">
{ALL_GROUPS}
</select>
<center><input type="submit" name="add" value="{L_ADD}" class="globaltab"/></center>
</form>
</td>
<td valign="top">
<form action="" method="post" target="_self">
<select class="globaltab" style="width: 100%;" name="current-groups" multiple="multiple" size="5">
{CURRENT_GROUPS}
</select>
<center><input type="submit" name="remove" value="{L_REMOVE}" class="globaltab"/></center>
</form>
</td>

</tr>

</table>
</div>

<!-- END category-permission -->

<!-- BEGIN forum-permission -->


<!-- BEGIN groups -->

<div class="content">
<h1 class="header">{FORUM_NAME}</h1>
<table width="100%" border="0" cellspacing="2" cellpadding="4">
<tr class="content">
<td width="20%"><div align="left" class="navfont">{L_GROUP_LIST}</div></td>
<td width="80%"><div align="left" class="navfont">{L_CURRENT_GROUPS}</div></td>
</tr>
<tr class="row0"><td valign="top">

<form action="" method="post" target="_self">

<select class="globaltab" style="width: 100%; margin:1;"name="all-groups" multiple="multiple" size="5">
{ALL_GROUPS}
</select>
<center><input type="submit" name="add" value="{L_ADD}" class="globaltab"/></center>
</form>


</td>
<td valign="top">

<form action="" method="post" target="_self">
<select class="globaltab" style="width: 100%;" name="current-groups" multiple="multiple" size="5">
{CURRENT_GROUPS}
</select>
<center><input type="submit" name="remove" value="{L_REMOVE}" class="globaltab"/> <input type="submit" name="edit" value="{L_EDIT}" class="globaltab"/></center>
</form>


</td>

</tr>

</table>
</div>

<!-- END groups -->
<!-- BEGIN group-edit -->
<div class="transparent"><font class="navfont">{L_PERMISSIONS}</font></div>

<div class="content">
<form action="" method="post" target="_self">
<table width = "100%">
<tr>
        <td width="20%"><div align="left"><font class="normfont">{L_POST}:</font></div></td>
        <td><div align="left"><font class="normfont">
          <select name="post" class="globaltab">
            <option {POSTYES} value="1">{L_ENABLED}</option>
            <option {POSTNO} value="0">{L_DISABLED}</option>
          </select>
        </font></div></td>
 </tr>

<tr>
        <td><div align="left"><font class="normfont">{L_REPLY}:</font></div></td>
        <td colspan="2"><div align="left"><font class="normfont">
          <select name="reply" class="globaltab">
            <option {REPLYYES} value="1">{L_ENABLED}</option>
            <option {REPLYNO} value="0">{L_DISABLED}</option>
          </select>
        </font></div></td>
 </tr>

<tr>
        <td><div align="left"><font class="normfont">{L_POLL}:</font></div></td>
        <td colspan="2"><div align="left"><font class="normfont">
          <select name="poll" class="globaltab">
            <option {POLLYES} value="1">{L_ENABLED}</option>
            <option {POLLNO} value="0">{L_DISABLED}</option>
          </select>
        </font></div></td>
 </tr>

<tr>
        <td><div align="left"><font class="normfont">{L_UPLOAD}:</font></div></td>
        <td colspan="2"><div align="left"><font class="normfont">
          <select name="upload" class="globaltab">
            <option {UPLOADYES} value="1">{L_ENABLED}</option>
            <option {UPLOADNO} value="0">{L_DISABLED}</option>
          </select>
        </font></div></td>
 </tr>

<tr>
        <td><div align="left"><font class="normfont">{L_MODERATOR}:</font></div></td>
        <td colspan="2"><div align="left"><font class="normfont">
          <select name="moderator" class="globaltab">
            <option {MODERATORYES} value="1">{L_ENABLED}</option>
            <option {MODERATORNO} value="0">{L_DISABLED}</option>
          </select>
        </font></div></td>
 </tr>

 <tr>
    <td colspan="2"><div align="center">
      <input type="submit" name="Submit" value="{L_SUBMIT}" class="globaltab"/>
    </div></td>
  </tr>

</table></form>
</div>

<!-- END group-edit -->

<!-- END forum-permission -->


<!-- BEGIN normal -->
<div class="content">
<table width="100%" cellspacing="2" cellpadding = "4">
<!-- BEGIN category -->
<form action="" method="post" name="category" target="_self">
<tr class="boxone"><td><font class="navfont">{TITLE}
</font><input type="hidden" name="id" value="{ID}"></td></tr>

<tr class="boxtwo"><td><font class="navfont"><input name="catup" type="submit" class="globaltab" value="{L_UP}"/> <input name="catdown"  type="submit" class="globaltab" value="{L_DOWN}"/> <input name="catdel"  type="submit" class="globaltab" value="{L_DELETE}"/> <INPUT TYPE="submit" name="edit-category" value ="{L_EDIT}" class="globaltab">  <INPUT TYPE="submit" name="category-permission" value ="{L_PERMISSIONS}" class="globaltab" style="width:100px;"></font></td></tr>
</form>
<!-- END category -->

<!-- BEGIN row -->
<tr class="row{CLASS}"><td><form action="" method="post" name="row" target="_self"><font class="normfont"><b>{ID} - {TITLE}</b> - {INFO}<div align="right"><input type="hidden" name="id" value="{ID}"><input name="up" type="submit" class="globaltab" value="{L_UP}"/><input name="down" type="submit" class="globaltab" value="{L_DOWN}"/><input name="edit-forum" type="submit" class="globaltab" value="{L_EDIT}"/><input name="empty" type="submit" class="globaltab" value="{L_EMPTY}"/><INPUT TYPE="submit" name="forum-permission" value ="{L_PERMISSIONS}" class="globaltab"><input name="delete" type="submit" class="globaltab"  style="color: #FF0000;" value="{L_DELETE}"/> </font></td></tr></form>
<!-- END row -->

</table>
</div>
<!-- END normal -->