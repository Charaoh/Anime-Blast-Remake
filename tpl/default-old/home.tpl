<!-- BEGIN menu -->
<FORM style="padding: 0px; margin: 0px">
<INPUT TYPE="button" value ="{L_SEARCH}" class="globaltab" onClick="parent.location='./?s=search'">
<INPUT TYPE="button" value ="{L_NEW_POSTS}" class="globaltab" onClick="parent.location='./?s=search&amp;mode=new'">
</FORM>
<!-- END menu -->
<!-- BEGIN category -->
<div class="content">
        <p class="category">{NAME}</p>
		<p class="stat">{L_TOPICS} / {L_LATEST}</p>
<!-- END category -->
<!-- BEGIN row -->
		<div class="forum">
			<div class="title" data-descr="{INFO}"><img src="./tpl/default/img/{ICON}.png" class="me" alt="">{FORUM}</div>		       
			<div class="stat topic">{TOPIC_COUNT}</div>
			<div class="stat latest">{TOPIC}
			</div>
        </div>
<!-- END row -->
<!-- BEGIN category_end -->
</div>
<!-- END category_end -->

<!-- BEGIN break -->
<br/>
<!-- END break -->

	<div class="content">
		<p class="category bottom">{L_SUMMARY}</p>
		{L_STATS}
	</div>
	<div class="content">
		<p class="category bottom">{L_ONLINE}</p>
		{ONLINE_STATS}
	</div>
