{* DO NOT EDIT THIS FILE! Use an override template instead. *}
<form action={concat("/shop/statistics")|ezurl} method="post" name="Statistics">
<div class="maincontentheader">
  <h1>{"Statistics"|i18n("design/standard/shop")}</h1>
</div>

<select name="Year">
    <option value="0" {section show=eq($year,0)}selected="selected"{/section}>[{"All Year"|i18n("design/standard/shop")}]</option>
    {section var=YearValue loop=$year_list}
        <option value="{$YearValue}" {section show=eq($YearValue,$year)}selected="selected"{/section}>{$YearValue}</option>
    {/section}
</select>
&nbsp;

<select name="Month">
    <option value="0" {section show=eq($month,0)}selected="selected"{/section}>[{"All Month"|i18n("design/standard/shop")}]</option>
    {section var=MonthItem loop=$month_list}
        <option value="{$MonthItem.value}" {section show=eq($MonthItem.value,$month)}selected="selected"{/section}>{$MonthItem.name}</option>
    {/section}
</select>
&nbsp;

<input class="button" type="submit" name="View" value="{'View'|i18n('design/standard/shop')}" />

{section show=$statistic_result}
<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<th>
	{"Product"|i18n("design/standard/shop")}
	</th>
	<th>
	{"Amount"|i18n("design/standard/shop")}
	</th>
	<th>
	{"Total ex. VAT"|i18n("design/standard/shop")}
	</th>
	<th>
	{"Total inc. VAT"|i18n("design/standard/shop")}
	</th>
</tr>
{section var="Product" loop=$statistic_result[0].product_list sequence=array(bglight,bgdark)}
<tr>
	<td class="{$Product.sequence}">
    {content_view_gui view=text_linked content_object=$Product.product}
	</td>
    <td class="{$Product.sequence}">
	{$Product.sum_count}
	</td>
	<td class="{$Product.sequence}">
	{$Product.sum_ex_vat|l10n(currency)}
	</td>
	<td class="{$Product.sequence}">
	{$Product.sum_inc_vat|l10n(currency)}
	</td>
</tr>
{/section}
<tr>
	<td class="bgdark">
    <h2>{"SUM"|i18n("design/standard/shop")}</h2>:
	</td>
    <td class="bgdark">
	</td>
	<td class="bgdark">
    <b>{$statistic_result[0].total_sum_ex_vat|l10n(currency)}</b>
	</td>
	<td class="bgdark">
	<b>{$statistic_result[0].total_sum_inc_vat|l10n(currency)}</b>
	</td>
</tr>
</table>
</form>