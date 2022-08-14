<?php

$tpl = $STYLE->open('groups.tpl');

if ( isset($_GET['view']))
{
    $view = $secure->clean($_GET['view']);
} else {
    $view = '';
}

if (isset($_GET['page'])) {
            $page = $_GET['page'];
        } else {
            $page = 1;
        }
        if ($page != 1) {
            $start = ($page - 1) * $limiter;
        } else {
            $start = 0;
        }

if ( $view )
{
     $tpl = str_replace($STYLE->getcode('normal',$tpl),'',$tpl);
     $group_data = $db->fetch("SELECT * FROM usergroups WHERE id = '$view'");
     
     if ( ! $group_data )
     {
         $system->message(L_ERROR,L_GROUP_NOT_FOUND,'./?s=groups',L_CONTINUE);
     }
     
     
    $sql="SELECT * FROM accounts WHERE `group` = '$view'";
    $sql_row = $db->query($sql);
    $group_style = '';
    $class = '0';
	$pages = '';
	if($sql_row){
    while ( $row = $sql_row->fetch())
    {
        $group_style .= $STYLE->tags($STYLE->getcode('row',$tpl),array("CLASS" => $class, "ID" => $row['id'], "NAME" => $user->name($row['id']), "JOINED" => $system->time($user->value($row['id'],'joined'))));
        
        $class = 1 - $class;
    }
	$pages = $system->paginate($sql, '10', '?s=groups&view='.$view.'');
	}
	
	
    if ( ! $group_style )
    {
        $tpl = str_replace($STYLE->getcode('members',$tpl),'',$tpl);
    } else {
    $tpl = str_replace($STYLE->getcode('row',$tpl),$group_style,$tpl);
    }
     
     
      $tpl = $STYLE->tags($tpl,array("L_MEMBERS" => L_MEMBERS, "L_ID" => L_ID, "L_JOINED" => L_JOINED, "L_NAME" => L_NAME, "L_INFO" => L_INFO, "L_GROUP" => L_GROUP, "GROUP_NAME" => $system->present($group_data['title']), "GROUP_DESCRIPTION" => $system->present($group_data['info'])));
    
    
    
    
} else {
     $tpl = str_replace($STYLE->getcode('view',$tpl),'',$tpl);
    $sql= "SELECT * FROM usergroups";
    $sql_row = $db->query($sql);
    
    $group_style = '';
    $class = '0';
    while ( $row = $sql_row->fetch())
    {
        
        $group = '<a href="./?s=groups&amp;view='.$row['id'].'" class="normfont">'.$system->present($row['title']).'</a>';
        $group_style .= $STYLE->tags($STYLE->getcode('row',$tpl),array("CLASS" => $class, "ID" => $row['id'], "GROUP" => $group, "INFO" => $system->present($row['info'])));
        
        $class = 1 - $class;
    }
    
    $tpl = str_replace($STYLE->getcode('row',$tpl),$group_style,$tpl);
      
        $pages = $system->paginate($sql, '10', '?s=groups');
       
    $tpl = $STYLE->tags($tpl,array("L_GROUPS" => L_GROUPS, "L_ID" => L_ID, "L_GROUP" => L_GROUP, "L_INFO" => L_INFO));
    
  
    
}

$output.= $STYLE->tags($tpl,array("PAGINATE" => $pages));

?>