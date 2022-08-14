
<!-- BEGIN sell -->
<div class="content">
<h2 class="header"> Sell an item</h2>
<p class="wordbreak">Please fill out the required form to sell your item to the community. Keep in mind the server will take a percentage by posting and will be posted for a week! By posting, you agree to this. On that note, items only allowed will be able to be sold! Currently only characters can be sold here. When the item is posted, it is retained by the server until sold or expired.
</p>
<br/>
<input type="button" value="Go Back" class="globaltab" onclick="parent.location='./?s=shop'" placeholder="" style="
    float: right;
">
</div>
<div class="content">
<h2 class="header" style="
    margin-bottom: 0;
"> Inventory<span class="what" data-descr="Check multiple items to make a bundle pack!" style="
    float: right;
">?</span></h2>
<form action="" method="post" name="post" target="_self" enctype="multipart/form-data">
<ul class="inventory">	   
{INVENTORY}
</ul>
<br class="clearfix">
<div align="center">
<input name="cost" type="text" class="formcss" size="20" maxlength="30" placeholder="Choose a price for your item">
</div><br>

<div align="center"><input name="Submit" type="submit" value="Sell this item!" class="globaltab"></div>
</form>
</div>
<!-- END sell -->
<div class="content">

<h2 class="header"> Blast Coin Shop<img src="{URL}/tpl/default/img/gold.png" style="width: 30px;float: right;padding-top: 0px;"><span style="
    float: right;
    font-size: 12px;
    /* width: 14%; */
    margin-right: 0px;
    padding-top: 5px;
">{COINS} BC</span></h2>
<p class="wordbreak">Here you can use your blast coins to buy special items sold by the staff and community items at a bargain!
</p>
<br/>
<input type="button" value="Sell" class="globaltab" onclick="parent.location='./?s=shop&amp;mode=sell'" placeholder="" style="
    float: right;
">
<br/>

<div class="content">
<br>
<!-- 
<p class="community">Community items<span class="what" data-descr="Here you will find items posted by the community">?</span></p><p class="stat"><img src="{URL}/tpl/default/img/gold.png" style="width: 30px;"> / Quantity / Seller</p>	
{COMMUNITY}
<br/>-->
<p class="community">   EXCLUSIVES<span class="what" data-descr="Here you will find items posted by the staff">?</span></p>
<p class="stat"><img src="{URL}/tpl/default/img/gold.png" style="width: 30px;"> / Quantity / Seller</p>
{SHOP}
<!-- BEGIN sold -->
<p class="tapado"><img src="{URL}/tpl/default/img/soldout.png" style="
    width: 40%;
"></p>
<!-- END sold -->
<!-- BEGIN bought -->
<p class="tapado"><img src="{URL}/tpl/default/img/bought.png" style="
    width: 40%;
"></p>
<!-- END bought -->
<!-- BEGIN timeout -->
<p class="tapado"><img src="{URL}/tpl/default/img/timeout.png" style="
    width: 40%;
"></p>
<!-- END timeout -->
<!-- BEGIN item -->
		<div class="forum">
			<div class="title" style="
    color: #34d0f1;
    width: 50%;
"><span style="
    font-size: 20px;
    border-bottom: 1px dotted white;
    margin-bottom: 3px;
    display: inline-block;
">{TITLE}</span><br />{IMAGES}<br style="
    clear: both;
">
<p style="color: white;text-align: center;margin-top: 6px;">{DESCRIPTION}</p></div>		   
			<div class="stat topic" style="
    width: 11%;
">
<!-- BEGIN discount -->
<p style="
    width: 50%;
    height: 3px;
    background: red;
    position: relative;
    top: 9px;
"></p><!-- END discount -->{VALUE} BC<!-- BEGIN discount --><div class="stat topic" style="
    /* width: 11%; */
    font-size: 15px;
">{NEW} BC</div><!-- END discount --></div><div class="stat topic">{QUANTITY}</div>
			<div class="stat latest" style="
    float: left;
    width: 18%;
">{AVATAR}{WHO}</div>
<!-- BEGIN discount -->
<p style="
    font-size: 40px;
    /* float: right; */
    /* width: 50%; */
    position: absolute;
    right: 0;
    margin-top: 26px;
    margin-right: 10%;
    /* color: #ffffff82; */
    opacity: 0.5;
">{DISCOUNT}</p>
<!-- END discount -->
        <br class="clearfix">
<!-- BEGIN buy -->
<div style="
    text-align: center;
"><input type="button" value="Buy" class="globaltab" onclick="parent.location='./?s=shop&amp;buy={ID}'" placeholder="">
</div>
<!-- END buy -->
</div>
<!-- END item -->

</div>

</div>
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- Profile add -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-6829406195290006"
     data-ad-slot="5625947147"
     data-ad-format="auto"
     data-full-width-responsive="true"></ins>
<script>
     (adsbygoogle = window.adsbygoogle || []).push({});
</script>