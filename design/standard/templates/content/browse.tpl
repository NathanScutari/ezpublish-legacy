{* DO NOT EDIT THIS FILE! Use an override template instead. *}
{let browse_indentation=5
     page_limit=15
     browse_list_count=fetch(content,list_count,hash(parent_node_id,$node_id,depth,1))
     object_array=fetch(content,list,hash(parent_node_id,$node_id,depth,1,offset,$view_parameters.offset,limit,$page_limit,sort_by,$main_node.sort_array))
     bookmark_list=fetch('content','bookmarks',array())
     recent_list=fetch('content','recent',array())

     select_name='SelectedObjectIDArray'
     select_type='checkbox'
     select_attribute='contentobject_id'}

{section show=eq($browse.return_type,'NodeID')}
    {set select_name='SelectedNodeIDArray'}
    {set select_attribute='node_id'}
{/section}
{section show=eq($browse.selection,'single')}
    {set select_type='radio'}
{/section}

<form action={concat($browse.from_page)|ezurl} method="post">

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>

    <td width="80%" valign="top">

{section show=$browse.description_template}
    {include name=Description uri=$browse.description_template browse=$browse main_node=$main_node}
{section-else}
    <div class="maincontentheader">
    <h1>{"Browse"|i18n("design/standard/content/browse")} - {$main_node.name|wash}</h1>
    </div>

    <p>{'To select objects, choose the appropriate radiobutton or checkbox(es), and click the "Choose" button.'|i18n("design/standard/content/browse")}</p>
    <p>{'To select an object that is a child of one of the displayed objects, click the object name and you will get a list of the children of the object.'|i18n("design/standard/content/browse")}</p>
{/section}

        {* Browse listing start *}
        <table class="list" width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <th width="1">
            </th>
            <th width="69%">
            {"Name"|i18n("design/standard/content/browse")}
            </th>
            <th width="30%">
            {"Class"|i18n("design/standard/content/browse")}
            </th>
            <th width="30%">
            {"Section"|i18n("design/standard/content/browse")}
            </th>
        </tr>
        <tr>
            <td class="bglight">
            {section show=and( or( $browse.permission|not,
                                   cond( is_set( $browse.permission.contentclass_id ),
                                         fetch( content, access,
                                                hash( access, $browse.permission.access,
                                                      contentobject, $main_node,
                                                      contentclass_id, $browse.permission.contentclass_id ) ),
                                         fetch( content, access,
                                                hash( access, $browse.permission.access,
                                                      contentobject, $main_node ) ) ) ),
                               $browse.ignore_nodes_select|contains( $main_node.node_id )|not() )}
	      {section show=is_array($browse.class_array)}
	        {section show=$browse.class_array|contains($main_node.object.content_class.identifier)}
		  <input type="{$select_type}" name="{$select_name}[]" value="{$main_node[$select_attribute]}" {section show=eq($browse.selection,'single')}checked="checked"{/section} />
		{section-else}
		    &nbsp;
		{/section}
	      {section-else}
	        <input type="{$select_type}" name="{$select_name}[]" value="{$main_node[$select_attribute]}" {section show=eq($browse.selection,'single')}checked="checked"{/section} />
	      {/section}
	    {section-else}
	        &nbsp;
            {/section}
            </td>

            <td class="bglight">
{*                 {node_view_gui view=line content_node=$main_node node_url=concat( 'content/browse', $main_node.parent_node_id, '/' )} *}
                {node_view_gui view=line content_node=$main_node node_url=false()}
                {section show=$main_node.depth|gt(1)}
                    <a href={concat("/content/browse/",$main_node.parent_node_id,"/")|ezurl}>
                        [{'Up one level'|i18n('design/standard/content/browse')}]
                    </a>
                {/section}
            </td>

            <td class="bglight">
            {$main_node.object.content_class.name|wash}
            </td>

            <td class="bglight">
            {$main_node.object.section_id}
            </td>
        </tr>
        {section name=Object loop=$object_array sequence=array(bgdark,bglight)}
        <tr class="{$Object:sequence}">
            <td>
            {section show=and( or( $browse.permission|not,
                                   cond( is_set( $browse.permission.contentclass_id ),
                                         fetch( content, access,
                                                hash( access, $browse.permission.access,
                                                      contentobject, $:item,
                                                      contentclass_id, $browse.permission.contentclass_id ) ),
                                         fetch( content, access,
                                                hash( access, $browse.permission.access,
                                                      contentobject, $:item ) ) ) ),
                               $browse.ignore_nodes_select|contains($:item.node_id)|not() )}
              {section show=is_array($browse.class_array)}
                {section show=$browse.class_array|contains($:item.object.content_class.identifier)}
                  <input type="{$select_type}" name="{$select_name}[]" value="{$:item[$select_attribute]}" />
                {section-else}
                  &nbsp;
                {/section}
              {section-else}
                <input type="{$select_type}" name="{$select_name}[]" value="{$:item[$select_attribute]}" />
              {/section}
            {/section}
            </td>

            <td>
                <img src={"1x1.gif"|ezimage} width="{mul(sub($:item.depth,$main_node.depth),$browse_indentation)}" height="1" alt="" border="0" />

                 {node_view_gui view=line content_node=$Object:item node_url=cond( $browse.ignore_nodes_click|contains($Object:item.node_id)|not(), concat( 'content/browse/', $Object:item.node_id, '/' ), false() )}
            </td>

            <td>
                    {$Object:item.object.content_class.name|wash}
            </td>

            <td>
                    {$:item.object.section_id}
            </td>
        </tr>
        {/section}
        </table>
        {* Browse listing end *}

    </td>

    <td width="20">
    </td>

    <td width="200" valign="top">

        {* Recent and bookmark start *}
        <table class="list" width="100%" border="0" cellspacing="0" cellpadding="0">

        <tr>
            <th colspan="2">
                {"Top levels"|i18n("design/standard/content/browse")}
            </th>
        </tr>

        <tr>
            <td colspan="2">
                {'Switch top levels by clicking one of these items.'|i18n('design/standard/content/browse')}
            </td>
        </tr>

        {section name=TopLevel loop=$browse.top_level_nodes
                 sequence=array(bgdark,bglight)}
        <tr class="{$:sequence}">
            {let top_node=fetch( content, node, hash( node_id, $:item ) )}
            <td width="1">
            {section show=and( or( $browse.permission|not,
                                   cond( is_set( $browse.permission.contentclass_id ),
                                         fetch( content, access,
                                                hash( access, $browse.permission.access,
                                                      contentobject, $:top_node,
                                                      contentclass_id, $browse.permission.contentclass_id ) ),
                                         fetch( content, access,
                                                hash( access, $browse.permission.access,
                                                      contentobject, $:top_node ) ) ) ),
                               $browse.ignore_nodes_select|contains( $:item )|not() )}
              {section show=is_array($browse.class_array)|not()}
                <input type="{$select_type}" name="{$select_name}[]" value="{$:item}" />
              {section-else}
                &nbsp;
              {/section}
            {section-else}
              &nbsp;
            {/section}
            </td>

            <td>
                {node_view_gui view=line content_node=$:top_node
                               node_url=cond( eq( $:item, $main_node.node_id ), false(),
                                              $browse.ignore_nodes_click|contains( $:top_node.node_id )|not(), concat( 'content/browse/', $:top_node.node_id, '/' ), false() )}
            </td>
            {/let}
        </tr>
        {/section}

        <tr>
            <th colspan="2">
                {"Bookmarks"|i18n("design/standard/content/browse")}
            </th>
        </tr>

        {section name=Bookmark loop=$bookmark_list show=$bookmark_list sequence=array(bgdark,bglight)}
        <tr class="{$:sequence}">
            <td width="1">
            {section show=and( or( $browse.permission|not,
                                   cond( is_set( $browse.permission.contentclass_id ),
                                         fetch( content, access,
                                                hash( access, $browse.permission.access,
                                                      contentobject, $:item.node,
                                                      contentclass_id, $browse.permission.contentclass_id ) ),
                                         fetch( content, access,
                                                hash( access, $browse.permission.access,
                                                      contentobject, $:item.node ) ) ) ),
                               $browse.ignore_nodes_select|contains( $:item.node_id )|not() )}
              {section show=is_array($browse.class_array)}
                {section show=$browse.class_array|contains($:item.object.content_class.identifier)}
                  <input type="{$select_type}" name="{$select_name}[]" value="{$:item.node[$select_attribute]}" />
                {section-else}
                  &nbsp;
                {/section}
              {section-else}
                <input type="{$select_type}" name="{$select_name}[]" value="{$:item.node[$select_attribute]}" />
              {/section}
            {section-else}
              &nbsp;
            {/section}
            </td>

            <td>
                {node_view_gui view=line content_node=$:item.node
                               node_url=cond( eq( $:item.node_id, $main_node.node_id ), false(),
                                              $browse.ignore_nodes_click|contains( $:item.node_id )|not(), concat( 'content/browse/', $:item.node_id, '/' ), false() )}
            </td>
        </tr>
        {section-else}
        <tr>
            <td colspan="2">
                {'Bookmark items are managed using %bookmarkname in the %personalname part.'
                 |i18n('design/standard/content/browse',,
                       hash('%bookmarkname',concat('<i>','My bookmarks'|i18n('design/standard/content/browse'),'</i>'),
                            '%personalname',concat('<i>','Personal'|i18n('design/standard/content/browse'),'</i>')))}
            </td>
        </tr>
        {/section}

        <tr height="6">
            <td>
            </td>
        </tr>

        <tr>
            <th colspan="2">
                {"Recent items"|i18n("design/standard/content/browse")}
            </th>
        </tr>

        {section show=$recent_list}
            {section name=Recent loop=$recent_list sequence=array(bgdark,bglight)}
            <tr class="{$:sequence}">
                <td width="1">
                {section show=and( or( $browse.permission|not,
                                       cond( is_set( $browse.permission.contentclass_id ),
                                             fetch( content, access,
                                                    hash( access, $browse.permission.access,
                                                          contentobject, $:item.node,
                                                          contentclass_id, $browse.permission.contentclass_id ) ),
                                             fetch( content, access,
                                                    hash( access, $browse.permission.access,
                                                          contentobject, $:item.node ) ) ) ),
                                   $browse.ignore_nodes_select|contains( $:item.node_id )|not() )}
                  {section show=is_array($browse.class_array)|not()}
                    <input type="{$select_type}" name="{$select_name}[]" value="{$:item[$select_attribute]}" />
                  {section-else}
                    &nbsp;
                  {/section}
                {section-else}
                  &nbsp;
                {/section}
                </td>

                <td>
                {node_view_gui view=line content_node=$:item.node
                               node_url=cond( eq( $:item.node_id, $main_node.node_id ), false(),
                                              $browse.ignore_nodes_click|contains( $:item.node_id )|not(), concat( 'content/browse/', $:item.node_id, '/' ), false() )}
                </td>
            </tr>
            {/section}
        {section-else}
        <tr>
            <td colspan="2">
                {'Recent items are added on publishing.'|i18n('design/standard/content/browse')}
            </td>
        </tr>
        {/section}

        </table>
        {* Recent and bookmark end *}

    </td>

</tr>
</table>

{include name=Navigator
         uri='design:navigator/google.tpl'
         page_uri=concat('/content/browse/',$main_node.node_id)
         item_count=$browse_list_count
         view_parameters=$view_parameters
         item_limit=$page_limit}


{section name=Persistent show=$browse.persistent_data loop=$browse.persistent_data}
    <input type="hidden" name="{$:key|wash}" value="{$:item|wash}" />
{/section}

<input type="hidden" name="BrowseActionName" value="{$browse.action_name}" />
{section show=$browse.browse_custom_action}
<input type="hidden" name="{$browse.browse_custom_action.name}" value="{$browse.browse_custom_action.value}" />
{/section}

        <input class="button" type="submit" name="SelectButton" value="{'Select'|i18n('design/standard/content/browse')}" />
</form>

        <form class="blockpart" name="test" method="post" action={"content/browse"|ezurl}>
            <input class="button" type="submit" name="CancelButton" value="{'Cancel'|i18n( 'design/standard/content/browse' )}" />
        </form>

<form name="test" method="post" action={"content/action"|ezurl}>
<table>
        <tr>
            <th colspan="2">
                {"Create new"|i18n("design/standard/content/browse")}
            </th>
        </tr>
        <tr class="bgdark">
        <td colspan="2" align="left">
	<select	name="ClassID" class="classcreate">
	    {section name=Classes loop=$main_node.object.can_create_class_list}
	    <option value="{$Classes:item.id}">{$Classes:item.name}</option>
	    {/section}
	</select>
	<input class="menubutton" type="submit" name="NewButton" value="{'New'|i18n('design/standard/content/browse')}" />
	<input class="menubutton" type="hidden" name="RedirectURIAfterPublish" value="/content/browse/" />
	<input class="menubutton" type="hidden" name="NodeID" value="{$main_node.node_id}" />
	</td>
        </tr>
</table>
</form>
{/let}
