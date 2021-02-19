<{extends file="admin/extends/edit.block.tpl"}>

<{block "head-plus"}>
<{include file="common/uploader.inc.tpl"}>
<{/block}>

<{block "inline-script-plus"}>
$('#cover_id').uploader();
<{/block}>

<{block "title"}>项目<{/block}>
<{block "subtitle"}><{$_data.name}><{/block}>

<{block "name"}>project<{/block}>

<{block "block-title-title"}>
<{include file="admin/project/fields-nav.inc.tpl"}>
<{/block}>

<{block "fields"}>
<{include file="admin/project/fields.inc.tpl"}>
<{/block}>
