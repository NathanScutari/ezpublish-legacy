{section show=eq($:contentStructureTree, false())|not()}
    {let parentNode     = $contentStructureTree.parent_node
         children       = $contentStructureTree.children
         haveChildren   = count($contentStructureTree.children)|gt(0)
         showToolTips   = ezini( 'TreeMenu', 'ToolTips'         , 'contentstructuremenu.ini' )
         classIconsSize = ezini( 'TreeMenu', 'ClassIconsSize'   , 'contentstructuremenu.ini' )
         toolTip        = ""
         visibility     = 'Visible' }

        {section show=is_set($class_icons_size)}
            {set classIconsSize=$class_icons_size}
        {/section}

        {section show=$:parentNode.node.is_hidden}
            <li id="n{$:parentNode.node.node_id}" class="hiddennode">
        {section-else}
            {section show=$:parentNode.node.is_invisible}
                <li id="n{$:parentNode.node.node_id}" class="invisiblenode">
            {section-else}
                <li id="n{$:parentNode.node.node_id}">
            {/section}
        {/section}

            {* Fold/Unfold/Empty: [-]/[+]/[ ] *}
                {section show=$:haveChildren}
                    <a class="openclose" href="#" title="{'Fold/Unfold'|i18n('design/standard/contentstructuremenu/show_content_structure')}" onclick="ezcst_onFoldClicked( this.parentNode ); return false;"></a>
                {section-else}
                    <span class="openclose"></span>
                {/section}

            {* Icon *}
                <a class="nodeicon" href={$:parentNode.node.path_identification_string|ezurl}>{$:parentNode.object.class_identifier|class_icon( "$:classIconsSize", "Show 'Edit' menu" )}</a>
            {* Label *}
                {* Tooltip *}
                {section show=$:showToolTips|eq('enabled')}
                    {section show=$:parentNode.node.is_invisible}
                        {set visibility = 'Hidden by superior'}
                    {/section}
                    {section show=$:parentNode.node.is_hidden}
                        {set visibility = 'Hidden'}
                    {/section}
                    {set toolTip = 'Node ID: %node_id Visibility: %visibility' |
                                    i18n("design/standard/contentstructuremenu/show_content_structure", , hash( '%node_id'      , $:parentNode.node.node_id,
                                                                                                '%visibility'   , $:visibility ) ) }
                {section-else}
                    {set toolTip = ''}
                {/section}

                {* Text *}
                {section show=$:csm_menu_item_click_action|eq('')}
                    {let defaultItemClickAction = $:parentNode.node.path_identification_string|ezurl(no)}
                        <a class="nodetext" href="{$:defaultItemClickAction}"  title="{$:toolTip}">
                    {/let}
                {section-else}
                        <a class="nodetext" href="{$:csm_menu_item_click_action}/{$:parentNode.node.node_id}" title="{$:toolTip}">
                {/section}

                {let defaultItemClickAction = $:parentNode.node.path_identification_string|ezurl(no)}
                    <a class="nodetext" href="{$:defaultItemClickAction}" onclick="this.href='javascript:ezcst_onItemClicked( {$:parentNode.node.node_id}, \'{$:defaultItemClickAction}\' )'" title="{$:toolTip}">{$:parentNode.object.name|wash}</a>
                {/let}

            {* Show children *}
                {section show=$:haveChildren}
                    <ul>
                        {section var=child loop=$:children}
                            {include uri="design:contentstructuremenu/show_content_structure.tpl" contentStructureTree=$:child}
                        {/section}
                    </ul>
                {/section}
        </li>
    {/let}
{/section}
