<!-- BEGIN view -->
<div class="regular">
    <div class="content">
        <table width="100%">
            <tr>
                <td width="20%">
                    <p class="normfont">{L_NAME}:</p>
                </td>
                <td>
                    <p class="normfont">{GROUP_NAME}</p>
            <tr>
                <td width="20%">
                    <p class="normfont">{L_INFO}:</p>
                </td>
                <td>
                    <p class="normfont">{GROUP_DESCRIPTION}</p>
                </td>
            <tr>
        </table>
    </div>
    <!-- BEGIN members -->
    <div class="title">
        <div class="paginate">{PAGINATE}</div>
    </div>
    <div class="content">
        <table width="100%" cellspacing="2" cellpadding="4">
            <tr class="boxone">
                <td colspan="5">{L_MEMBERS}</td>
            </tr>
            <tr class="boxtwo">
                <td width="10%">
                    <div style="justify-content:center;" class="pagefont"><b>{L_ID}</b></div>
                </td>
                <td width="45%">
                    <div style="justify-content:center;" class="pagefont"><b>{L_NAME}</b></div>
                </td>
                <td width="45%">
                    <div style="justify-content:center;" class="pagefont"><b>{L_JOINED}</b></div>
                </td>
            </tr>
            <!-- BEGIN row -->
            <tr class="row{CLASS}">
                <td>
                    <div style="justify-content:center;" class="normfont">{ID}</div>
                </td>
                <td>
                    <div style="justify-content:center;" class="normfont">{NAME}</div>
                </td>
                <td>
                    <div style="justify-content:center;" class="normfont">{JOINED}</div>
                </td>
            </tr>
            <!-- END row -->
        </table>
    </div>
    <div class="title">
        <div class="paginate">{PAGINATE}</div>
    </div>
    <!-- END members -->
</div>
<!-- END view -->

<!-- BEGIN normal -->

<h2 class="header">{L_GROUP}</h2>
<p class="wordbreak px-1">Check out the team and get to know your fellow players!</p>
<div class="regular">

    <div class="content">
        <div class="paginate">{PAGINATE}</div>
    </div>
    <div class="content">
        <table width="100%" cellspacing="2" cellpadding="4">
            <tr class="boxtwo">
                <td width="10%">
                    <div style="justify-content:center;" class="pagefont"><b>{L_ID}</b></div>
                </td>
                <td width="30%">
                    <div style="justify-content:center;" class="pagefont"><b>{L_GROUP}</b></div>
                </td>
                <td width="60%">
                    <div style="justify-content:center;" class="pagefont"><b>{L_INFO}</b></div>
                </td>
            </tr>
            <!-- BEGIN row -->
            <tr class="row{CLASS}">
                <td>
                    <div style="justify-content:center;" class="normfont">{ID}</div>
                </td>
                <td>
                    <div style="justify-content:center;" class="normfont">{GROUP}</div>
                </td>
                <td>
                    <div style="justify-content:center;" class="normfont">{INFO}</div>
                </td>
            </tr>
            <!-- END row -->
        </table>
    </div>
    <div class="content">
        <div class="paginate">{PAGINATE}</div>
    </div>
</div>
<!-- END normal -->