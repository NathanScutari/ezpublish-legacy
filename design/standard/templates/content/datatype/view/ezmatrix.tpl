{let matrix=$attribute.content}

<table>
<tr>
<th>
{section name=ColumnNames loop=$matrix.columns.sequential}
{$ColumnNames:item.name}

{delimiter}
</th>
<th>
{/delimiter}
{/section}
</th>
</tr>
<tr>
{section name=Rows loop=$matrix.rows.sequential}
<td>
{section name=Columns loop=$Rows:item.columns}
{$Rows:Columns:item}
{delimiter}
</td>
<td>
{/delimiter}
{/section}
</td>
{delimiter}
</tr>
<tr>
{/delimiter}
{/section}
</tr>
</table>

{/let}
